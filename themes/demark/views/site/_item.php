<?php
/* @var $data CActiveDataProvider */
?>
<?php
Yii::import('application.modules.payments.models.Currency');
$profile = ProfileController::getProfile();
$currecy = Currency::model()->findByAttributes(array('country_code' => $profile->price_country));
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
//  $remainder = $data->remainder > 0 ? 'Осталось ' . $data->remainder . ' шт' : 'Временно отсутствует';
  $remainder_class = 'gray';
} else {
  $percent = '';
  $price = number_format($price, 0, '.', ' ');
  $old_price = '';
  $remainder_class = 'gray';
}
$remainderLocale = "remainder_$profile->price_country";
$remainder = $data->$remainderLocale > 0 ? 'В наличии' : 'Нет на складе';
$href_params = array('id' => $data->id);
$prodName = html_entity_decode($data->name, ENT_COMPAT, 'UTF-8');
if (isset($index) && $index == 0)
  echo CHtml::hiddenField('currentPage', $widget->dataProvider->getPagination()->getCurrentPage());
echo CHtml::hiddenField('url', Yii::app()->request->url, array('id' => "url$data->id"));
?>
<div class="helper"></div>
<div class="item" title="<?php echo$data->name; ?>">
    <a class="item-link" href="<?php echo Yii::app()->createUrl('product', $href_params); ?>">
        <div class="box-item">
            <div class="<?php echo empty($percent) ? '' : 'discount-label'; ?>"><?php echo $percent; ?></div>
            <!--<div class="<?php // echo $glass;        ?>"></div>-->
            <div class="item-img img-anim">
              <!--<a class="fancybox" href="<?php echo $data->img; ?>">-->
                <img src="<?php echo $data->small_img; ?>" alt="Изображение">
                <!--</a>-->
            </div>
            <div class="item-name blue"><?php echo (mb_strlen($prodName, 'utf-8') > 45 ? mb_substr($prodName, 0, 42, 'utf-8') . '...' : $prodName) . ' ' . $data->article; ?></div>
            <div class="item-rest bold <?php echo $remainder_class; ?>"><?php echo $remainder; ?></div>
            <div class="item-disc <?php echo ($data->$remainderLocale > 0 ? 'item-hide ' : ''); ?>red"><?php echo $old_price; ?></div>
            <div class="item-price <?php echo ($data->$remainderLocale > 0 ? 'item-hide ' : ''); ?>blue"><?php echo $price . $currecy->class; ?></div>
            <?php if ($data->$remainderLocale > 0) { ?>
              <div class="item-bt addToCart" data-product="<?php echo $data->id; ?>"><div>В корзину</div></div>
            <?php } ?>
            <div class="item-add-proc"></div>
        </div>
    </a>
</div>