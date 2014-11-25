<?php
/* @var $data Product */
/* @var $price_type Price */

Yii::import('application.modules.payments.models.Currency');
$currecy = Currency::model()->findByAttributes(array('country_code' => 'RU'));
/* @var $currecy Currency */
$price = $data->getTradePrice($price_type);

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
  $remainder = $data->remainder > 0 ? 'остаток ' . $data->remainder . ' шт' : '';
  $remainder_class = 'gray';
}
else {
  $remainder = $data->remainder > 0 ? 'в наличии' : '';
  $remainder_class = 'gray';
}

if (isset($index) && $index == 0)
  echo CHtml::hiddenField('currentPage', $widget->dataProvider->getPagination()->getCurrentPage());
echo CHtml::hiddenField('url', Yii::app()->request->url, array('id' => "url$data->id"));
?>
<div class="helper"></div>
<div data-product="<?php echo $data->id; ?>" class="item" title="<?php echo $data->name; ?>">
  <div class="box-item">
    <?php if ($discount) { ?>
      <div class="discount-label" txt="<?php echo $percent; ?>"></div>
    <?php } ?>
    <div class="item-img img-anim">
      <!--<a class="fancybox" href="<?php echo $data->img; ?>">-->
      <img src="<?php echo $data->small_img; ?>" alt="Изображение" data-big-img="<?php echo $data->img; ?>">
      <!--</a>-->
    </div>
    <div class="item-name"><?php echo $data->name; ?></div>
    <div class="item-rest <?php echo $remainder_class; ?>"><?php echo $remainder; ?></div>
    <div class="item-price-box">
      <?php if ($discount) { ?>
        <span class="item-disc red"><?php echo $old_price; ?></span>
      <?php } ?>
        <span title="Ваша цена &quot;<?php echo $price_type->name; ?>&quot;" class="item-price blue"><?php echo $price . $currecy->class; ?></span>
    </div>
    <div class="item-bt addToCart inline-blocks" data-product="<?php echo $data->id; ?>">
      <?php echo CHtml::numberField('quantity', 1, array('min' => 1, 'class' => 'item-inline-quantity')); ?>
      <div title="Добавить в корзину"></div>
    </div>
  </div>
</div>