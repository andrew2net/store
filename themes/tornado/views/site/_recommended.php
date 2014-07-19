<?php
/* @var $group Category */
?>
<div>
  <?php
  $product = Product::model();
  if (isset($group)) {
    $product->availableOnly()->subCategory($group->id)->discountOrder()->recommended();
    $products = $product->findAll(array('limit' => 12));
  }
  else
    $products = $product->availableOnly()->discountOrder()->recommended()->findAll(array('limit' => 15));
  foreach ($products as $value) {
    ?>
    <div style="float: left">
      <?php $this->renderPartial('//site/_item_inline', array('data' => $value)); ?>
    </div>
  <?php } ?>
</div>