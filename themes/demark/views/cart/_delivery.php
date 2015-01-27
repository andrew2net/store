<?php

/* @var $this CartController */
/* @var $order Order */
/* @var $delivery array */
/* @var $currency Currency */
?>

<?php

if (is_array($delivery)) {
  echo CHtml::activeRadioButtonList($order, 'delivery_id'
    , $delivery, array(
    'labelOptions' => array(
      'style' => 'display: block',
  )));
} else {
  echo $delivery;
}
?>
