<?php

/**
 * Description of minuteCommand
 *
 */
class MinutelyCommand extends CConsoleCommand {

  public function run($args) {
//    global $argv;
    Yii::import('application.modules.admin.models.Mail');
    Yii::import('application.modules.admin.models.MailOrder');
    Yii::import('application.models.Order');
    Yii::import('application.models.Pay');
    Yii::import('application.models.OrderProduct');
    Yii::import('application.models.CustomerProfile');
//    Yii::import('application.models.Newsletter');
//    Yii::import('application.models.NewsletterBlock');
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.user.models.User');
    Yii::import('application.modules.user.models.Profile');
    Yii::import('application.modules.delivery.models.Delivery');
    Yii::import('application.modules.payments.models.Payment');
    Yii::import('ext.yii-mail.YiiMailMessage');

//    $conn = explode('=', $argv[2]);
//    Yii::trace('Start minutely ' . $conn[1], 'cron');

    $mails = Mail::model()->findAll(array(
      'with' => array('user', 'order'),
      'condition' => 't.status_id=1'));
    /* @var $mails Mail[] */
    foreach ($mails as $mail) {
      $tr = Yii::app()->db->beginTransaction();
      unset($params);
      try {
        $message = new YiiMailMessage;
        $message->setFrom(Yii::app()->params['infoEmail']);
        $message->setTo(array($mail->user->email => $mail->user->profile->first_name . ' ' . $mail->user->profile->last_name));
        switch ($mail->type_id) {
          case Mail::TYPE_CONFIRM_ORDER:
            $message->view = 'confirmOrder';
            $params['order'] = $mail->order[0];
            $params['profile'] = $mail->user->profile;
            $message->setSubject("Ваш заказ");
            break;
          case Mail::TYPE_CHANGE_ORDER_STATUS:
            $message->view = 'processOrder';
            $params['order'] = $mail->order[0];
            $params['profile'] = $mail->user->profile;
            switch ($mail->order[0]->status_id) {
              case Order::STATUS_WAITING_FOR_PAY:
                $message->view = 'payOrder';
                $message->setSubject("Оплата заказа");
                $params['text'] = 'готов к оплате';
                break;
              case Order::STATUS_PAID:
                $message->setSubject("Поступила оплата");
                $params['text'] = 'оплачен и готов к отгрузке';
                break;
              case Order::STATUS_SENT:
                $message->setSubject("Заказ отгружен");
                $params['text'] = 'отгружен со склада';
                break;
              case Order::STATUS_CANCELED:
                $message->setSubject("Отмена заказа");
                $params['text'] = 'отменен';
                break;
              default :
                $message->setSubject("Изменение статуса заказа");
                $params['text'] = mb_strtolower($mail->order[0]->status, 'utf8');
                break;
            }
            break;
          case Mail::TYPE_NEW_ORDER_NOTIFY:
            $message->view = 'notifyOrder';
            $params['order'] = $mail->order[0];
            $message->setSubject('Оповещение о заказе');
            break;
        }

        if (isset($params)) {
          Yii::trace("user $mail->uid type $mail->type_id", 'Send_mail_error');
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
        }
        $tr->commit();
      } catch (Exception $e) {
        $tr->rollback();
        if ($mail->errors > 2) {
          $mail->status_id = Mail::STATUS_ERROR;
        } else {
          $mail->errors ++;
        }
        $mail->save();
        Yii::trace("user $mail->uid", 'Send_mail_error');
      }
    }
  }

}
