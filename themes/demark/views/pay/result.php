<?php
/* @var $this PayController */
/* @var $message array */

$this->pageTitle = Yii::app()->name . ' - Оплата заказа';
?>
<div class="container" id="page">
  <?php $this->renderPartial('/site/_topblock'); ?>
  <?php $this->renderPartial('/site/_mainmenu'); ?>
  <h1 class="bold blue" style="margin-top: 40px"><?php echo $message['title']; ?></h1>
  <p><?php echo $message['txt']; ?></p>
  <div style="margin-bottom: 15px">
    <?php echo CHtml::link('Перейти в личный кабинет', '/profile'); ?>
    <?php // echo CHtml::link('Вернуться на главную страницу', '/'); ?><br>
  </div>
</div>
<?php $this->renderPartial('//site/_footer'); ?>
