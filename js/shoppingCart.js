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
    var delivery_loading = $('#delivery-loading');
    var cart_delivery = $('#cart-delivery');
    var cartTotal = $('#cart-total');
    var cartPayment = $('#Order_payment_id');
    var cart_login_dialog = $("#cart-login-dialog");
    var user_email = $('#User_email');
    var insurance = $('#insurance');
    var insurancePrice = insurance.find('span#insurance-price');

    function calcCartSumm() {
        var summ = 0;
        var summNoDisc = 0;
        var discountSumm = 0;
        $('.cart-quantity').each(function () {
            var price = $(this).attr('data-price');
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

        var discountType = coupon.attr('data-type-id');
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
            summTotal += priceDelivery;
            cartTotal.html(summTotal.formatMoney());
            cartSubmit.show();
        }
        else {
            $('#delivery-summ').html('');
            cartSubmit.hide();
            cartTotal.html(summTotal.formatMoney());
        }
    }

    calcCartSumm();
    getDeliveries(true);

    cartSubmit.click(function () {
        cartSubmit.hide();
        var cartProc = cartSubmit.parent().find('img').show();
        var email = user_email.val();
        $.post('/cart/checkemail', {
            email: email
        }, function (data) {
            if (data == 'ok') {
                yaCounter26247867.reachGoal('CREATEORDER');
                ga('send', {
                    'hitType': 'event',
                    'eventCategory': 'order',
                    'eventAction': 'createorder',
                    'eventValue': summTotal
                });
                $('form.item-submit').submit();
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

    cart_login_dialog.on('click', '#close-cart-dialog', function () {
        cart_login_dialog.hide();
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
        if (city_val != this.value) {
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

    function getDeliveries(call) {
        cartSubmit.hide();
        var pcode = post_code.val();
        var city = cart_city.val();
        if (pcode.length === 6 || city.length > 0 || call) {
            var ccode = country_code.val();
            insurance.hide();
            cart_delivery.hide();
            delivery_loading.show();
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
                cart_delivery.html(data);
                cart_delivery.show();
                showDeliveries(pcode, city);
                showInsurance();
            });
        } else {
            var lbls = cart_delivery.find('label > span:not([data-self])').parent();
            lbls.prev().remove();
            lbls.next().remove();
            lbls.remove();
            if (cart_delivery.find('input:checked').length === 0)
                cart_delivery.find('input:first-child').prop('checked', true);
            showDeliveries(pcode, city);
        }
    }

    post_code.tooltip({
        items: '[data-hint]',
        content: 'Укажите индекс для отображения всех доступных способов доставки',
        position: {my: 'top', at: 'bottom+15', collision: 'none', using: function (position, feedback) {
                $(this).css(position);
                $('<div>').addClass('tt-arrow').addClass(feedback.vertical).addClass(feedback.horizontal).appendTo(this);
            }
        }
    });

    cart_city.tooltip({
        items: '[data-hint]',
        content: 'Укажите город или населенный пункт для отображения всех доступных способов доставки',
        position: {my: 'top', at: 'bottom+15', collision: 'none', using: function (position, feedback) {
                $(this).css(position);
                $('<div>').addClass('tt-arrow').addClass(feedback.vertical).addClass(feedback.horizontal).appendTo(this);
            }
        }
    });

    var tooltipInterval;
    function showDeliveries(pcode, city) {
        clearInterval(tooltipInterval);
        delivery_loading.hide();
        post_code.tooltip('disable');
        cart_city.tooltip('disable');

        var tooltipField = false;
        if (pcode.length !== 6)
            tooltipField = post_code;
        else if (city.length === 0)
            tooltipField = cart_city;

        if (tooltipField) {
            tooltipField.tooltip('enable').tooltip("open");
            setTimeout(function () {
                tooltipField.tooltip('close');
            }, 6000);
            tooltipInterval = setInterval(function () {
                tooltipField.tooltip('enable').tooltip("open");
                setTimeout(function () {
                    tooltipField.tooltip('close');
                }, 6000);
            }, 30000);
        }

        checkCashPayment();
        calcTotal();
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
        elem.attr('data-type-id', '');
        elem.attr('data-discount', '');
        if (code.length === 6) {
            $.get('/cart/coupon', {
                coupon: code
            }, function (data) {
                var discount = JSON && JSON.parse(data) || $.parseJSON(data);
                if (discount.type === 3) {
                    $('#discount-text').html(err);
                    coupon.attr('data-type-id', '');
                    coupon.attr('data-discount', '');
                } else {
                    coupon.attr('data-type-id', discount.type);
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
        checkCashPayment();
        showInsurance();
        calcTotal();
    });

    function checkCashPayment() {
        var disCash = cart_delivery.find('input:checked').next('label').find('span[data-self]').length === 0;
        cartPayment.find('span[data-cash]').parent().prev('input').prop('disabled', disCash);
        if (disCash && cartPayment.find('input:enabled:checked').length === 0)
            cartPayment.find('input:enabled:first').prop('checked', true);
    }

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
            cart_delivery.hide();
            insurance.hide();
//            var city = cart_city.val();
//            if (city.length > 0) {
            delivery_loading.show();
            clearTimeout(cartTimeout);
            cartTimeout = setTimeout(function () {
                getDeliveries();
            }, 3000);
//            }
        });
    }

    $(document).on('click', '.cart-item-del', function () {
        $(this).hide().parent().find('img').show();
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
        var summ = elm.value * $(elm).attr('data-price');
        $(elm).parent().parent().find('.summ').html(summ.formatMoney());
    }

});
function citySuggest(request, response) {
    $.get("/site/suggestcity",
            {country: country_code.val(), term: request.term},
    function (data) {
        var result = $.parseJSON(data);
        response(result);
    });
}

