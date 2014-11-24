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
      'style' => 'display: table',
    ),
  ));
  ?>
</div>
<div>
  <?php if ($customer_profile->price_country == 'KZ') { ?>
    <div>
      <a target="_blank" href="http://processing.kz/ru/about/safety_systems" title="Узнать больше" style="text-decoration: none">
        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/vbv.gif">
        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/mcsc.gif">
      </a>
      <a target="_blank" href="http://www.processing.kz"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/pr.gif"></a>
      <a target="_blank" href="http://www.halykbank.kz"><img style="margin-left: 5px" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/hb.jpg"></a>
    </div>
    <a style="margin-left: 10px" target="_blank" href="http://processing.kz/ru/about/safety_systems" title="Узнать больше">learn more</a>
    <a style="margin-left: 20px" target="_blank" href="http://processing.kz/ru/about/safety_systems" title="Узнать больше">learn more</a>
    <?php
  }
  else {
    ?>
    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/vbv.gif" alt="Verified by VISA">
    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/mcsc.gif" alt="Master Card Secure Code">
  <?php } ?>
</div>