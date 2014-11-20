<?php
/* @var $order Order */
/* @var $payment array */
?>

<div style="width: 445px; vertical-align: top">
  <div class="bold gray" style="font-size: 12pt; margin-bottom: 20px">Способ оплаты</div>
  <?php
  echo CHtml::activeRadioButtonList($order, 'payment_id'
      , $payment, array(
    'labelOptions' => array(
      'style' => 'display: table',
    ),
  ));
  ?>
</div>
