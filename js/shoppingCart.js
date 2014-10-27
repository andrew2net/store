var country_code = $('#CustomerProfile_country_code');
$(document).ready(function () {
  var cartSumm = $('#cart-summ');
  var coupon = $('#coupon');
  var discountText = $('#discount-text');
  var cartDiscount = $('#cart-discount');
  var cartSubmit = $('#cart-submit');
  var cart_items = $('#cart-items');
  var post_code = $('#CustomerProfile_post_code');
  var cart_city = $('#cart-city');
  var delivery_hint = $('#delivery-hint');
  var delivery_loading = $('#delivery-loading');
  var cart_delivery = $('#cart-delivery');
  var cart_login_dialog = $("#cart-login-dialog");
  var user_email = $('#User_email');
  var order_hint = $("#order-hint");

  function calcCartSumm() {
    var summ = 0;
    var summNoDisc = 0;
    var discountSumm = 0;
    $('.cart-quantity').each(function () {
      var price = $(this).attr('price');
      var quantity = parseInt(this.value);
      if (isNaN(quantity))
        quantity = 0;
      summ += quantity * price;
      var disc = $(this).attr('disc');
      if (disc > 0)
        discountSumm += disc * quantity;
      else
        summNoDisc += quantity * price;
    });
    if (isNaN(summ))
      summ = 0;

    var discountType = coupon.attr('type_id');
    if (summNoDisc === 0) {
      coupon.prop({disabled: true});
      discountText.html('');
    } else {
      coupon.prop('disabled', false);
      if (discountType !== undefined && discountType.length > 0) {
        var text = 'скидка ';
        var discountDisc = coupon.attr('discount');
        var discNum = parseFloat(discountDisc);
        switch (discountType) {
          case '0':
            var couponDisc = summNoDisc > discNum ? discNum : summNoDisc;
            discountSumm += couponDisc;
            summ -= couponDisc;
            var currency = $('#price-label > span').clone();
            text += discountDisc + ' ';//<span class="ruble">Р</span>';
            discountText.html(text).append(currency);
            break;
          case '1':
            var couponDisc = summNoDisc * discNum / 100;
            discountSumm += couponDisc;
            summ -= couponDisc;
            text += discountDisc + '%';
            discountText.html(text);
            break;
        }
      }
    }

    cartSumm.html(summ.formatMoney());
    cartSumm.attr('summ', summ);
    cartDiscount.html(discountSumm.formatMoney());
    cartDiscount.attr('summ', discountSumm);
  }

  function calcTotal() {
    var priceDelivery = parseFloat($('#cart-delivery input:checked + label > span').attr('price'));
    var summ = parseFloat(cartSumm.attr('summ'));
    if (!isNaN(priceDelivery)) {
      var price_f = priceDelivery.formatMoney();
      $('#delivery-summ').html(price_f);
      $('#cart-total').html((priceDelivery + summ).formatMoney());
      order_hint.hide();
      cartSubmit.show();
    }
    else {
      $('#delivery-summ').html('');
      cartSubmit.hide();
      $('#cart-total').html(summ.formatMoney());
      if ($('#cart-delivery input').length > 0)
        order_hint.show();
      else
        order_hint.hide();
    }
  }

  calcCartSumm();
  getDeliveries();

  cartSubmit.click(function () {
    var email = user_email.val();
    $.post('/cart/checkemail', {
      email: email
    }, function (data) {
      if (data == 'ok')
        $('form').submit();
      else {
        $('#email-dialog').html(email);
        cart_login_dialog.dialog('open');
      }
    });
  });

  cart_login_dialog.on('click', '#submit-password', function () {
    var email = user_email.val();
    var passw = $('#password').val();
    $.post('/login', {
      email: email,
      passw: passw
    }, function (data) {
      if (data == 'ok')
        $('form').submit();
      else
        $('#passw-err').html('Неверный пароль');
    });
  });

  cart_login_dialog.on('click', '#recover-password', function () {
    var email = user_email.val();
    $('#sent-mail-recovery').html('');
    $(this).hide();
    $('#loading-dialog').show();
    $.post('/user/recovery/passwrecover', {
      email: email
    }, function (data) {
      if (data == 'ok')
        $('#sent-mail-recovery').html('Инструкции для восстановления пароля высланы на Email ' + email);
      $('#loading-dialog').hide();
      $('#recover-password').show();
    });
  });

  var deliveryTimeOut;
  var city_val;
  cart_city.keyup(function (event) {
    if (city_val == this.value) // || event.keyCode == 8 || event.keyCode > 36 && event.keyCode < 41)
      return false;
    var c_val = this.value;
    deliveryTimeOut = setTimeout(function () {
      city_val = c_val;
      getDeliveries();
    }, 1000);
  });

  cart_city.keydown(function () {
    clearTimeout(deliveryTimeOut);
  });

  cart_city.focusin(function () {
    city_val = this.value;
  });

  cart_city.focusout(function () {
    clearTimeout(deliveryTimeOut);
    if (city_val != this.value){
      city_val = this.value;
      getDeliveries();
    }
  });

  cart_city.on('autocompleteselect', function (event, elem) {
    clearTimeout(deliveryTimeOut);
    getDeliveries();
  });

  country_code.change(function () {
    getDeliveries();
  });

  var post_code_val;
  post_code.keyup(function (event) {
    if (post_code_val == this.value) // || event.keyCode == 8 || event.keyCode > 36 && event.keyCode < 41)
      return false;
    var pcval = this.value;
    deliveryTimeOut = setTimeout(function () {
      post_code_val = pcval;
      getDeliveries();
    }, 1000);
  });

  post_code.keydown(function () {
    clearTimeout(deliveryTimeOut);
  });

  post_code.focusin(function () {
    post_code_val = this.value;
  });
  post_code.focusout(function (event) {
    clearTimeout(deliveryTimeOut);
    if (post_code_val != this.value) {
      post_code_val = this.value;
      getDeliveries();
    }
  });

  function getDeliveries() {
    cartSubmit.hide();
    var pcode = post_code.val();
    if (pcode.length === 6) {
      var ccode = country_code.val();
      cart_delivery.hide();
      delivery_hint.hide();
      delivery_loading.show();
      var city = cart_city.val();
      var delivery = $('input:radio[name="Order[delivery_id]"]:checked');
      var d_id = 0;
      if (delivery.length > 0)
        d_id = delivery.val();
      var id = delivery.attr('id');
      var c_deliver = delivery.siblings('label[for="' + id + '"]').find('input[type="text"]').val();
      if (c_deliver == undefined)
        c_deliver = '';
      $.get('/cart/delivery', {
        'ccode': ccode,
        'pcode': pcode,
        'city': city,
        'delivery_id': d_id,
        'c_deliver': c_deliver
      }, function (data) {
        delivery_loading.hide();
        cart_delivery.html(data);
        cart_delivery.show();
        calcTotal();
      });
    } else {
      delivery_loading.hide();
      cart_delivery.hide();
      cart_delivery.html('');
      delivery_hint.show();
      calcTotal();
    }
  }

  coupon.typing({
    start: function (event, elem) {
    },
    stop: function (event, elem) {
      getCoupon(elem);
    },
    delay: 2000
  });

  coupon.focusout(function () {
    getCoupon($(this));
  });

  function getCoupon(elem) {
    var code = $.trim(elem.val());
    var err = 'неверный код';
    elem.attr('type_id', '');
    elem.attr('discount', '');
    if (code.length === 6) {
      $.get('/cart/coupon', {
        coupon: code
      }, function (data) {
        var discount = JSON && JSON.parse(data) || $.parseJSON(data);
        if (discount.type === 3) {
          $('#discount-text').html(err);
          coupon.attr('type_id', '');
          coupon.attr('discount', '');
        } else {
          coupon.attr('type_id', discount.type);
          coupon.attr('discount', discount.discount);
          calcCartSumm();
        }
      });
    } else if (code.length > 0)
      discountText.html(err);
    else
      discountText.html('');
    calcCartSumm();
  }

  cart_delivery.on('change', 'input[name="Order[delivery_id]"]', function () {
    calcTotal();
  });

  var quantityTimeOut;
  $(document).on('change', '.cart-quantity', function () {
    cartSubmit.hide();
    clearTimeout(quantityTimeOut);
    var id = $(this).attr('product');
    var quantity = parseInt(this.value);
    if (isNaN(quantity))
      quantity = 0;
    if (quantity < 0) {
      quantity = 0;
      this.value = quantity;
    } else if (quantity > 99) {
      quantity = 99;
    }
    calcRow(this);
    calcCartSumm();
    changeCart(id, quantity);
  });

  var cartQuantity;
  $(document).on('keyup', '.cart-quantity', function (event) {
    clearTimeout(quantityTimeOut);
    cartSubmit.hide();
    var id = $(this).attr('product');
    if (this.value.length > 0) {
      var value = parseInt(this.value);
      if (value > 99)
        this.value = cartQuantity;
      else
        this.value = value;
    }
    else
      this.value = 0;
    calcRow(this);
    calcCartSumm();
    quantityTimeOut = setTimeout(function () {
      changeCart(id, value);
    }, 1000);
  });

  $(document).on('keydown', '.cart-quantity', function (event) {
    clearTimeout(quantityTimeOut);
    cartQuantity = this.value;
  });

  var cartTimeout;
  function changeCart(id, quantity) {
    $.post('/cart/changeCart', {
      'id': id,
      'quantity': quantity
    }, function (data) {
//    if (data) {
//      refreshCart(data);
//    }
      cart_delivery.hide();
      delivery_hint.hide();
//    cart_delivery.val('');
      var city = cart_city.val();
      if (city.length > 0) {
        delivery_loading.show();
        clearTimeout(cartTimeout);
        cartTimeout = setTimeout(function () {
          getDeliveries();
        }, 3000);
      }
    });
  }

  $(document).on('click', '.cart-item-del', function () {
    var id = $(this).attr('product');
    $.post('/cart/delitem', {
      'id': id
    }, function (data) {
      var result = $.parseJSON(data);
      cart_items.html(result.html);
      calcCartSumm();
      getDeliveries();
    }
    );
  });

  function calcRow(elm) {
    var summ = elm.value * $(elm).attr('price');
    $(elm).parent().parent().find('.summ').html(summ.formatMoney());
  }

  $(function () {
    cart_login_dialog.dialog({
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

  $('#close-cart-dialog').click(function () {
    cart_login_dialog.dialog('close');
  });
});
function citySuggest(request, response) {
  $.get("/site/suggestcity",
          {country: country_code.val(), term: request.term},
  function (data) {
    var result = $.parseJSON(data);
    response(result);
  });
}

