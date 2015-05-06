Number.prototype.formatMoney = function (c, d, t) {
    var n = this,
            c = isNaN(c = Math.abs(c)) ? 0 : c,
            d = d == undefined ? "." : d,
            t = t == undefined ? " " : t,
            s = n < 0 ? "-" : "",
            i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

var price_mess;
function scrollUp() {
    $('html, body').animate({scrollTop: 0});
}

$(document).ready(function () {
    price_mess = $('#price-mess');

//  $().ready(function($) {
    var menu = $('#mainmenuarea');
    if (menu.attr('data-cart'))
        return false;
    var page = $('#page');
    var offset = menu.offset();
    var fix = false;
    $(window).scroll(function () {
        if (fix) {
            menu.css({left: -$(document).scrollLeft()});
        }
        if ($(this).scrollTop() > offset.top && !fix) {
            login_dialog.hide();
            menu.addClass('f-menu');
            page.css('margin-top', '50px')
            menu.css({left: -$(document).scrollLeft(), width: $(document).width()});
            fix = true;
        }
        else if ($(this).scrollTop() < offset.top && fix) {
            menu.removeClass('f-menu');
            page.css('margin-top', '10px')
            fix = false;
        }
    });
    $(window).resize(function () {
        menu.css({left: -$(document).scrollLeft(), width: $(document).width()});
    });

    $(document).on('click', '.addToCart > input, .addToCart', function (event) {
        event.preventDefault();
        event.stopPropagation();
        if (this.nodeName === 'DIV')
            var button = $(this);
        else
            return;
        closeLoginDialog();
        var parent = button.hide().parent();
        var addProc = parent.find('.item-add-proc').show();
        var id = button.attr('data-product');
        var quantity = parseInt(button.find('input').val());
        if (!quantity)
            quantity = 1;
        $.post('/site/addtocart', {
            'id': id,
            'quantity': quantity
        },
        function (data) {
            var result = $.parseJSON(data);
            ga('send', {
                'hitType':'event',
                'eventCategory':'order',
                'eventAction':'addtocart',
                'eventValue': result.value
            });
            var img = parent.parent().find('.img-anim');
            if (!img.length)
                img = $('.img-anim');
            var img2 = img.clone();
            var imgOffset = img.offset();
            var win = $(window);
            imgOffset.right = win.innerWidth() - imgOffset.left - img.width();
            img2.css({
                display: 'none'
            });
            var cart = $('#shoppingCart');
            var cartOffset = cart.offset();
            cartOffset.right = win.innerWidth() - cartOffset.left - cart.width();
            img2.appendTo('body');
            img2.css({
                display: 'block',
                position: 'absolute',
                top: imgOffset.top,
                left: imgOffset.left + 50,
                right: imgOffset.right - 50,
                'z-index': 4000
            })
                    .animate({
                        maxWidth: '83px', //img.attr('width') * 0.66,
                        maxHeight: '75px', //img.attr('height') * 0.66,
                        opacity: 0.2,
                        top: cartOffset.top + 10,
                        right: cartOffset.right + 30,
                        left: cartOffset.left - 30
                    }, 1000)
                    .fadeOut(100, function () {
                        cart.html(result.cart);
                        addProc.hide();
//                itemDisc.css('display', "");
//                itemPrice.css('display', "");
                        button.css('display', "");
//                button.addClass('addToCart');
                        $(this).remove();

                        if (result.refresh) {
                            if ($('#product-list').length > 0)
                                $.fn.yiiListView.update('product-list');
                            else if ($('#top10').length) {
                                $.get('/site/price', function (data) {
                                    var result = $.parseJSON(data);
                                    if (result.top10 !== undefined)
                                        for (var key in result.top10) {
                                            var p = $('.item[data-product="' + key + '"] .item-price');
                                            p.html(result.top10[key].price);
                                            p.attr('title', result.title);
                                            $('.item[data-product="' + key + '"] .item-disc').html(result.top10[key].disc);
                                        }
                                });
                            }
                            price_mess.html(result.price);
                            price_mess.show('bounce');
                            setTimeout(function () {
                                price_mess.hide('blind')
                            }, 3000);
                        }
                    });
        });
    });

    $('.submit').click(function () {
        $('form').submit();
    });

    $(document).on('click', '.item-link', function (event) {
        event.preventDefault();
        $('#item-submit').attr('action', $(this).attr('href'));
        $('#item-submit').submit();
    });
    $(document).on('click', '.fancybox', function (event) {
        event.stopPropagation();
    });

    $(document).on('keydown', ".input-number", function (event) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(event.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
                        (event.keyCode == 65 && event.ctrlKey === true) ||
                        // Allow: home, end, left, right
                                (event.keyCode >= 35 && event.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                else {
                    // Ensure that it is a number and stop the keypress
                    if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                        event.preventDefault();
                    }
                }
            });

    $(document).tooltip({
        items: 'div.img-anim > img[data-big-img], .tooltip-price, .addToCart > div',
        track: true,
        content: function () {
            var elm = $(this);
            if (elm.is('[data-big-img]')) {
                var src = elm.attr('data-big-img');
                if (src.length > 0)
                    return '<img class="img-tooltip" src="' + src + '" alt="Загрузка изображения..." />';
            }
            if (elm.is('.tooltip-price')) {
                var pricesData = $.parseJSON(elm.attr('data-price'));
                var text = '';
                if (pricesData.length > 0) {
                    text = '<div>Цена зависит от общей суммы заказа</div><table style="margin-bottom:0"><tr><th colspan=2 style="text-align:center">сумма заказа<span class="ruble">Р</span></th><th style="text-align:center">цена<span class="ruble">Р</span></th></tr>';
                    $.each(pricesData, function (index, elment) {
                        text += '<tr><td style="text-align:right">от</td><td style="text-align:right;padding-right:35px">' + elment[0].formatMoney(0) + '</td><td style="text-align:right;padding-right:20px">' + elment[1].formatMoney(0) + '</td></tr>';
                    });
                    text += '</table>'
                }
                return text;
            }
            if (elm.is('.addToCart > div')) {
                return elm.attr('title');
            }
        }
    });
});
