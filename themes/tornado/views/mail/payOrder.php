<?php
/* @var $profile CustomerProfile */
/* @var $order Order */
/* @var $text string */
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title></title>
  </head>
  <body>
    <?php
    $date = Yii::app()->dateFormatter->format('dd.MM.yyyy', $order->time);
    echo CHtml::tag('div', array('style' => 'font-size:16pt;font-weight:bold;margin-bottom:1em'), 'Здравствуйте ' . $profile->fio . '!');
    echo CHtml::tag('div', array(), "Ваш заказ №{$order->id} от {$date} {$text}.");
    echo CHtml::tag('div', array(), "Совершить платеж Вы можете на странице "
        . CHtml::link('оплаты заказа', Yii::app()->createAbsoluteUrl(
                'pay/order', array('id' => $order->id))));
    $this->renderPartial('//mail/_footer');
    ?>
  </body>
</html>
