<?php
/* @var $this SiteController */
/* @var $loginForm LoginForm */
/* @var $search Search */
/* @var $form CActiveForm */

$this->pageTitle = Yii::app()->name . ' - Вход';
?>
<div class="container" id="page">
  <?php $this->renderPartial('//site/_topblock'); ?>
  <?php $this->renderPartial('//site/_mainmenu'); ?>

  <h1 class="bold blue" style="margin-top: 40px">Вход в личный кабинет</h1>

  <?php $form = $this->beginWidget('CActiveForm'); ?>
  <div style="margin: 20px 0">
    <?php
    echo $form->labelEx($loginForm, 'username', array(
      'class' => 'bold'
    ));
    ?><br>
    <?php
    echo $form->textField($loginForm, 'username', array(
      'class' => 'input-text'
    ));
    ?><br>
    <?php echo $form->error($loginForm, 'username', array('class' => 'red')); ?>
  </div>
  <div>
    <?php
    echo $form->labelEx($loginForm, 'password', array(
      'class' => 'bold'
    ));
    ?><br>
    <?php
    echo $form->passwordField($loginForm, 'password', array(
      'class' => 'input-text'
    ));
    ?><br>
    <?php echo $form->error($loginForm, 'password', array('class' => 'red')); ?>
  </div>
  <?php echo CHtml::submitButton('ВХОД', array('style' => 'margin: 30px 0')); ?>
  <?php $this->endWidget(); ?>
</div>

<?php $this->renderPartial('//site/_footer'); ?>
