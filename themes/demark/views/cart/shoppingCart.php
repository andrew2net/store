<?php
/* @var $cart Cart[] */
/* @var $customer_profile CustomerProfile */
/* @var $user User */
/* @var $profile Profile */
/* @var $order Order */
/* @var $delivery array */
/* @var $payment Payment */
/* @var $currency Currency */
/* @var $has_err string */
$this->pageTitle = Yii::app()->name . ' - Корзина';
?>
<div class="container" id="page">
  <?php
  $this->renderPartial('//site/_topblock');
  $this->renderPartial('//site/_mainmenu');

  $form = $this->beginWidget('CActiveForm', array(
    'id' => 'item-submit',
    'action' => $this->createUrl('') . "#prof",
  ));
  /* @var $form CActiveForm */

  echo CHtml::hiddenField('url', Yii::app()->request->url);
  echo CHtml::hiddenField('login', 0, array('id' => 'login-fl'));
  ?>
  <fieldset>
    <legend><span class="page-title bold blue">Ваша корзина</span></legend>
    <table class="striped" style="font-size: 11pt">
      <col/><col/><col/><col/><col/><col/><col/>
      <thead>
        <tr style="font-size: 12pt; background: #414FA5 !important; color: whitesmoke">
          <th colspan="2" style="width: 319px; text-align: center">товар</th><th style="width: 85px">артикул</th>
          <th id="price-label" class="text-right" style="width: 75px">цена <?php echo $currency->class; ?></th>
          <th style="width: 100px">кол-во</th><th class="text-right" style="width: 110px">сумма <?php echo $currency->class; ?></th><th></th>
        </tr>
      </thead>
      <tbody id="cart-items">
        <?php
        $this->renderPartial('_cartItems', array(
          'cart' => $cart,
          'customer_profile' => $customer_profile,
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
            'data-type-id' => $coupon['type'],
            'data-discount' => $coupon['value'],
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
  <fieldset>
    <legend><span class="page-title blue bold">Контактная информация</span></legend>
    <div class="inline-blocks">
      <div class="inline-blocks">
        <div style="vertical-align: top; width: 280px">
          <div style="margin-bottom: 1em"><span id="<?php echo $has_err; ?>"></span>
            <?php echo $form->labelEx($profile, 'first_name'); ?>
            <div><?php echo $form->textField($profile, 'first_name'); ?></div>
            <?php echo $form->error($profile, 'first_name', array('style' => 'font-size:10pt', 'class' => 'red')); ?>
          </div>
          <div style="margin-bottom: 1em"><span id="<?php echo $has_err; ?>"></span>
            <?php echo $form->labelEx($profile, 'last_name'); ?>
            <div><?php echo $form->textField($profile, 'last_name'); ?></div>
            <?php echo $form->error($profile, 'last_name', array('style' => 'font-size:10pt', 'class' => 'red')); ?>
          </div>
          <div style="margin-bottom: 1em">
            <?php echo $form->labelEx($user, 'email'); ?>
            <div><?php echo CHtml::activeEmailField($user, 'email'); ?></div>
            <?php echo $form->error($user, 'email', array('style' => 'font-size:10pt', 'class' => 'red')); ?>
          </div>
        </div>
        <div style="vertical-align: top; width: 280px; margin: 0 35px">
          <div style="margin-bottom: 1em">
            <?php echo $form->labelEx($customer_profile, 'phone'); ?>
            <div><?php echo $form->telField($customer_profile, 'phone'); ?></div>
            <?php echo $form->error($customer_profile, 'phone', array('style' => 'font-size:10pt', 'class' => 'red')); ?>
          </div>
          <div class="inline-blocks" style="margin-bottom: 1em">
            <div>
              <?php echo $form->labelEx($customer_profile, 'country_code'); ?>
              <div><?php echo $form->dropDownList($customer_profile, 'country_code', ProfileController::getCountries()); ?></div>
            </div>
            <div>
              <?php echo $form->labelEx($customer_profile, 'post_code'); ?>
              <div><?php echo $form->textField($customer_profile, 'post_code', array('style' => 'width:120px; margin-left:2px')); ?></div>
              <?php echo $form->error($customer_profile, 'post_code', array('style' => 'font-size:10pt', 'class' => 'red')); ?>
            </div>
          </div>
          <div style="margin-bottom: 1em">
            <?php echo $form->labelEx($customer_profile, 'city'); ?>
            <div>
              <?php
              $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'id' => 'cart-city',
                'model' => $customer_profile,
                'attribute' => 'city',
                'source' => new CJavaScriptExpression('function (request, response){citySuggest(request, response);}'),
              ));
              ?>
            </div>
            <?php echo $form->error($customer_profile, 'city', array('style' => 'font-size:10pt', 'class' => 'red')); ?>
          </div>
        </div>
        <div style="margin-bottom: 1em; display: block">
          <div style="margin-bottom: 1em">
            <?php echo $form->labelEx($customer_profile, 'address'); ?>
            <div><?php echo $form->textField($customer_profile, 'address', array('style' => 'width:548px')); ?></div>
            <?php echo $form->error($customer_profile, 'address', array('style' => 'font-size:10pt', 'class' => 'red')); ?>
          </div>
          <?php echo $form->labelEx($order, 'description'); ?>
          <div>
            <?php
            echo $form->textArea($order, 'description', array(
              'rows' => 4,
              'style' => 'width:558px'
            ));
            ?>
          </div>
        </div>
        <p class="gray" style="font-size: 10pt"><span class="red">*</span> - поля обязательные для заполнения</p>
      </div>
      <div style="vertical-align: top; width: 290px">
        <div class="bold gray">Оформление заказа</div>
        <div style="font-size: 10pt; margin-top: 10px; border: 1px dashed #DDD">
          <ol style="margin: 10px">
            <li>Выберите товар</li>
            <li>Обязательно укажите Ваше имя, электронную почту и почтовый адрес</li>
            <li>Выберите способ доставки товара (подробности о доставке, можно узнать в разделе <a href="/info/delivery">ДОСТАВКА</a>)</li>
            <li>После оформления заказа введите реквизиты банковской карточки на безопасной платежной странице <?php echo $customer_profile->price_country == 'KZ' ? 'Processing.kz' : 'LiqPay.com'; ?></li>
            <li>Подтвердите заказ и сохраните чек</li>
          </ol>
        </div>
      </div>
    </div>
  </fieldset>
  <fieldset>
    <legend><span class="page-title blue bold">Доставка и оплата</span></legend>
    <div class="inline-blocks">
      <div style="width: 440px; vertical-align: top; margin-right: 40px; min-height: 250px; position: relative">
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
        <div id="delivery-loading" class="loading" style="position: relative; display: none; top: 15px"></div>
        <div id="delivery-hint" class="red hintr" style="display: none">Укажите адрес доставки</div>
      </div>
      <div style="vertical-align: top">
        <?php
        $this->renderPartial('_payment', array(
          'order' => $order,
          'payment' => $payment,
          'customer_profile' => $customer_profile,
        ));
        ?>
      </div>
    </div>
  </fieldset>
  <div class="inline-blocks" style="margin: 10px 0 20px; position: relative">
    <div class="bold" style="font-size: 18pt; width: 600px; line-height: 50px">
      <span>общая сумма заказа: </span><span class="red"><span id="cart-total"></span><?php echo $currency->class; ?></span>
    </div>
    <div id="cart-submit" class="main-submit">
      <div>Оформить заказ</div>
    </div>
    <div id="order-hint" class="red hintl" style="display: none">Выберите способ доставки</div>
  </div>
  <?php $this->endWidget(); ?>
