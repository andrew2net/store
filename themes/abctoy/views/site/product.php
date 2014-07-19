<?php
/* @var $product Product */
/* @var $productForm ProductForm */
/* @var $groups[] Category */
/* @var $search Search */
?>
<?php $this->pageTitle = Yii::app()->name . ' - ' . $product->name; ?>
<?php $this->renderPartial('_topmenu'); ?>

<div class="container" id="page">
  <?php $this->renderPartial('_topblock'); ?>
  <?php
  $this->renderPartial('_mainmenu', array(
    'search' => $search,
    'groups' => $groups,
  ));
  ?>
  <?php
  $categories = $product->category;
  if (isset($categories[0])) {
    $breadcrumbs = array();
//    $breadcrumbs[] = $product->name;
    $category = $categories[0];
    while (!$category->isRoot()) {
      $breadcrumbs[$category->name] = array('group', 'id' => $category->id);
      $category = $category->getParent();
    }
    $breadcrumbs[$category->name] = array('group', 'id' => $category->id);
    $breadcrumbs=array_reverse($breadcrumbs);
    $this->widget('zii.widgets.CBreadcrumbs', array(
    'links' => $breadcrumbs,
    'homeLink' => FALSE,
    'separator' => ' / ',
    'htmlOptions' => array(
      'class' => 'cufon green bold breadcrumbs',
    )
  ));
}
  ?>
  <?php
  $date_tomorrow = new DateTime('tomorrow');
  $aMonth = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
  $tomorrow = $date_tomorrow->format('d') . ' ' . $aMonth[$date_tomorrow->format('m') - 1];
  $discount = $product->getActualDiscount();
  if (is_array($discount)) {
    $percent = '-' . $discount['discount'] . '%';
    $old_price = number_format($product->price, 0, '.', ' ');
    $price = number_format($discount['price'], 0, '.', '');
  }
  else {
    $percent = '';
    $price = number_format($product->price, 0, '.', '');
    $old_price = '';
  }
  $form = $this->beginWidget('CActiveForm');
  ?>
  <div class="inline-blocks" style="margin-top: 20px">
    <div style="position: relative">
      <div class="<?php echo empty($percent) ? '' : 'discount-label-big'; ?>"><?php echo $percent; ?></div>
      <div class="img-container" style="width: 450px; height: 450px">
        <a class="fancybox" href="<?php echo $product->img; ?>"><img style="max-width: 450px; max-height: 450px" src="<?php echo $product->img; ?>"></a>
      </div>
    </div>
    <div class="helper"></div>
    <div style="margin: 20px 0 20px 20px; height: 450px; vertical-align: top; line-height: 1.8; width: 470px">
      <div style="text-align: right">
        <?php
        if (isset($url))
          echo CHtml::link('Назад', $url);
        ?>
      </div>
      <div class="cufon bold" style="font-size: 24pt; margin: 50px 0 20px"><?php echo $product->name; ?></div>
      <div>Артикул: <?php echo $product->article; ?></div>
      <div>Производитель: <?php echo $product->brand->name; ?></div>
      <div>Возраст: <?php echo $product->age . ' - ' . $product->age_to; ?></div>
      <div>Наличие: <?php echo $product->remainder ? 'товар в наличии на складе' : 'товар временно отсутствует'; ?></div>
      <!--<div><?php echo CHtml::link('ДОСТАВКА', $this->createUrl('delivery_payment')); ?> по новосибирску возможна завтра <?php echo $tomorrow; ?></div>-->
      <div class="cufon gray strike bold" style="font-size: 22pt; margin-top: 20px;
           display: inherit"><?php echo $old_price; ?></div>
      <div class="inline-blocks" style="position: relative">
        <div class="cufon bold red" style="font-size: 32pt"><?php echo number_format($price, 0, '.', ''); ?>.-</div>
        <div><?php
          echo CHtml::activeNumberField($productForm, 'quantity'
              , array(
            'style' => 'width: 2em; font-size: 12pt; margin: 0 5px 0 1em',
            'class' => 'input-number cart-quantity',
            'max' => 99,
            'min' => 0,
            'maxlength' => 2,
          ));
          ?>
          <span style="position: relative; bottom: -5px">шт.</span>
        </div>
        <div style="position: absolute; right: 0; bottom: 0">
          <div class="redbutton addToCart" product="<?php echo $product->id; ?>">
            <div class="left"></div>
            <div class="center">В корзину</div>
            <div class="right"></div>
          </div>
        </div>
      </div>
      <div>
        <div style="text-align: center; width: 168px; float: right; font-size: 11pt; margin-top: 5px">
          <!--<a id="buy-one-click" product="<?php echo $product->id; ?>" href="#">Купить в 1 клик</a>-->
        </div>
      </div>
    </div>
  </div>
  <div style="margin: 20px">
    <div class="cufon bold gray" style="font-size: 24pt">Описание</div>
    <div style="margin: 20px 0 40px"><?php echo $product->description; ?></div>
  </div>
  <?php $this->endWidget(); ?>
</div>
<?php $this->renderPartial('_footer', array('groups' => $groups)); ?>
<?php $this->renderPartial('_addProductModal'); ?>
<script>
  $(document).ready(function() {
    $(".fancybox").fancybox();
  });
</script>