<?php
/* @var $profile Profile */
/* @var $order Order */
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
    echo CHtml::tag('div', array('style' => 'font-size:16pt;font-weight:bold;margin-bottom:1em'), 'Здравствуйте ' . $profile->first_name . ' ' . $profile->last_name . '!');
    echo CHtml::tag('div', array(), 'Ваш заказ принят.' . ($order->payment->type == Payment::TYPE_CAHSH ? ' Мы сообщим Вам, когда можно будет забрать товар.' : ''));
    $params = array(
      'order' => $order,
    );
    $this->renderInternal(dirname(__FILE__) . '/_order.php', $params);
    $this->renderInternal(dirname(__FILE__) . '/_footer.php');
    ?>
  </body>
</html>
