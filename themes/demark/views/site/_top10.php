<?php
Yii::import('application.modules.catalog.models.Top10');
Yii::import('application.modules.catalog.models.Product');
$top10 = Product::model()->availableOnly()->top()->findAll();
if (count($top10)){
?>

<div class="inline-blocks" style="margin: 30px 0 5px 10px">
  <div class="blue bold" style="font-size: 16pt">Лидеры продаж</div>
  <div style="float: right; font-size: 12pt">
    <span class="blue bold">Доверьтесь выбору наших покупателей</span>
  </div>
</div>
<div style="position: relative">
<div class="top10carousel" style="position: static; height: 300px">
  <ul>
    <?php foreach ($top10 as $value) { ?>
      <li>
        <?php $this->renderPartial('_item', array('data' => $value)); ?>
      </li>
    <?php } ?>
  </ul>
  <a class="top10carousel-prev" href="#"></a>
  <a class="top10carousel-next" href="#"></a>
</div>
</div>
<!--<div style="text-align: right; line-height: 3"><a href="#">Все товары</a></div>-->
<?php } ?>