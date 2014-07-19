<?php
/* @var $product Product */
?>
<div class="inline-blocks" style="margin-bottom: 20px">
  <div <?php echo (isset($group) ? 'style="width: 100%"' : ''); ?>>
    <div class="inline-blocks <?php echo (isset($group) ? 'right' : ''); ?>">
      <div class="icon-recom"></div>
      <div class="cufon green bold" style="font-size: 20pt; position: relative; padding: 0 10px">Вам рекомендовано</div>
    </div>
  </div>
  <?php
  if (!isset($group)) {
    ?>
    <div style="float: right; font-size: 12pt; position: relative; top: 20px;">
      <span class="cufon green bold">Товар подобран для вашего ребенка</span>
    </div>
    <?php
  }
  ?>
</div>
<div style="margin-bottom: 30px">
  <?php
  if (isset($group)) {
    $product->availableOnly()->subCategory($group->id)->discountOrder()->recommended();
    $products = $product->findAll(array('limit' => 12));
  }
  else
    $products = $product->availableOnly()->discountOrder()->recommended()->findAll(array('limit' => 15));
  foreach ($products as $value) {
    ?>
    <div style="float: left">
    <?php $this->renderPartial('_item', array('data' => $value)); ?>
    </div>
<?php } ?>
</div>