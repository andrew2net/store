<?php

/* @var $cart Cart[] */
/* @var $customer_profile CustomerProfile */

if (count($cart) > 0) {
  foreach ($cart as $product) {
    echo $this->renderPartial('_cartItem', array(
      'product' => $product,
      'customer_profile' => $customer_profile));
  }
}
else {
  ?>
  <tr><td colspan="7" class="red bold" style="font-size: 18pt; text-align: center; height: 40px">корзина пуста</td></tr>
<?php } ?>
