<?php
/* @var $cart Cart[] */
/* @var $profile CustomerProfile */
/* @var $order Order */
/* @var $delivery array */
/* @var $payment Payment */
/* @var $has_err string */
$this->pageTitle = Yii::app()->name . ' - Корзина';
?>
<div class="container" id="page" style="margin-top: 0">
  <?php $this->renderPartial('_shoppingCartTopBlock', array('profile' => $profile)); ?>
  <div class="cufon gray bold" style="font-size: 28pt; margin: 20px 0">Ваша корзина</div>
  <?php
  $form = $this->beginWidget('CActiveForm', array(
    'id' => 'item-submit',
    'action' => $this->createUrl('') . "#prof",
  ));
  ?>
  <div id="cart-items">
    <?php $this->renderPartial('_cartItems', array('cart' => $cart)); ?>
  </div>
  <div style="font-size: 18pt; margin: 30px 0 10px">
    <span class="cufon">КУПОН </span><?php
    echo CHtml::textField('coupon', $coupon['code'], array(
      'type_id' => $coupon['type'],
      'discount' => $coupon['value'],
      'maxlength' => 8,
      'style' => 'width:5em; padding: 0 7px 0 5px; font-size: 16pt; border:1px dashed #BBB;border-radius:3px',
    ));
    ?>
    <span id="discount-text" style="font-size: small"></span>
  </div>
  <div class="bold" style="font-size: 14pt">
    <span class="cufon">Сумма скидки </span><span id="cart-discount" class="cufon"></span>
  </div>
  <div class="bold" style="font-size: 26pt">
    <span class="cufon gray">Общая сумма заказа: </span>
    <span id="cart-summ" class="cufon red"></span>
  </div>
  <div class="bold" style="font-size: 14pt">
    <span class="cufon red">*</span>
    <span class="cufon"> Минимальная сумма заказа </span>
    <span class="cufon red">700р.</span>
  </div>
  <div class="cufon gray bold" style="font-size: 26pt; text-align: center; margin: 40px 0">Контактная информация</div>
  <div class="inline-blocks" style="font-size: 12pt">
    <div style="vertical-align: top; width: 290px">
      <div><span id="<?php echo $has_err; ?>"></span>
        <?php
        echo CHtml::label('Ваше имя и фамилия<span class="red">*</span>'
            , 'CustomerProfile_fio', array(
          'class' => 'cufon gray bold',
        ));
        ?>
      </div>
      <div style="margin-bottom: 1em"><?php
        echo $form->textField($profile, 'fio'
            , array('class' => 'input-text'));
        ?>
        <?php echo CHtml::error($profile, 'fio', array('style' => 'font-size:10pt', 'class' => 'red')); ?>
      </div>
      <div><?php
        echo CHtml::label('E-mail<span class="red">*</span>', 'CustomerProfile_email'
            , array(
          'class' => 'cufon gray bold',
        ));
        ?>
      </div>
      <div style="margin-bottom: 1em"><?php
        echo CHtml::activeEmailField($profile, 'email'
            , array('class' => 'input-text'));
        ?>
        <?php echo CHtml::error($profile, 'email', array('style' => 'font-size:10pt', 'class' => 'red')); ?>
      </div>
      <div><?php
        echo CHtml::label('Телефон<span class="red">*</span>', 'CustomerProfile_phone'
            , array(
          'class' => 'cufon gray bold',
        ));
        ?>
      </div>
      <div style="margin-bottom: 1em"><?php
        echo CHtml::activeTelField($profile, 'phone'
            , array('class' => 'input-text'));
        ?>
        <?php echo CHtml::error($profile, 'phone', array('style' => 'font-size:10pt', 'class' => 'red')); ?>
      </div>
      <div><?php
        echo CHtml::label('Удобное время звонка<span class="red">**</span>', 'call_time_id'
            , array(
          'class' => 'cufon gray bold',
        ));
        ?>
      </div>
      <div class="styled-select" style="margin-top: 1em"><?php
        echo CHtml::activeDropDownList($order, 'call_time_id'
            , $order->callTimes, array('style' => 'font-size: 12pt'));
        ?></div>
    </div>
    <div style="vertical-align: top; width: 290px; margin: 0 35px">
      <div><?php
        echo CHtml::label('Город доставки<span class="red">*</span>', 'CustomerProfile_city'
            , array(
          'class' => 'cufon gray bold',
        ));
        ?>
      </div>
      <div style="margin-bottom: 1em"><?php
        $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
          'id' => 'cart-city',
          'model' => $profile,
          'attribute' => 'city',
          'sourceUrl' => '/site/suggestcity',
          'htmlOptions' => array('class' => 'input-text'),
        ));
        ?>
        <?php echo CHtml::error($profile, 'city', array('style' => 'font-size:10pt', 'class' => 'red')); ?>
      </div>
      <div><?php
        echo CHtml::label('Адрес доставки', 'CustomerProfile_address'
            , array(
          'class' => 'cufon gray bold',
        ));
        ?>
      </div>
      <div style="margin-bottom: 1em"><?php
        echo CHtml::activeTextField($profile, 'address'
            , array('class' => 'input-text'));
        ?></div>
      <div><?php
        echo CHtml::label('Комментарий', 'description'
            , array(
          'class' => 'cufon gray bold',
        ));
        ?>
      </div>
      <div style="margin-bottom: 1em"><?php
        echo CHtml::activeTextArea($order, 'description'
            , array(
          'class' => 'input-text',
          'cols' => 25,
          'rows' => 4,
        ));
        ?>
      </div>
      <div style="font-size: 10pt">
        <div class="cufon bold gray"><span class="red">*</span> Поля обязательные для заполнения</div>
        <div class="cufon bold gray"><span class="red">**</span> Удобное время для того, чтобы <br>мы Вам перезвонили для уточнения <br>данные о доставке</div>
      </div>
    </div>
    <div style="vertical-align: top; width: 290px">
      <div class="cufon bold gray">Информация о доставке</div>
      <div style="font-size: 10pt; width: 195px; margin-top: 10px; border: 1px dashed #DDD">
        <div style="padding: 10px">Введите Ваш город, для что бы узнать стоимость доставки.</div>
        <div style="padding: 0 10px 10px">Если вашего города нет в базе, стоимость доставки сообщит менеджер во время звонка.
          Минимальную сумму доставки и подробности о БЕСПЛАТНОЙ ДОСТАВКЕ, можно узнать в разделе <a href="/deliver">ДОСТАВКА</a></div>
      </div>
    </div>
  </div>
  <div class="inline-blocks" style="margin-top: 20px">
    <div style="width: 450px; vertical-align: top; margin-right: 50px">
      <div class="cufon bold gray" style="font-size: 12pt; margin: 20px 0">Способ доставки</div>
      <div id="cart-delivery">
        <?php
        $this->renderPartial('_delivery', array(
          'order' => $order,
          'delivery' => $delivery
        ));
        ?>
      </div>
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
  <div class="bold" style="font-size: 26pt; text-align: center ;margin-top: 40px">
    <span class="cufon">Общая сумма заказа: </span><span id="cart-total" class="red"></span>
  </div>
  <div style="margin: 40px 0; text-align: center">
    <?php echo CHtml::button('', array('id' => 'cart-submit')); ?>
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
  <?php $this->endWidget(); ?>
