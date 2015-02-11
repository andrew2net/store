<?php
/* @var $price_type Price */

Yii::import('application.modules.catalog.models.Top10');
Yii::import('application.modules.catalog.models.Product');
$top10 = Product::model()->availableOnly()->top()->findAll();
if (count($top10)) {
  ?>
  <div class="blue bold" style="font-size: 16pt; margin-bottom: 10px">Лидеры продаж</div>
  <div style="position: relative">
    <div class="top10carousel" style="padding: 15px 0 0">
      <ul>
        <?php foreach ($top10 as $value) { ?>
          <li>
            <?php $this->renderPartial('_item', array('data' => $value, 'price_type' => $price_type)); ?>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>
<?php } ?>