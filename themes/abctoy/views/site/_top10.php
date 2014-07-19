<?php
Yii::import('application.modules.catalog.models.Top10');
Yii::import('application.modules.catalog.models.Product');
$top10 = Product::model()->availableOnly()->top()->findAll();
if (count($top10)){
?>

<div class="inline-blocks" style="margin: 20px 0">
  <div class="icon-top10"></div>
  <div class="cufon yellow bold" style="font-size: 20pt; position: relative; padding: 0 10px">ТОП 10 подарков</div>
  <div style="float: right; font-size: 12pt; position: relative; top: 20px;">
    <span class="cufon yellow bold">Доверьтесь выбору наших покупателей</span>
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