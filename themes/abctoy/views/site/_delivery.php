<?php

/* @var $order Order */
/* @var $delivery Delivery */
?>

<?php

echo CHtml::activeRadioButtonList($order, 'delivery_id'
    , $delivery, array(
  'labelOptions' => array(
    'style' => 'display: block',
    'class' => 'cart-radio',
)));
?>
<div class="bold" style="margin-top: 40px; font-size: 16pt">
  <span class="cufon">Стоимость доставки: </span>
  <span class="red" id="delivery-summ"></span></div>