<?php
/* @var $profile Profile */
/* @var $message array */
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
    echo CHtml::tag('div', array(), 'Вы запросили восстановление пароля на сайте '.$message['site_name']);
    echo CHtml::tag('div', array(), 'Для получения нового пароля перейдите по ссылке ', FALSE);
    echo CHtml::tag('a', array('href'=>$message['activation_url']), $message['activation_url']);
    echo CHtml::closeTag('div');
    $this->renderInternal(dirname(__FILE__) . '/_footer.php');
    ?>
  </body>
</html>
