<?php
Yii::import('application.modules.discount.models.Discount');
Yii::import('application.modules.catalog.models.Category');
Yii::import('application.modules.catalog.models.Product');
//$week = Discount::model()->week()->findAll();
$week = Product::model()->week()->availableOnly()->recommended()->findAll();
$products = array();
$end_dates = array();
foreach ($week as $value) {
  $end_dates[] = DateTime::createFromFormat('Y-m-d', $value->w_end_date);
  $products[] = $value;
}
if (count($end_dates)) {
  $end_date = date_format(min($end_dates), 'd-m-Y');
  ?>

  <div class="inline-blocks" style="margin: 20px 0">
    <div class="icon-dicount"></div>
    <div class="cufon red bold" style="font-size: 20pt; position: relative; padding: 0 10px">Скидка недели</div>
    <div style="float: right; font-size: 12pt; position: relative; top: 20px; width: 210px">
      <span class="cufon red bold">Осталось: </span><span class="clock red bold" style="width: 40px" date="<?php echo $end_date; ?>"></span>
    </div>
  </div>
  <div style="position: relative">
    <div class="weekcarousel" style="position: static; height: 300px">
      <ul>
        <?php foreach ($products as $value) { ?>
          <li>
            <?php $this->renderPartial('_item', array('data' => $value)); ?>
          </li>
        <?php } ?>
      </ul>
      <a class="weekcarousel-prev" href="#"></a>
      <a class="weekcarousel-next" href="#"></a>
    </div>
  </div>
  <div style="text-align: right; line-height: 3"><a class="red" href="/discount">Все товары со скидкой</a></div>
<?php } ?>