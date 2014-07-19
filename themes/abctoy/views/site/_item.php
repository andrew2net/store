<?php
/* @var $data CActiveDataProvider */
?>
<?php
$discount = $data->getActualDiscount();
if (is_array($discount)) {
  $percent = '-' . $discount['discount'] . '%';
  $old_price = number_format($data->price, 0, '.', ' ');
  $price = number_format($discount['price'], 0, '.', '');
  $remainder = $data->remainder > 0 ? 'Осталось ' . $data->remainder . ' шт' : '';
  $remainder_class = 'gray';
  $glass = 'red-glass';
  $item = 'red-item';
  $button = 'red-item-bt';
}
else {
  $percent = '';
  $price = number_format($data->price, 0, '.', '');
  $old_price = '';
  $remainder = $data->remainder > 0 ? 'В наличии' : '';
  $remainder_class = 'green';
  $glass = 'green-glass';
  $item = 'green-item';
  $button = 'green-item-bt';
}
$href_params = array('id' => $data->id);
?>
<?php
if (isset($index) && $index == 0)
  echo CHtml::hiddenField('currentPage', $widget->dataProvider->getPagination()->getCurrentPage());
echo CHtml::hiddenField('url', Yii::app()->request->url);
?>
<div class="helper"></div>
<div class="item" title="<?php echo$data->name; ?>">
  <a class="item-link" href="<?php echo $this->createUrl('product', $href_params); ?>">
    <div class="<?php echo $item; ?>">
      <div class="<?php echo empty($percent) ? '' : 'discount-label'; ?>"><?php echo $percent; ?></div>
      <!--<div class="<?php // echo $glass;  ?>"></div>-->
      <div class="item-img">
        <!--<a class="fancybox" href="<?php echo $data->img; ?>">-->
          <img src="<?php echo $data->small_img; ?>" alt="Изображение">
        <!--</a>-->
      </div>
      <div class="item-name"><?php echo mb_strlen($data->name, 'utf-8') > 27 ? mb_substr($data->name, 0, 24, 'utf-8') . '...' : $data->name; ?></div>
      <div class="item-rest <?php echo $remainder_class; ?>"><?php echo $remainder; ?></div>
      <div class="item-disc"><?php echo $old_price; ?></div>
      <div class="cufon item-price"><?php echo $price; ?>.-</div>
      <div class="<?php echo $button; ?> inline-blocks addToCart" product="<?php echo $data->id; ?>">
        <div class="left"></div>
        <div class="center">В КОРЗИНУ</div>
        <div class="right"></div>
      </div>
    </div>
  </a>
</div>