<?php
Yii::import('application.modules.discount.models.Discount');
Yii::import('application.modules.catalog.models.Category');
Yii::import('application.modules.catalog.models.Product');
//$week = Discount::model()->week()->findAll();
$week = Product::model()->week()->availableOnly()->findAll();
$products = array();
$end_dates = array();
foreach ($week as $value) {
  $end_dates[] = DateTime::createFromFormat('Y-m-d', $value->w_end_date);
  $products[] = $value;
}
if (count($end_dates)) {
  $end_date = date_format(min($end_dates), 'd-m-Y');
  ?>

  <div class="inline-blocks" style="margin: 30px 0 5px 10px">
    <div class="blue bold" style="font-size: 16pt; position: relative; padding: 0 10px">Скидка недели</div>
    <div style="float: right; font-size: 12pt; position: relative; width: 220px">
      <span class="blue bold">Осталось: </span><span class="clock blue bold" style="width: 40px" date="<?php echo $end_date; ?>"></span>
    </div>
  </div>
  <div style="position: relative">
    <div class="weekcarousel" style="position: static; height: 300px">
      <ul>
        <?php foreach ($products as $value) { ?>
          <li>
            <?php $this->renderPartial('_item', array('data' => $value, 'price_type' => $price_type)); ?>
          </li>
        <?php } ?>
      </ul>
      <a class="weekcarousel-prev" href="#"></a>
      <a class="weekcarousel-next" href="#"></a>
    </div>
  </div>
  <div style="text-align: right; line-height: 3"><a class="red" href="<?php echo Yii::app()->createUrl('discount_product'); ?>">Все товары со скидкой</a></div>
<?php } ?>