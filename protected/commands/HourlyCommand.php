<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HourlyCommand
 *
 * @author atlant
 */
class HourlyCommand extends CConsoleCommand {

  public function run($args) {
//    global $argv;
    Yii::import('application.modules.admin.models.Mail');
    Yii::import('application.models.Newsletter');
    Yii::import('application.models.NewsletterBlock');
    Yii::import('application.modules.user.models.User');
    Yii::import('application.modules.user.models.Profile');
    Yii::import('ext.yii-mail.YiiMailMessage');

    $mails = Mail::model()->findAll(array(
      'with' => array('user'),
      'condition' => 't.status_id=1'));
    /* @var $mails Mail[] */
    foreach ($mails as $mail) {
      try {
        $message = new YiiMailMessage;
        $message->setFrom(Yii::app()->params['infoEmail']);
        $message->setTo(array($mail->user->email => $mail->user->profile->first_name . ' ' . $mail->user->profile->last_name));
        switch ($mail->type_id) {
          case Mail::TYPE_SEND_NEWSLETTER:
            $imagepath = dirname(Yii::app()->getBasePath()) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR .
              Yii::app()->params['img_storage'] . DIRECTORY_SEPARATOR . 'newsletter' . DIRECTORY_SEPARATOR;

            $logo = Swift_Image::fromPath(dirname(Yii::app()->getBasePath()) . '/themes/' .
                Yii::app()->params['img_storage'] . '/img/logo.png');
            $imageIds['logo'] = $message->embed($logo);
            $imageIds['blocks'] = [];
            foreach ($mail->newsletter[0]->newsletterBlocks as $key => $block) {
              if (!$block->image) {
                continue;
              }
              $image = Swift_Image::fromPath($imagepath . $block->image);
              $imageIds['blocks'][$key] = $message->embed($image);
            }

            $pricepath = dirname(Yii::app()->getBasePath()) . '/uploads/' . Yii::app()->params['img_storage'] . '/price.xls';
            if ($mail->newsletter[0]->send_price && file_exists($pricepath)) {
              $price = Swift_Attachment::fromPath($pricepath);
              $message->attach($price);
            }

            $params['imageIds'] = $imageIds;
            $params['profile'] = $mail->user->profile;
            $params['newsletter'] = $mail->newsletter[0];
            $message->view = 'newsletter';
            $message->setSubject($mail->newsletter[0]->subject);
            break;
        }

        $message->setBody($params, 'text/html');
        $n = Yii::app()->mail->send($message);
        if ($n) {
          $mail->status_id = 2;
          $mail->sent_time = Yii::app()->dateFormatter->format('dd-MM-yyyy HH:mm:ss', time());
          if (!$mail->validate()) {
            $result = $mail->getErrors();
            foreach ($result as $item) {
              foreach ($item as $err) {
                Yii::log($err, CLogger::LEVEL_INFO, 'cron');
              }
            }
          } else
            $mail->save();
        }
      } catch (Exception $e) {
        if ($mail->errors > 2) {
          $mail->status_id = Mail::STATUS_ERROR;
          $mail->user->profile->newsletter = 0;
          $mail->user->profile->save();
        } else {
          if (is_null($mail->errors)) {
            $mail->errors = 1;
          } else {
            $mail->errors ++;
          }
        }
        $mail->save();
        Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, 'Send_mail_error');
      }
    }
  }

}
