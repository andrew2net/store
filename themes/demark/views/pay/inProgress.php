<?php $this->pageTitle = Yii::app()->name . ' - Оплата заказа'; ?>
<div class="container" id="page">
  <?php $this->renderPartial('/site/_topblock'); ?>
  <h1 class="cufon bold green" style="margin-top: 40px">Оплата в процессе</h1>
  <p>Подтверждение об оплате еще не получено от платежной системы.</p>
  <div>
    <?php echo CHtml::link('Вернуться на главную страницу', '/'); ?><br>
    <?php echo CHtml::link('Личный кабинет', '/profile'); ?>
  </div>
</div>