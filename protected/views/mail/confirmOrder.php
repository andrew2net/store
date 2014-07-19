<?php
/* @var $profile CustomerProfile */
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
    echo CHtml::tag('div', array('style' => 'font-size:16pt;font-weight:bold;margin-bottom:1em'), 'Здравствуйте ' . $profile->fio . '!');
    echo CHtml::tag('div', array(), 'Ваш заказ принят. В ближайшее время наши менеджеры свяжутся Вами.');
    $params = array(
      'order' => $order,
      'profile' => $profile,
    );
    if (isset($coupon_discount))
      $params['coupon_discount'] = $coupon_discount;
    $this->renderPartial('//mail/_order', $params);
    $this->renderPartial('//mail/_footer');
    ?>
  </body>
</html>
