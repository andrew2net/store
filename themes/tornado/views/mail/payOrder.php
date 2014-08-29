<?php
/* @var $profile Profile */
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
    echo CHtml::tag('div', array('style' => 'font-size:16pt;font-weight:bold;margin-bottom:1em'), 'Здравствуйте ' . $profile->first_name . ' ' . $profile->last_name . '!');
    echo CHtml::tag('div', array(), "Ваш заказ №{$order->id} от {$date} {$text}.");
    echo CHtml::tag('div', array(), "Информацию о заказе и реквизиты для оплаты можно получить по адресу "
        . CHtml::link('оплаты заказа', Yii::app()->createAbsoluteUrl(
                'pay/order', array('id' => $order->id))));
    $this->renderInternal(dirname(__FILE__) . '/_footer.php');
    ?>
  </body>
</html>
