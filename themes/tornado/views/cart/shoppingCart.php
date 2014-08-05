<?php
/* @var $cart Cart[] */
/* @var $customer_profile CustomerProfile */
/* @var $order Order */
/* @var $delivery array */
/* @var $payment array */
/* @var $currency Currency */
/* @var $has_err string */
/* @var $price_type Price */
/* @var $minsumm float */

$this->pageTitle = Yii::app()->name . ' - Корзина';
?>
<div class="container" id="page">
  <?php
  $this->breadcrumbs = array(
    'Корзина',
  );
  $this->widget('zii.widgets.CBreadcrumbs', array(
    'links' => $this->breadcrumbs,
  ));
  $form = $this->beginWidget('CActiveForm', array(
    'id' => 'item-submit',
    'action' => $this->createUrl('') . "#prof",
  ));
  /* @var $form CActiveForm */

  echo CHtml::hiddenField('url', Yii::app()->request->url);
  ?>
  <fieldset>
    <legend><span class="page-title bold blue">Ваша корзина</span></legend>
    <table class="striped" style="font-size: 11pt">
      <tr style="font-size: 12pt; background: #414FA5 !important; color: whitesmoke">
        <th colspan="2" style="width: 319px; text-align: center">товар</th><th style="width: 85px">артикул</th>
        <th id="price-label" class="text-right" style="width: 75px">
      <div id="price-header" title="Ваша цена &quot<?php echo $price_type->name; ?>&quot" style="display: inline-block; text-align: center">цена<?php echo $currency->class; ?><br>
        <span id="price-name" style="font-weight: normal; font-size: 8pt; text-transform: lowercase"><?php echo "($price_type->name)"; ?></span>
      </div>
      </th>
      <th style="width: 100px">кол-во</th><th class="text-right" style="width: 110px">сумма<?php echo $currency->class; ?></th><th></th>
      </tr>
      <tbody id="cart-items">
        <?php
        $this->renderPartial('_cartItems', array(
          'cart' => $cart,
          'customer_profile' => $customer_profile,
          'price_type' => $price_type,
        ));
        ?>
      </tbody>
      <tr style="font-size: 12pt; background: #414FA5; color: whitesmoke">
        <th class="text-right" colspan="5">общая сумма заказа:</th>
        <td class="text-right bold"><span id="cart-summ"></span></td>
        <th></th>
      </tr>
      <tr style="height: 37px">
        <th class="text-right" colspan="5">купон <span class="red">*</span> :</th>
        <th class="text-right">
          <?php
          echo CHtml::textField('coupon', $coupon['code'], array(
            'type_id' => $coupon['type'],
            'discount' => $coupon['value'],
            'maxlength' => 8,
            'style' => 'width:5em',
          ));
          ?>
        </th>
        <td><div id="discount-text" style="font-size: 9pt; text-align: center; width: 55px" class="red"></div></td>
      </tr>
      <tr>
        <th class="text-right" colspan="5">общая сумма скидок:</th>
        <td class="text-right bold"><span id="cart-discount"></span></td>
        <td></td>
      </tr>
    </table>
    <span class="right bold" style="font-size: 12pt; margin-right: 90px">Минимальная сумма заказа <span class="red"><?php echo number_format($minsumm, 0, '.', ' ') . $currency->class; ?></span></span>
    <span><span class="red">*</span> - скидка по купону предоставляется только на товары без скидки</span>
  </fieldset>
  <?php
  $this->renderPartial('//site/_contact_form', array(
    'profile' => $profile,
    'customer_profile' => $customer_profile,
    'user' => $user,
    'order' => $order,
    'form' => $form,
  ));
  ?>
  <fieldset>
    <legend><span class="page-title blue bold">Доставка и оплата</span></legend>
    <div class="inline-blocks">
      <div style="width: 450px; vertical-align: top; margin-right: 50px; height: 250px">
        <div class="bold gray" style="font-size: 12pt; margin-bottom: 20px">Способ доставки</div>
        <div id="cart-delivery">
          <?php
          if (isset($delivery))
            $this->renderPartial('_delivery', array(
              'order' => $order,
              'delivery' => $delivery,
              'currency' => $currency,
            ));
          ?>
        </div>
        <div id="delivery-loading" class="loading" style="position: relative; display: none; top: 15px"></div>
        <div id="delivery-hint" class="red" style="display: none">Укажите город или населенный пункт</div>
      </div>
      <div style="vertical-align: top">
        <?php
        $this->renderPartial('_payment', array(
          'order' => $order,
          'payment' => $payment
        ));
        ?>
      </div>
    </div>
  </fieldset>
  <div class="inline-blocks" style="margin: 10px 0 20px">
    <div class="bold" style="font-size: 18pt; width: 600px; vertical-align: bottom">
      <span>общая сумма заказа: </span><span class="red"><span id="cart-total"></span><?php echo $currency->class; ?></span>
    </div>
    <div id="cart-submit" class="main-submit" style="display: none">
      <div>Оформить заказ</div>
      <?php // echo CHtml::button('', array('id' => 'cart-submit'));   ?>
    </div>
  </div>
  <div id="cart-login-dialog">
  </div>
  <?php
  $this->endWidget();
  $cart_css = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cart.css';
  $url = Yii::app()->getAssetManager()->publish($cart_css);
  $cs = Yii::app()->getClientScript();
  $cs->registerCssFile($url);
  $cs->registerCoreScript('jquery.ui');
  $cs->registerScriptFile("/themes/tornado/js/shoppingCart.js", CClientScript::POS_END);
  ?>
</div>
<script type="text/javascript">
  var minsumm = <?php echo $minsumm ; ?>;
</script>
