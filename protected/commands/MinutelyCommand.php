<?php

/**
 * Description of minuteCommand
 *
 */
class MinutelyCommand extends CConsoleCommand {

  public function run($args) {
    Yii::trace('Start minutely', 'cron');
    echo 'Start minutely' . "\n";
    Yii::import('application.modules.admin.models.Mail');
    Yii::import('application.modules.admin.models.MailOrder');
    Yii::import('application.models.Order');
    Yii::import('application.models.CustomerProfile');
    Yii::import('application.modules.user.models.User');
    Yii::import('application.modules.user.models.Profile');
    Yii::import('ext.yii-mail.YiiMailMessage');

    $mails = Mail::model()->findAll(array(
      'with' => array('user', 'order'),
      'condition' => 't.status_id=1'));
    /* @var $mails Mail[] */
    foreach ($mails as $mail) {
      $message = new YiiMailMessage;
      $message->setFrom(Yii::app()->params['infoEmail']);
      $message->setTo(array($mail->user->email => $mail->user->profile->first_name . ' ' . $mail->user->profile->last_name));
      switch ($mail->type_id) {
        case 3: //confirm order
          $message->view = 'confirmOrder';
          $message->setSubject("Оплата заказа");
          $params['text'] = 'готов к оплате';
          break;
        case 4: //change order status
          $message->view = 'processOrder';
          $params['order'] = $mail->order[0];
          $params['profile'] = $mail->user->profile;
          switch ($mail->order[0]->status_id) {
            case 4:
              $message->view = 'payOrder';
              $message->setSubject("Оплата заказа");
              $params['text'] = 'готов к оплате';
              break;
            case 5:
              $message->setSubject("Поступила оплата");
              $params['text'] = 'оплачен и готов к отгрузке';
              break;
            case 6:
              $message->setSubject("Заказ отгружен");
              $params['text'] = 'отгружен со склада';
              break;
            case 7:
              $message->setSubject("Отмена заказа");
              $params['text'] = 'отменен';
              break;
            default :
              $message->view = 'payOrder';
              $message->setSubject("Изменение статуса заказа");
              $params['text'] = $mail->order[0]->status;
              break;
          }
      }
      $message->setBody($params, 'text/html');
      $n = Yii::app()->mail->send($message);
      if ($n) {
        $mail->status_id = 2;
        $mail->sent_time = Yii::app()->dateFormatter->format('dd-MM-yyyy HH:mm:ss', time());
        echo $mail->made_time . "\n";
        $mail->validate();
        echo $mail->made_time . "\n";
        $result = $mail->getErrors();
        echo var_dump($result) . "\n";
        $mail->save();
        echo $mail->made_time . "\n";
      }
    }
  }

}
