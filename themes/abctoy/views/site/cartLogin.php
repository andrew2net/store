<?php
/* @var $order Order */
/* @var $profile CustomerProfile */
?>
<?php $this->pageTitle = Yii::app()->name . ' - Ввод пароля'; ?>
<div class="container" id="page">
  <?php
  $this->renderPartial('_topblock');
  if (isset($profile)) {
    ?>
    <div style="font-size: 14pt; margin-top: 30px">Пользователь с адресом электройнной почты <span style="color: rgb(51, 153, 204)"><?php echo $profile->email; ?></span> уже зарегистрирован на этом сайте.</div>
    <div style="font-size: 14pt; margin: 1em 0 2em">Чтобы войти в личный кабинет, небходимо ввести пароль.</div>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
      'id' => 'item-submit',
//      'stateful' => TRUE,
    ));
    ?>
    <?php echo CHtml::hiddenField('profile_id', $profile->id); ?>
    <?php echo CHtml::hiddenField('order_id', $order->id); ?>
    <?php echo CHtml::label('Пароль', 'password', array('style' => 'font-size: 14pt')); ?>
    <?php echo CHtml::passwordField('password', '', array('style' => 'font-size: 14pt')); ?>
    <?php
    echo CHtml::submitButton('Вход', array(
//      'name' => 'step2',
      'style' => 'font-size: 14pt',
    ));
    ?>
  <?php $this->endWidget(); ?>
<?php } ?>
</div>