</div>
<script type="text/javascript">
  function calcSumm() {
    var cartSumm = 0;
    var cartSummNoDisc = 0;
    var discountSumm = 0;
    $('.cart-quantity').each(function() {
      var price = $(this).attr('price');
      var quantity = parseInt(this.value);
      if (isNaN(quantity))
        quantity = 0;
      cartSumm += quantity * price;
      var disc = $(this).attr('disc');
      if (disc > 0)
        discountSumm += disc * quantity;
      else
        cartSummNoDisc += quantity * price;
    });
    if (isNaN(cartSumm))
      cartSumm = 0;

    var discountType = $('#coupon').attr('type_id');
    if (discountType !== undefined && discountType.length > 0) {
      if (cartSummNoDisc === 0)
        $('#discount-text').html('Скидка по купону распространяется только на товары без скидки.');
      else {
        var discountText = 'Скидка ';
        var discountDisc = $('#coupon').attr('discount');
        var discNum = parseFloat(discountDisc);
        switch (discountType) {
          case '0':
            if (discountSumm > discNum)
              $('#discount-text').html('Общая сумма скидки больше скидки по купону. Купон не используется.');
            else if (cartSumm < 1800) {
              $('#discount-text').html('Сумма закза должна быть не менее 1800 рублей. Купон не используется.');
            } else {
              var couponDisc = cartSummNoDisc > discNum ? discNum : cartSummNoDisc;
              discountSumm += couponDisc;
              cartSumm -= couponDisc;
              discountText += discountDisc + ' руб.';
              $('#discount-text').html(discountText);
            }
            break;
          case '1':
            var couponDisc = cartSummNoDisc * discNum / 100;
            discountSumm += couponDisc;
            cartSumm -= couponDisc;
            discountText += discountDisc + ' %';
            $('#discount-text').html(discountText);
            break;
        }
      }
    }

    $('.delivery-data').each(function() {
      var orderSumm = $(this).attr('summ');
      if (cartSumm < orderSumm) {
        var deliveryPrice = parseFloat($(this).attr('price'));
        $(this).nextAll('.delivery-price').html(deliveryPrice.toFixed(2) + ' руб.');
      } else if (orderSumm > 0) {
        $(this).nextAll('.delivery-price').html('бесплатно');
      }
    });

    $('#cart-summ').html(cartSumm.formatMoney());
    $('#cart-summ').attr('summ', cartSumm);
    $('#cart-discount').html(discountSumm.formatMoney());
    $('#cart-discount').attr('summ', discountSumm);

    var deliveryOrderSumm = $('input:checked + .cart-radio > span').attr('summ');
    var priceDelivery = 0;
    if (deliveryOrderSumm == 0 || cartSumm < deliveryOrderSumm)
      priceDelivery = parseFloat($('input:checked + .cart-radio > span').attr('price'));
    if (!isNaN(priceDelivery)) {
      var price_f = priceDelivery.formatMoney();
      $('#delivery-summ').html(price_f);
      $('#cart-total').html((priceDelivery + cartSumm).formatMoney());
    }
    else {
      $('#delivery-summ').html('');
      $('#cart-total').html(cartSumm.formatMoney());
    }
    Cufon.replace('#cart-summ, #cart-discount, #delivery-summ, #cart-total');
  }
  calcSumm();

  $('#cart-submit').click(function() {
    var email = $('#CustomerProfile_email').val();
    $.post('/checkemail', {
      email: email
    }, function(data) {
      if (data == 'ok')
        $('form').submit();
      else {
        $('#email-dialog').html(email);
        $('#cart-login-dialog').dialog('open');
      }
    });
  });

  $('#submit-password').click(function() {
    var email = $('#CustomerProfile_email').val();
    var passw = $('#password').val();
    $.post('/login', {
      email: email,
      passw: passw
    }, function(data) {
      if (data == 'ok')
        $('form').submit();
      else
        $('#passw-err').html('Неверный пароль');
    });
  });

  $('#recover-password').click(function() {
    var email = $('#CustomerProfile_email').val();
    $('#sent-mail-recovery').html('');
    $(this).css('display', 'none');
    $('#loading-dialog').css('display', 'inline');
    $.post('/user/recovery/passwrecover', {
      email: email
    }, function(data) {
      if (data == 'ok')
        $('#sent-mail-recovery').html('Инструкции для восстановления пароля высланы на Email ' + email);
      $('#loading-dialog').css('display', 'none');
      $('#recover-password').css('display', 'inline');
    });
  });

  $('#cart-city').on('autocompleteselect', function(event, elem) {
    getDeliveries(elem.item.value);
  });
  $('#cart-city').typing({
    start: function(event, elem) {
    },
    stop: function(event, elem) {
      getDeliveries(elem.val());
    },
    delay: 2000
  });

  function getDeliveries(city) {
    $.get('/delivery', {
      'city': city
    }, function(data) {
      $('#cart-delivery').html(data);
      calcSumm();
    });
  }

  $('#coupon').typing({
    start: function(event, elem) {
    },
    stop: function(event, elem) {
      getCoupon(elem);
    },
    delay: 2000
  });

  $('#coupon').focusout(function() {
    getCoupon($(this));
  });

  function getCoupon(elem) {
    var code = $.trim(elem.val());
    var err = 'Неверный код купона';
    elem.attr('type_id', '');
    elem.attr('discount', '');
    if (code.length === 6) {
      $.get('/coupon', {
        coupon: code
      }, function(data) {
        var discount = JSON && JSON.parse(data) || $.parseJSON(data);
        var coupon = $('#coupon');
        if (discount.type === 3) {
          $('#discount-text').html(err);
          coupon.attr('type_id', '');
          coupon.attr('discount', '');
        } else {
          coupon.attr('type_id', discount.type);
          coupon.attr('discount', discount.discount);
          calcSumm();
        }
      });
    } else if (code.length > 0)
      $('#discount-text').html(err);
    else
      $('#discount-text').html('');
    calcSumm();
  }

  $('#cart-delivery').on('change', 'input[name="Order[delivery_id]"]', function() {
    calcSumm();
  });

  $(document).on('change', '.cart-quantity', function() {
    var id = $(this).attr('product');
    var quantity = parseInt(this.value);
    if (isNaN(quantity))
      quantity = 0;
    if (quantity < 0) {
      quantity = 0;
      this.value = quantity;
    } else if (quantity > 9) {
      quantity = 9;
    }
    calcSumm();
    $.post('/changeCart', {
      'id': id,
      'quantity': quantity
    });
  });
  $(document).on('click', '.cart-item-del', function() {
    var id = $(this).attr('product');
    $.post('/delitemcart', {
      'id': id,
    }, function(data) {
      $('#cart-items').html(data);
      calcSumm();
      Cufon.replace('#cart-items .cufon');
    }
    );
  });

  var cartQuantity;
  $(document).on('keyup', '.cart-quantity', function(event) {
    if (this.value.length > 0) {
      var value = parseInt(this.value);
      if (value > 99)
        this.value = cartQuantity;
      else
        this.value = value;
    }
    else
      this.value = 0;
    calcSumm();
  });

  $(document).on('keydown', '.cart-quantity', function(event) {
    cartQuantity = this.value;
  });

  $(function() {
    $("#cart-login-dialog").dialog({
      autoOpen: false,
      modal: true,
      draggable: false,
      resizable: false,
      width: 500,
      dialogClass: "cart-login-alert",
      show: {
        effect: "blind",
        duration: 500
      },
      hide: {
        effect: "explode",
        duration: 500
      }
    });
  });

  $('#close-cart-dialog').click(function() {
    $("#cart-login-dialog").dialog('close');
  });
</script>