</div>
<div id="cart-login-dialog">
<!--    <div>Пользователь с адресом электройнной почты <span id="email-dialog" style="color: rgb(51, 153, 204)"></span> уже зарегистрирован на этом сайте.</div>
  <div style="margin: 1em 0 2em">Чтобы войти в личный кабинет, небходимо ввести пароль.</div>
  <?php // echo CHtml::label('Пароль', 'password'); ?>
  <?php // echo CHtml::passwordField('password'); ?>
  <?php // echo CHtml::Button('Вход', array('id' => 'submit-password')); ?>
  <span class="red" id="passw-err"></span>
  <div style="margin-top: 1em">
    Забыли пароль? <?php // echo CHtml::Button('Восстановить', array('id' => 'recover-password'));       ?>
    <img src="/images/process.gif" style="display: none; vertical-align: middle; margin-left: 15px" id="loading-dialog" />
  </div>
  <div id="sent-mail-recovery" style="height: 40px"></div>
  <div id="close-cart-dialog" class="blue" style="text-align: right; font-size: 9pt; margin-top: 1em; cursor: pointer">Закрыть окно</div>-->
</div>
<?php
$cart_css = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cart.css';
$url = Yii::app()->getAssetManager()->publish($cart_css);
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($url);
$cs->registerScriptFile('/js/shoppingCart.js', CClientScript::POS_END);
?>
<?php $this->renderPartial('//site/_footer'); ?>
