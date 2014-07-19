<?php
/* @var $this SiteController */
/* @var $loginForm LoginForm */

$this->pageTitle = Yii::app()->name . ' - Вход';
?>
<div class="container" id="page">
  <?php $this->renderPartial('_topblock'); ?>

  <h1 class="cufon bold blue" style="margin-top: 40px">Вход в личный кабинет</h1>

  <?php $form = $this->beginWidget('CActiveForm'); ?>
  <div style="margin: 20px 0">
    <?php
    echo $form->label($loginForm, 'username', array(
      'class' => 'cufon bold'
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
      'class' => 'cufon bold'
    ));
    ?><br>
    <?php
    echo CHtml::activePasswordField($loginForm, 'password', array(
      'class' => 'input-text'
    ));
    ?><br>
    <?php echo CHtml::error($loginForm, 'password', array('class' => 'red')); ?>
  </div>
  <div style="margin-top: 40px">
    <a class="submit" href="#">
      <div class="greenbutton inline-blocks">
        <div class="left"></div>
        <div class="center">ВХОД</div>
        <div class="right"></div>
      </div>
    </a>
  </div>

  <?php $this->endWidget(); ?>
</div>

</div>