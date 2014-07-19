<?php
/* @var $data CActiveDataProvider */
?>
<?php
Yii::import('application.modules.payments.models.Currency');
$currecy = Currency::model()->findByAttributes(array('country_code' => 'RU'));
/* @var $currecy Currency */
$price = $data->price;

$discount = $data->getActualDiscount();
if ($discount) {
  $percent = '-' . $discount . '%';
  $old_price = '<span>' . number_format($price, 0, '.', ' ') . '</span>' . $currecy->class;
  $price = number_format(round($price * (1 - $discount / 100)), 0, '.', ' ');
}
else {
  $percent = '';
  $price = number_format($price, 0, '.', ' ');
  $old_price = '';
}

if ($data->remainder < 10) {
  $remainder = $data->remainder > 0 ? $data->remainder . ' шт' : '';
  $remainder_class = 'gray';
}
else {
  $remainder = $data->remainder > 0 ? 'есть' : '';
  $remainder_class = 'gray';
}

if (isset($index) && $index == 0)
  echo CHtml::hiddenField('currentPage', $widget->dataProvider->getPagination()->getCurrentPage());
echo CHtml::hiddenField('url', Yii::app()->request->url);
?>
<div class="item-inline">
  <?php if ($discount) { ?>
    <div class="discount-label" txt="<?php echo $percent; ?>"></div>
  <?php } ?>
  <div class="item-inline-img img-anim">
    <img title="<?php echo$data->name; ?>" src="<?php echo $data->small_img; ?>" alt="Изображение">
  </div>
  <div class="item-inline-art"><?php echo $data->article; ?></div>
  <div class="item-inline-name"><?php echo $data->name; ?></div>
  <div class="item-inline-rest <?php echo $remainder_class; ?>"><?php echo $remainder; ?></div>
  <div class="item-inline-price">
    <?php if ($discount) { ?>
      <div class="item-disc red"><?php echo $old_price; ?></div>
    <?php } ?>
    <div class="item-price blue"><?php echo $price . $currecy->class; ?></div>
  </div>
  <div class="item-inline-add">
    <!--<div class="item-inline-quantity"></div>-->
    <div class="item-inline-bt addToCart" product="<?php echo $data->id; ?>">
      <?php echo CHtml::numberField('quantity', 1, array('min' => 1, 'class' => 'item-inline-quantity')); ?>
      <div title="Добавить в корзину"></div>

    </div>
  </div>
</div>