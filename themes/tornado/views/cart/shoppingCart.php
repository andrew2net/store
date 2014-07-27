<?php
/* @var $cart Cart[] */
/* @var $customer_profile CustomerProfile */
/* @var $order Order */
/* @var $delivery array */
/* @var $payment Payment */
/* @var $currency Currency */
/* @var $has_err string */
/* @var $price_type Price */

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
    <p><span class="red">*</span> - скидка по купону предоставляется только на товары без скидки</p>
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
          $this->renderPartial('_delivery', array(
            'order' => $order,
            'delivery' => $delivery,
            'currency' => $currency,
          ));
          ?>
        </div>
        <div id="delivery-hint" class="red" <?php echo ($delivery ? 'style="display: none"' : ''); ?>>Укажите страну и индекс</div>
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
    <div id="cart-submit" class="main-submit">
      <div>Оформить заказ</div>
      <?php // echo CHtml::button('', array('id' => 'cart-submit'));   ?>
    </div>
  </div>
  <div id="cart-login-dialog">
    <div>Пользователь с адресом электройнной почты <span id="email-dialog" style="color: rgb(51, 153, 204)"></span> уже зарегистрирован на этом сайте.</div>
    <div style="margin: 1em 0 2em">Чтобы войти в личный кабинет, небходимо ввести пароль.</div>
    <?php echo CHtml::label('Пароль', 'password'); ?>
    <?php echo CHtml::passwordField('password'); ?>
    <?php echo CHtml::Button('Вход', array('id' => 'submit-password')); ?>
    <span class="red" id="passw-err"></span>
    <div style="margin-top: 1em">
      Забыли пароль? <?php echo CHtml::Button('Восстановить', array('id' => 'recover-password')); ?>
      <img src="/images/process.gif" style="display: none; vertical-align: middle; margin-left: 15px" id="loading-dialog" />
    </div>
    <div id="sent-mail-recovery" style="height: 40px"></div>
    <div id="close-cart-dialog" class="blue" style="text-align: right; font-size: 9pt; margin-top: 1em; cursor: pointer">Закрыть окно</div>
  </div>
  <?php
  $this->endWidget();
  Yii::app()->getClientScript()->registerCoreScript('jquery.ui');
  Yii::app()->getClientScript()->registerScriptFile("/themes/tornado/js/shoppingCart.js", CClientScript::POS_END);
  ?>
</div>
