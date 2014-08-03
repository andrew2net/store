<?php
/* @var $email string */
?>
<div class="yellow-background yellow-border">
  <span id="close-cart-dialog" class="close-dialog right" title="Закрыть диалог"></span>
  <div>Пользователь с адресом электройнной почты <span id="email-dialog" style="color: rgb(51, 153, 204)"><?php echo $email; ?></span> уже зарегистрирован на этом сайте.</div>
  <div style="margin: 1em 0 2em">Чтобы войти в личный кабинет, небходимо ввести пароль.</div>
  <?php echo CHtml::label('Пароль', 'cart-password'); ?>
  <?php echo CHtml::passwordField('cart-password'); ?>
  <?php echo CHtml::Button('Вход', array('id' => 'submit-password')); ?>
  <div class="red" id="passw-err" style="height: 10px"></div>
  <div style="margin-top: 1em">
    Забыли пароль? <?php echo CHtml::Button('Восстановить', array('id' => 'recover-password')); ?>
    <img src="/images/process.gif" style="display: none; vertical-align: middle; margin-left: 15px" id="loading-dialog" />
  </div>
  <div id="sent-mail-recovery" style="height: 40px"></div>
</div>