<?php
/* @var $order Order */
/* @var $payment Payment */
?>

<div style="width: 445px; vertical-align: top">
  <div class="cufon bold gray" style="font-size: 12pt; margin: 20px 0">Способ оплаты</div>
  <?php
  echo CHtml::activeRadioButtonList($order, 'payment_id'
      , $payment, array(
    'labelOptions' => array(
      'style' => 'display: block',
      'class' => 'cart-radio',
    ),
  ));
  ?>
</div>
