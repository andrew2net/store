<?php
/* @var $product Cart */
/* @var $customer_profile CustomerProfile */
?>
<?php
switch ($customer_profile->price_country) {
  case 'KZ':
    $price = $product->product->price_tenge;
    break;
  default :
    $price = $product->product->price;
}
$discount = $product->product->getActualDiscount();
if ($discount) {
  $old_price = number_format($price, 0, '.', ' ');
  $disc = $price;
  $price = round($price * (1 - $discount / 100));
  $disc -= $price;
  $price_class = 'red';
}
else {
  $price = $price;
  $old_price = '';
  $disc = 0;
  $price_class = '';
}
$summ = $price * $product->quantity;
?>
<tr>
  <td>
    <!--<div class="img-container" style="width: 50px; height: 50px">-->
    <img style="max-width: 50px; max-height: 50px" src="<?php echo $product->product->small_img; ?>">
    <!--</div>-->
  </td>
  <td>
    <a class="item-link" href="<?php echo $this->createUrl('/product', array('id' => $product->product_id)); ?>">
      <?php echo $product->product->name; ?>
    </a>
  </td>
  <td><?php echo $product->product->article; ?></td>
  <!--<div>Производитель: <?php // echo $product->product->brand->name;    ?></div>-->
  <td class="text-right">
    <div style="text-decoration: line-through"><?php echo $old_price; ?></div>
    <div class="<?php echo $price_class; ?>"><?php echo number_format($price, 0, '.', ' '); ?></div>
  </td>
  <td style="margin: 0 40px"><?php
    echo CHtml::activeNumberField($product, "[$product->product_id]quantity", array(
//      'style' => 'width: 2em; font-size: 16pt; border:1px dashed #BBB;border-radius:3px',
      'class' => 'cart-quantity input-number',
      'data-price' => $price,
      'disc' => $disc,
      'product' => $product->product_id,
      'max' => 99,
      'min' => 0,
      'maxlength' => 2,
    ));
    ?>
    <span style="position: relative; top: 5px; font-size: 12pt"> шт.</span>
  </td>
  <td class="text-right summ bold">
    <?php echo number_format($summ, 0, '.', ' '); ?>
  </td>
  <td>
    <span class="cart-item-del" product="<?php echo $product->product_id ?>">Удалить</span>
    <img style="display: none; margin-left: 10px" src="/images/load.gif">
  </td>
</tr>