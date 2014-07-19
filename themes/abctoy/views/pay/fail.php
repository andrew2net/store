<?php $this->pageTitle = Yii::app()->name . ' - Оплата заказа'; ?>
<div class="container" id="page">
  <?php $this->renderPartial('/site/_topblock'); ?>
  <h1 class="cufon bold red" style="margin-top: 40px">Платеж отменен</h1>
  <div>
    <?php echo CHtml::link('Вернуться на главную страницу', '/'); ?><br>
    <?php echo CHtml::link('Личный кабинет', '/profile'); ?>
  </div>
</div>