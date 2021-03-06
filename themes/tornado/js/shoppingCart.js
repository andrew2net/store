$(document).ready(function () {
    var cartSumm = $('#cart-summ');
    var coupon = $('#coupon');
    var discountText = $('#discount-text');
    var cartDiscount = $('#cart-discount');
    var cartSubmit = $('#cart-submit');
    var cart_items = $('#cart-items');
    var price_name = $('#price-name');
    var price_header = $('#price-header');
    var cart_city = $('#cart-city');
    var post_code = $('#CustomerProfile_post_code');
    var delivery_hint = $('#delivery-hint');
    var delivery_loading = $('#delivery-loading');
    var cart_delivery = $('#cart-delivery');
    var cart_login_dialog = $("#cart-login-dialog");
    var user_email = $('#User_email');
    var insurance = $('#insurance');
    var insurancePrice = insurance.find('span#insurance-price');

    post_code.tooltip({
        items: '[data-hint]',
        content: 'Укажите Ваш индекс для расчета льготной доставки',
        position: {my: 'left top-15', at: 'right+12', collision: 'none', using: function (position, feedback) {
                $(this).css(position);
                $('<div>').addClass('tt-arrow').addClass(feedback.vertical).addClass(feedback.horizontal).appendTo(this);
            }
        }
    });

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

        var discountType = coupon.attr('data-type_id');
        if (summNoDisc === 0) {
            coupon.prop({disabled: true});
            discountText.html('');
        } else {
            coupon.prop('disabled', false);
            if (discountType !== undefined && discountType.length > 0) {
                var text = 'скидка ';
                var discountDisc = coupon.attr('data-discount');
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

    var summTotal;
    function calcTotal() {
        var delivery = $('#cart-delivery input:checked + label > span');
        var priceDelivery = parseFloat(delivery.attr('data-price'));
        summTotal = parseFloat(cartSumm.attr('summ'));
        if (!isNaN(priceDelivery)) {
            if (insurance.find('input[type="checkbox"]:checked').length > 0) {
                summTotal += parseFloat(delivery.attr('data-insurance'));
            }
            var price_f = priceDelivery.formatMoney();
            $('#delivery-summ').html(price_f);
            $('#cart-total').html((priceDelivery + summTotal).formatMoney());
            if (summTotal >= minsumm)
                cartSubmit.show();
        }
        else {
            $('#delivery-summ').html('');
            cartSubmit.hide();
            $('#cart-total').html(summTotal.formatMoney());
        }
    }

    calcCartSumm();
    getDeliveries();

    cartSubmit.click(function () {
        cartSubmit.hide();
        var cartProc = cartSubmit.parent().find('img').show();
        var email = $('#User_email').val();
        var submitTimeOut = setTimeout(function(){
                cartProc.hide();
                cartSubmit.show();            
        }, 60000);
        $.post('/cart/checkemail', {
            email: email
        }, function (data) {
            clearTimeout(submitTimeOut);
            if (data == 'ok') {
                var priceDelivery = parseFloat($('#cart-delivery input:checked + label > span').attr('data-price'));
                var summ = parseFloat(cartSumm.attr('summ'));
                if (!isNaN(priceDelivery))
                    summ += priceDelivery;
                yaCounter26247687.reachGoal('CREATEORDER', {price: summ});
                ga('send', {
                    'hitType': 'event',
                    'eventCategory': 'order',
                    'eventAction': 'createorder',
                    'eventValue': summTotal
                });
                $('form#item-submit').submit();
            } else {
                cartProc.hide();
                cartSubmit.show();
                cart_login_dialog.html(data);
                cart_login_dialog.show();
            }
        });
    });

    cart_login_dialog.on('click', '#submit-password', function () {
        var email = user_email.val();
        var passw = $('#cart-password').val();
        $.post('/login', {
            email: email,
            passw: passw
        }, function (data) {
            if (data == 'ok') {
                $('#login-fl').val('1');
                $('form').submit();
            } else
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

    var pcode_typing;
    var city_typing;
    $('#CustomerProfile_city_l, #CustomerProfile_other_city, #CustomerProfile_post_code').change(function () {
        clearTimeout(city_typing);
        clearTimeout(pcode_typing);
        getDeliveries();
    });

    cart_city.keyup(function (event) {
        if (event.keyCode == 13) // || event.keyCode == 8 || event.keyCode > 36 && event.keyCode < 41)
            return false;
        clearTimeout(city_typing);
        city_typing = setTimeout(function () {
            getDeliveries();
        }, 3000);
    });

    post_code.mask('999999', {completed: function () {
            clearTimeout(pcode_typing);
            getDeliveries();
        }});
    post_code.keyup(function (event) {
        post_code.tooltip('open');
        if ((event.keyCode < 48 || event.keyCode > 57) && event.keyCode != 8) { // || event.keyCode == 8 || event.keyCode > 36 && event.keyCode < 41)
            return false;
        }
        clearTimeout(pcode_typing);
        pcode_typing = setTimeout(function () {
            getDeliveries();
        }, 3000);

    });

    cart_city.on('autocompleteselect', function (event, elem) {
        clearTimeout(city_typing);
        getDeliveries();
    });

    function getCity() {
        if ($('input#CustomerProfile_other_city:checkbox:checked').val())
            return cart_city.val();
        else
            return $('select#CustomerProfile_city_l option:selected').val();
    }

//  function deliveryID() {
//    var delivery = $('input:radio[name="Order[delivery_id]"]:checked');
//    if (delivery.length > 0)
//      return delivery.val();
//    else
//      return 0;
//  }

    var getDeliveryTimeout;
    function getDeliveries() {
        if (post_code.val().length < 6) {
            post_code.tooltip('open');
        }
        cartSubmit.hide();
        var city = getCity();
        if (city.length > 0) {
            cart_delivery.hide();
            delivery_hint.hide();
            insurance.hide();
            delivery_loading.show();
            var ccode = 'RU';
            var pcode = post_code.val();
            var delivery = $('input:radio[name="Order[delivery_id]"]:checked');
            var d_id = 0;
            if (delivery.length > 0)
                d_id = delivery.val();
            var id = delivery.attr('id');
            var c_deliver = delivery.siblings('label[for="' + id + '"]').find('input[type="text"]').val();
            if (c_deliver == undefined)
                c_deliver = '';
            getDeliveryTimeout = setTimeout(function () {
                delivery_loading.hide();
            }, 120000);
            $.get('/cart/delivery', {
                'ccode': ccode,
                'pcode': pcode,
                'city': city,
                'delivery_id': d_id,
                'c_deliver': c_deliver
            }, function (data) {
                clearTimeout(getDeliveryTimeout);
                delivery_loading.hide();
                cart_delivery.html(data);
                var input = cart_delivery.find('input:radio[name="Order[delivery_id]"]:checked~label > input[type="text"]');
                input.prop('disabled', false);
                cart_delivery.show();
                showInsurance();
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

    function showInsurance() {
        var price = parseFloat($('#cart-delivery input:checked + label > span').attr('data-insurance'));
        if (price > 0) {
            insurancePrice.html(price);
            insurance.show();
        } else {
            insurance.hide();
        }
    }

    insurance.change(function () {
        calcTotal();
    });

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
        elem.attr('data-type_id', '');
        elem.attr('data-discount', '');
        if (code.length === 6) {
            $.get('/cart/coupon', {
                coupon: code
            }, function (data) {
                var discount = JSON && JSON.parse(data) || $.parseJSON(data);
                if (discount.type === 3) {
                    $('#discount-text').html(err);
                    coupon.attr('data-type_id', '');
                    coupon.attr('data-discount', '');
                } else {
                    coupon.attr('data-type_id', discount.type);
                    coupon.attr('data-discount', discount.discount);
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
        var parent = $(this).parent();
        parent.find('input[type="text"]').prop('disabled', true);
        var input = parent.find('label[for="' + this.id + '"] > input[type="text"]');
        input.prop('disabled', false);
        showInsurance();
        calcTotal();
    });

    var quantityTimeOut;
    cart_items.on('change', '.cart-quantity', function () {
        cartSubmit.hide();
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
        calcTotal();
        changeCart(id, quantity);
    });

    var cartQuantity;
    cart_items.on('keyup', '.cart-quantity', function (event) {
        clearTimeout(quantityTimeOut);
        cartSubmit.hide();
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
        quantityTimeOut = setTimeout(function () {
            changeCart(id, value);
        }, 1000);
    });

    cart_items.on('keydown', '.cart-quantity', function (event) {
        clearTimeout(quantityTimeOut);
        cartQuantity = this.value;
    });

    var cartTimeout;
    function changeCart(id, quantity) {
        cartSubmit.hide();
        $.post('/cart/changeCart', {
            'id': id,
            'quantity': quantity
        }, function (data) {
            if (data) {
                refreshCart(data);
            }
            cart_delivery.hide();
            insurance.hide();
            cart_delivery.val('');
            var city = getCity();
            if (city.length > 0) {
                delivery_loading.show();
                clearTimeout(cartTimeout);
                cartTimeout = setTimeout(function () {
                    getDeliveries();
                }, 3000);
            }
        });
    }

    cart_items.on('click', '.cart-item-del', function () {
        $(this).hide().parent().find('img').show();
        var id = $(this).attr('product');
        $.post('/cart/delitem', {
            'id': id
        }, function (data) {
            refreshCart(data);
            getDeliveries();
        }
        );
    });

    var newPriceTimeout;
    function refreshCart(data) {
        var result = $.parseJSON(data)
        cart_items.html(result.html);
        if (result.price_name) {
            clearTimeout(newPriceTimeout)
            price_header.attr('title', 'Ваша цена "' + result.price_name + '"');
            price_name.html('(' + result.price_name + ')');
            price_mess.html('Установлена цена "' + result.price_name + '"');
            price_mess.show('bounce');
            newPriceTimeout = setTimeout(function () {
                price_mess.hide('blind');
            }, 3000);
        }
        calcCartSumm();
    }

    function calcRow(elm) {
        var summ = elm.value * $(elm).attr('price');
        $(elm).parent().parent().find('.summ').html(summ.formatMoney());
    }

    cart_login_dialog.on('click', '#close-cart-dialog', function () {
        cart_login_dialog.hide();
    });

//  function citySuggest(request, response) {
//    $.get("/site/suggestcity",
//            {country: $("#CustomerProfile_country_code").val(), term: request.term},
//    function (data) {
//      var result = $.parseJSON(data);
//      response(result);
//    });
//  }
});
