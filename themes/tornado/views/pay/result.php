<?php $this->pageTitle = Yii::app()->name . ' - Оплата заказа'; ?>
<div class="container" id="page">
  <h1 class="bold blue" style="margin-top: 40px">Оплата прошла успешно</h1>
  <div>
    <?php echo CHtml::link('Вернуться на главную страницу', '/'); ?><br>
    <?php echo CHtml::link('Личный кабинет', '/profile'); ?>
  </div>
</div>