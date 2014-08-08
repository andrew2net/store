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
