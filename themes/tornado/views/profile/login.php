<?php
/* @var $this SiteController */
/* @var $loginForm LoginForm */
/* @var $search Search */

$this->pageTitle = Yii::app()->name . ' - Вход';
?>
<div class="container" id="page">

  <h1 class="bold blue" style="margin-top: 40px">Вход в личный кабинет</h1>

  <?php $form = $this->beginWidget('CActiveForm'); ?>
  <div style="margin: 20px 0">
    <?php
    echo $form->label($loginForm, 'username', array(
      'class' => 'bold'
    ));
    ?><br>
    <?php
    echo $form->textField($loginForm, 'username', array(
      'class' => 'input-text'
    ));
    ?><br>
    <?php echo CHtml::error($loginForm, 'username', array('class' => 'red')); ?>
  </div>
  <div>
    <?php
    echo CHtml::activeLabel($loginForm, 'password', array(
      'class' => 'bold'
    ));
    ?><br>
    <?php
    echo CHtml::activePasswordField($loginForm, 'password', array(
      'class' => 'input-text'
    ));
    ?><br>
    <?php echo CHtml::error($loginForm, 'password', array('class' => 'red')); ?>
  </div>
  <div style="margin: 40px 0 20px" class="inline-blocks">
    <div style="vertical-align: bottom" class="login-button">
      <div class="submit">Вход</div>
    </div>
    <div style="margin: 0 20px">
      <div><a href="/user/recovery">восстановить пароль</a></div>
      <div><a href="/user/recovery">зарегистрироваться</a></div>
    </div>
  </div>
  <?php $this->endWidget(); ?>
</div>

</div>