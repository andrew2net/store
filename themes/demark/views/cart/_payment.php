<?php
/* @var $order Order */
/* @var $payment Payment */
?>

<div style="width: 440px; vertical-align: top">
  <div class="bold gray" style="font-size: 12pt; margin-bottom: 20px">Способ оплаты</div>
  <?php
  echo CHtml::activeRadioButtonList($order, 'payment_id'
      , $payment, array(
    'labelOptions' => array(
      'style' => 'display: block',
    ),
  ));
  ?>
</div>
