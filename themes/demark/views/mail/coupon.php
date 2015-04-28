<?php
/* @var $profile CustomerProfile */
/* @var $coupon Coupon */
/* @var $this CController */
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title></title>
  </head>
  <body>
    <div style="font-size:16pt;font-weight:bold;margin-bottom:1em">Здравствуйте!</div>
    <p style="font-weight:bold">Благодарим Вас за регистрацию в интернет-магазине <a href="<?php echo Yii::app()->createAbsoluteUrl(''); ?>"><?php echo Yii::app()->createAbsoluteUrl(''); ?></a></p><br>
    <p>Каждому новому покупателю мы дарим купон со скидкой <span style="font-weight:bold"><?php echo $coupon->value; ?> %</span> на первую покупку!</p>
    <p>Введите купон при оформлении заказа и получите скидку.</p><br>
    <p style="font-size:14pt;">Купон:<span style="font-weight: bold"><?php echo $coupon->code; ?></span></p>
    <p><span style="font-weight:bold">Внимание!</span> Купон нужно использовать в течении <span style="font-weight:bold">5-и дней</span>, затем он будет аннулирован.</p><br>
    <?php $this->renderInternal(dirname(__FILE__) . '/_footer.php', ['customerProfile' => $profile]); ?>
  </body>
</html>
