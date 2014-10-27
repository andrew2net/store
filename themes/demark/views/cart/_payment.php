<?php
/* @var $order Order */
/* @var $payment Payment */
/* @var $customer_profile CustomerProfile */
?>

<div style="width: 440px; vertical-align: top;min-height: 160px">
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
<div>
  <?php if ($customer_profile->price_country == 'KZ') { ?>
  <a href="http://processing.kz/ru/about/safety_systems" title="Узнать больше" style="text-decoration: none">
      <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/vbv.gif">
      <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/mcsc.gif">
    </a>
    <a href="http://www.processing.kz"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/pr.gif"></a>
    <a href="http://www.halykbank.kz"><img style="margin-left: 5px" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/hb.jpg"></a>
  <?php
  }
  else {
    ?>
    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/vbv.gif">
    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/mcsc.gif">
<?php } ?>
</div>