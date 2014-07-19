<?php
/* @var $cart Cart[] */
?>
<?php
if (count($cart) > 0) {
  echo CHtml::hiddenField('url', Yii::app()->request->url);
  $fl = FALSE;
  foreach ($cart as $product) {
    if ($fl) {
      ?>
      <div style="border-bottom: 1px solid #DDD; width: 750px"></div>
      <?php
    }
    $fl = TRUE;
    $discount = $product->product->getActualDiscount();
    echo $this->renderPartial('_cartItem', array(
      'product' => $product,
      'discount' => $discount));
  }
}
else {
  ?>
  <div class="cufon red bold" style="font-size: 26pt; margin: 20px; text-align: center">Корзина не заполнена</div>

<?php } ?>
