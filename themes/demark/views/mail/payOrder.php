<?php
/* @var $profile Profile */
/* @var $customerProfile CustomerProfile */
/* @var $order Order */
/* @var $text string */
/* @var $this CController */
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
    echo CHtml::tag('div', array('style' => 'font-size:16pt;font-weight:bold;margin-bottom:1em'), 'Здравствуйте '
        . $profile->first_name . $profile->last_name . '!');
    echo CHtml::tag('div', array(), "Ваш заказ №{$order->id} от {$date} {$text}.");
    echo CHtml::tag('div', array(), "Совершить платеж Вы можете на странице "
        . CHtml::link('оплаты заказа', Yii::app()->createAbsoluteUrl(
                'pay/order', array('id' => $order->id))));
    $this->renderInternal(dirname(__FILE__) . '/_footer.php', ['customerProfile' => $customerProfile]);
    ?>
  </body>
</html>
