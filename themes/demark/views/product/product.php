<?php
/* @var $product Product */
/* @var $productForm ProductForm */
/* @var $search Search */

$txtName = html_entity_decode($product->name, ENT_COMPAT, 'UTF-8');
?>
<?php $this->pageTitle = Yii::app()->name . ' - ' . $txtName; ?>
<?php // $this->renderPartial('_topmenu');            ?>

<div class="container" id="page">
  <?php
  $this->renderPartial('//site/_topblock', array('search' => $search));
  $this->renderPartial('//site/_mainmenu');
  $categories = $product->category;
  if (isset($categories[0])) {
    $breadcrumbs = array();
    $breadcrumbs[] = $txtName;
    $category = $categories[0];
    while (!$category->isRoot()) {
      $breadcrumbs[$category->name] = array('/group', 'id' => $category->id);
      $category = $category->getParent();
    }
    $breadcrumbs[$category->name] = array('/group', 'id' => $category->id);
    $breadcrumbs = array_reverse($breadcrumbs);
    $this->widget('zii.widgets.CBreadcrumbs', array(
      'links' => $breadcrumbs,
      'homeLink' => FALSE,
      'separator' => ' > ',
      'htmlOptions' => array(
        'class' => 'breadcrumbs',
      )
    ));
  }
  $profile = ProfileController::getProfile();
  switch ($profile->price_country) {
    case 'KZ':
      $price = $product->price_tenge;
      $currecy = '<span class="tenge">&#8376;</span>';
      break;
    default :
      $price = $product->price;
      $currecy = '<span class="ruble">P</span>';
  }
  $discount = $product->getActualDiscount();
  if ($discount) {
    $percent = '-' . $discount . '%';
    $old_price = '<span>' . number_format($price, 0, '.', ' ') . '</span>' . $currecy;
    $price = number_format(round($price * (1 - $discount / 100)), 0, '.', ' ');
  }
  else {
    $percent = '';
    $price = number_format($price, 0, '.', ' ');
    $old_price = '';
  }
  $form = $this->beginWidget('CActiveForm');
  ?>
  <div class="inline-blocks">
    <div style="position: relative">
      <div class="<?php echo empty($percent) ? '' : 'discount-label-big'; ?>"><?php echo $percent; ?></div>
      <div class="img-container" style="width: 450px; height: 450px">
        <a class="fancybox" href="<?php echo $product->img; ?>"><img class="img-anim" style="max-width: 450px; max-height: 450px" src="<?php echo $product->img; ?>"></a>
      </div>
    </div>
    <div class="helper"></div>
    <div style="margin: 20px 0 0 20px; vertical-align: top; line-height: 1.8; width: 470px">
      <div style="text-align: right">
        <?php
        if (isset($url))
          echo CHtml::link('Назад', $url);
        ?>
      </div>
      <div class="bold" style="font-size: 18pt; margin: 15px 0 20px"><?php echo $product->name; ?></div>
      <div>Артикул: <?php echo $product->article; ?></div>
      <div>Производитель: <?php echo $product->brand->name; ?></div>
      <div>Наличие: <?php echo $product->remainder ? 'товар в наличии на складе' : 'товар временно отсутствует'; ?></div>
      <div class="item-disc red" style="font-size: 18pt !important; display: inherit"><?php echo $old_price; ?></div>
      <div class="inline-blocks" style="position: relative">
        <div class="item-price blue" style="position: relative; bottom: 3px; font-size: 32pt"><?php echo $price . $currecy; ?></div>
        <div style="position: relative; bottom: 28px; vertical-align: bottom"><?php
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
        <div class="item-bt addToCart" style="cursor: pointer; position: relative; top: 20px" product="<?php echo $product->id; ?>"><div>В корзину</div></div>
      </div>
      <!--<div>-->
        <!--<div style="text-align: center; width: 168px; float: right; font-size: 11pt; margin-top: 5px">-->
          <!--<a id="buy-one-click" product="<?php // echo $product->id;       ?>" href="#">Купить в 1 клик</a>-->
        <!--</div>-->
      <!--</div>-->
    </div>
  </div>
  <div style="margin-bottom: 10px">
    <?php
    $this->widget('CTabView', array(
      'tabs' => array(
        'description' => array(
          'title' => 'Описание',
          'content' => $product->description,
          'htmlOptions' => array('style' => 'margin-left: 5px')
        ),
        array(
          'title' => 'Характеристики',
          'view' => '_feature',
          'data' => array('product' => $product), TRUE),
      ),
      'cssFile' => '/themes/demark/css/tabs.css',
        )
    );
    ?>
  </div>
  <?php $this->endWidget(); ?>
</div>
<?php $this->renderPartial('//site/_footer'); ?>
<script>
  $(document).ready(function() {
    $(".fancybox").fancybox();
  });
</script>