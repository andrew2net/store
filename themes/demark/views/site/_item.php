<?php
/* @var $data CActiveDataProvider */
?>
<?php
Yii::import('application.modules.payments.models.Currency');
$profile = ProfileController::getProfile();
$currecy = Currency::model()->findByAttributes(array('country_code'=>$profile->price_country));
/* @var $currecy Currency */
switch ($profile->price_country) {
  case 'KZ':
    $price = $data->price_tenge;
    break;
  default :
    $price = $data->price;
}

$discount = $data->getActualDiscount();
if ($discount) {
  $percent = '-' . $discount . '%';
  $old_price = '<span>' . number_format($price, 0, '.', ' ') . '</span>' . $currecy->class;
  $price = number_format(round($price * (1 - $discount / 100)), 0, '.', ' ');
  $remainder = $data->remainder > 0 ? 'Осталось ' . $data->remainder . ' шт' : '';
  $remainder_class = 'gray';
}
else {
  $percent = '';
  $price = number_format($price, 0, '.', ' ');
  $old_price = '';
  $remainder = $data->remainder > 0 ? 'В наличии' : '';
  $remainder_class = 'gray';
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
  <a class="item-link" href="<?php echo Yii::app()->createUrl('product', $href_params); ?>">
    <div class="box-item">
      <div class="<?php echo empty($percent) ? '' : 'discount-label'; ?>"><?php echo $percent; ?></div>
      <!--<div class="<?php // echo $glass;     ?>"></div>-->
      <div class="item-img img-anim">
        <!--<a class="fancybox" href="<?php echo $data->img; ?>">-->
        <img src="<?php echo $data->small_img; ?>" alt="Изображение">
        <!--</a>-->
      </div>
      <div class="item-name blue bold"><?php echo (mb_strlen($data->name, 'utf-8') > 38 ? mb_substr($data->name, 0, 35, 'utf-8') . '...' : $data->name) . ' ' . $data->article; ?></div>
      <div class="item-rest <?php echo $remainder_class; ?>"><?php echo $remainder; ?></div>
      <div class="item-disc red"><?php echo $old_price; ?></div>
      <div class="item-price blue"><?php echo $price . $currecy->class; ?></div>
      <div class="item-bt addToCart" product="<?php echo $data->id; ?>"><div>В корзину</div></div>
    </div>
  </a>
</div>