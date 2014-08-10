<?php
/* @var $customer_profile CustomerProfile */
/* @var $profile Profile */
/* @var $order Order */
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
    echo CHtml::tag('div', array(), 'Ваш заказ принят. В ближайшее время Вам придет уведомление о резервировании товара.');
    $params = array(
      'order' => $order,
      'profile' => $customer_profile,
    );
    if (isset($coupon_discount))
      $params['coupon_discount'] = $coupon_discount;
    $this->renderPartial('//mail/_order', $params);
    $this->renderPartial('//mail/_footer');
    ?>
  </body>
</html>
