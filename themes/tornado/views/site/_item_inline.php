<?php
/* @var $data Product */
/* @var $currency Currency */
/* @var $trade_price Price */

Yii::import('application.modules.catalog.models.ProductPrice');

$price = $data->price;
$wholesale = $data->getTradePrice($trade_price);

$discount = $data->getActualDiscount();
if ($discount) {
  $percent = '-' . $discount . '%';
  $old_price = '<span>' . number_format($price, 0, '.', ' ') . '</span>' . $currency->class;
  $price = number_format(round($price * (1 - $discount / 100)), 0, '.', ' ') . $currency->class;
  if ($wholesale > 0) {
    $old_wholesale = '<span>' . number_format($wholesale, 0, '.', ' ') . '</span>' . $currency->class;
    $wholesale = number_format(round($wholesale * (1 - $discount / 100)), 0, '.', ' ') . $currency->class;
  }
  else {
    $old_wholesale = '';
    $wholesale = '--';
  }
}
else {
  $percent = '';
  $price = number_format($price, 0, '.', ' ') . $currency->class;
  $old_price = '';
  $old_wholesale = '';
  if ($wholesale > 0)
    $wholesale = number_format($wholesale, 0, '.', ' ') . $currency->class;
  else
    $wholesale = '--';
}

if ($data->remainder_RU < 10) {
  $remainder = $data->remainder_RU > 0 ? $data->remainder_RU . ' шт' : '';
  $remainder_class = 'gray';
}
else {
  $remainder = $data->remainder_RU > 0 ? 'есть' : '';
  $remainder_class = 'gray';
}

/* @var $webUser CWebUser */
$webUser = Yii::app()->user;
/* @var $user User */
$user = User::model()->with(array('customerProfile' => array('with' => 'price')))->findByPk($webUser->id);
$wholesalePrices = array();
foreach ($data->prices as $p) {
  if ($p->price && ($webUser->isGuest || !$user->customerProfile->price || $p->price_type->summ > $user->customerProfile->price->summ))
    $wholesalePrices[] = array(
      $p->price_type->summ,
      $p->price * ($discount ? 1 - $discount / 100 : 1),
    );
}

if (isset($index) && $index == 0)
  echo CHtml::hiddenField('currentPage', $widget->dataProvider->getPagination()->getCurrentPage());
echo CHtml::hiddenField('url', Yii::app()->request->url, array('id' => "url$data->id"));
?>
<div class="item-inline">
  <?php if ($discount) { ?>
    <div class="discount-label" txt="<?php echo $percent; ?>"></div>
  <?php } ?>
  <div class="item-inline-img img-anim">
    <img title="<?php echo $data->name; ?>" src="<?php echo (empty($data->small_img) ? '/images/noimage.png' : $data->small_img); ?>" alt="<?php echo $data->getSmallImageAlt(); ?>" data-big-img="<?php echo $data->img; ?>">
  </div>
  <div class="inline-blocks tooltip-price" data-price="<?php echo json_encode($wholesalePrices, JSON_NUMERIC_CHECK); ?>">
    <div class="item-inline-art bold"><?php echo $data->article; ?></div>
    <div class="item-inline-name">
        <h6><?php echo $data->name; ?></h6>
        <?php echo preg_replace("/\r\n|\r|\n/",'<br/>', $data->description); ?>
    </div>
    <div class="item-inline-rest <?php echo $remainder_class; ?>"><?php echo $remainder; ?></div>
    <div class="item-inline-price">
      <?php if ($discount) { ?>
        <div class="item-disc red"><?php echo $old_price; ?></div>
      <?php } ?>
      <div class="item-price blue"><?php echo $price; ?></div>
    </div>
    <div class="item-inline-price">
      <?php if ($discount) { ?>
        <div class="item-disc red"><?php echo $old_wholesale; ?></div>
      <?php } ?>
      <div class="item-price blue"><?php echo $wholesale; ?></div>
    </div>
  </div>
  <div class="item-inline-add">
    <!--<div class="item-inline-quantity"></div>-->
    <div class="item-inline-bt addToCart" data-product="<?php echo $data->id; ?>">
      <?php echo CHtml::numberField('quantity', 1, array('min' => 1, 'class' => 'item-inline-quantity', 'id' => "quantity$data->id")); ?>
      <div title="Добавить в корзину"></div>
    </div>
    <div class="item-add-proc"></div>
  </div>
</div>