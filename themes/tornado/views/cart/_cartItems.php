<?php
/* @var $cart Cart[] */
/* @var $customer_profile CustomerProfile */
/* @var $price_type Price */

if (count($cart) > 0) {
  foreach ($cart as $product) {
    echo $this->renderPartial('_cartItem', array(
      'product' => $product,
      'customer_profile' => $customer_profile,
      'price_type' => $price_type,
      ));
  }
}
else {
  ?>
  <tr><td colspan="7" class="red bold" style="font-size: 18pt; text-align: center; height: 40px">корзина пуста</td></tr>
<?php } ?>
