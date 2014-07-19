<?php
/* @var $profile CustomerProfile */
/* @var $coupon Coupon */
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title></title>
  </head>
  <body>
    <div style="font-size:16pt;font-weight:bold;margin-bottom:1em">Здравствуйте!</div>
    <p style="font-weight:bold">Благодарим Вас за регистрацию в интернет-магазине <a href="<?php echo Yii::app()->createAbsoluteUrl(''); ?>">abc-toy.ru</a></p><br>
    <p>Каждому новому покупателю мы дарим купон со скидкой <span style="font-weight:bold"><?php echo $coupon->value; ?> рублей</span> на первую покупку!</p>
    <p>Введите купон при оформлении заказа и получите скидку.</p><br>
    <p style="font-size:14pt;">Купон:<span style="font-weight: bold"><?php echo $coupon->code; ?></span></p>
    <p><span style="font-weight:bold">Внимание!</span> Купон нужно использовать в течении <span style="font-weight:bold">3х дней</span>, затем он будет аннулирован.</p><br>
    <?php $this->renderPartial('//mail/_footer'); ?>
  </body>
</html>
