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
//      'class' => 'delivery-radio',
  )));
//  if ($delivery) {
  ?>
  <!--    <div class="bold" style="margin-top: 20px; font-size: 16pt">
        стоимость доставки: <span class="red"><span id="delivery-summ"></span><?php // echo $currency->class;  ?></span>
      </div>-->
  <?php
//  }
}
else {
  echo $delivery;
}
?>
