<?php
/* @var $profile CustomerProfile */
/* @var $login string */
/* @var $passw string */
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
    <p>Ваши регистрационные данные:</p>
    <p style="font-weight:bold">Логин: <?php echo $login; ?></p>
    <p style="font-weight:bold">Пароль: <?php echo $passw; ?></p><br>
    <p>Вы можете изменить свои регистрационные данные в разделе <a href="<?php echo Yii::app()->createAbsoluteUrl('profile'); ?>">Личный кабинет.</a></p><br>
    <?php
    $this->renderPartial('//mail/_footer');
    ?>
  </body>
</html>
