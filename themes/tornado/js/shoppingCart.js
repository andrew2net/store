var cartSumm = $('#cart-summ');
var coupon = $('#coupon');
var discountText = $('#discount-text');
var cartDiscount = $('#cart-discount');
var cartSubmit = $('#cart-submit');
var post_code = $('#CustomerProfile_post_code');
var cart_items = $('#cart-items');
var price_name = $('#price-name');
var price_header = $('#price-header');

//price_header.tooltip({position: {my: 'bottom', at: 'top-5'}});

function calcCartSumm() {
  var summ = 0;
  var summNoDisc = 0;
  var discountSumm = 0;
  $('.cart-quantity').each(function() {
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
    cartSubmit.show();
  }
  else {
    $('#delivery-summ').html('');
    cartSubmit.hide();
    $('#cart-total').html(summ.formatMoney());
  }
}

calcCartSumm();
calcTotal();

cartSubmit.click(function() {
  var email = $('#CustomerProfile_email').val();
  $.post('/profile/checkemail', {
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

//$('#cart-city').on('autocompleteselect', function(event, elem) {
//  getDeliveries(elem.item.value);
//});

$('#CustomerProfile_country_code').change(function() {
  getDeliveries();
});

post_code.typing({
  start: function(event, elem) {
  },
  stop: function(event, elem) {
    getDeliveries();
  },
  delay: 0
});

post_code.focusout(function() {
  getDeliveries();
});

function getDeliveries() {
  var ccode = $('#CustomerProfile_country_code').val();
  var pcode = post_code.val();
  $('#cart-delivery').html('');
  if (pcode.length === 6)
    $.get('/cart/delivery', {
      'ccode': ccode,
      'pcode': pcode
    }, function(data) {
      $('#delivery-hint').hide();
      $('#cart-delivery').html(data);
      calcTotal();
    });
  else {
    $('#delivery-hint').show();
    calcTotal();
  }
}

coupon.typing({
  start: function(event, elem) {
  },
  stop: function(event, elem) {
    getCoupon(elem);
  },
  delay: 2000
});

coupon.focusout(function() {
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
    }, function(data) {
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

$('#cart-delivery').on('change', 'input[name="Order[delivery_id]"]', function() {
  calcTotal();
});

var quantityTimeOut;
$(document).on('change', '.cart-quantity', function() {
  clearTimeout(quantityTimeOut);
  var id = $(this).attr('product');
  var quantity = parseInt(this.value);
  if (isNaN(quantity))
    quantity = 0;
  if (quantity < 0) {
    quantity = 0;
    this.value = quantity;
  } else if (quantity > 999) {
    quantity = 999;
  }
  calcRow(this);
  calcCartSumm();
  changeCart(id, quantity);
});

var cartQuantity;
$(document).on('keyup', '.cart-quantity', function(event) {
  var id = $(this).attr('product');
  if (this.value.length > 0) {
    var value = parseInt(this.value);
    if (value > 999)
      this.value = cartQuantity;
    else
      this.value = value;
  }
  else
    this.value = 0;
  value = this.value;
  calcRow(this);
  calcCartSumm();
  quantityTimeOut = setTimeout(function() {
    changeCart(id, value);
  }, 1000);
});

$(document).on('keydown', '.cart-quantity', function(event) {
  clearTimeout(quantityTimeOut);
  cartQuantity = this.value;
});

function changeCart(id, quantity) {
  $.post('/cart/changeCart', {
    'id': id,
    'quantity': quantity
  }, function(data) {
    if (data) {
      refreshCart(data);
    }
    getDeliveries();
  });
}

$(document).on('click', '.cart-item-del', function() {
  var id = $(this).attr('product');
  $.post('/cart/delitem', {
    'id': id
  }, function(data) {
    refreshCart(data);
    getDeliveries();
  }
  );
});

function refreshCart(data) {
  var result = $.parseJSON(data)
  cart_items.html(result.html);
  if (result.price_name) {
    var title = 'Ваша цена "' + result.price_name + '"';
    price_header.attr('title', title);
    price_name.html('(' + result.price_name + ')');
    price_mess.html(title);
    price_mess.show('bounce');
    setTimeout(function() {
      price_mess.hide('blind');
    }, 2000);
  }
  calcCartSumm();
}

function calcRow(elm) {
  var summ = elm.value * $(elm).attr('price');
  $(elm).parent().parent().find('.summ').html(summ.formatMoney());
}

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

function citySuggest(request, response) {
  $.get("/site/suggestcity",
          {country: $("#CustomerProfile_country_code").val(), term: request.term},
  function(data) {
    var result = $.parseJSON(data);
    response(result);
  });
}

