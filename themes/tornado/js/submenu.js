Number.prototype.formatMoney = function(c, d, t) {
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
function showTooltip(id, data) {
//  price_mess.html($('#price-header').attr('title'));
//  price_mess.show('bounce');
//  setTimeout(function() {
//    price_mess.hide('blind')
//  }, 2000);
}

$(document).ready(function() {
  price_mess = $('#price-mess');

  $().ready(function($) {
    var menu = $('#mainmenuarea');
    if (menu.attr('cart'))
      return false;
    var page = $('#page');
    var offset = menu.offset();
    var fix = false;
    $(window).scroll(function() {
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
    $(window).resize(function() {
      menu.css({left: -$(document).scrollLeft(), width: $(document).width()});
    });
  });

  $(document).on('click', '.addToCart > input, .addToCart', function(event) {
    event.preventDefault();
    event.stopPropagation();
    if (this == $('.addToCart > input')[0])
      return;
    closeLoginDialog();
    var button = $(this);
    button.removeClass('addToCart');
    var id = button.attr('product');
    var quantity = button.find('input').val();
    if (!quantity)
      quantity = 1;
    $.post('/site/addtocart', {
      'id': id,
      'quantity': quantity
    },
    function(data) {
      var result = $.parseJSON(data);
      var img = button.parent().parent().find('.img-anim');
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
        display: 'initial',
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
              .fadeOut(100, function() {
        cart.html(result.cart);
        button.addClass('addToCart');
        $(this).remove();

        if (result.refresh) {
          if ($('#inline-product-list').length > 0)
            $.fn.yiiListView.update('inline-product-list');
          else if ($('#top10').length) {
            $.get('/site/price', function(data) {
              var result = $.parseJSON(data);
              if (result.top10 !== undefined)
                for (var key in result.top10) {
                  $('.item[product="' + key + '"] .item-price').html(result.top10[key].price);
                  $('.item[product="' + key + '"] .item-disc').html(result.top10[key].disc);
                }
            });
          }
          price_mess.html(result.price);
          price_mess.show('bounce');
          setTimeout(function() {
            price_mess.hide('blind')
          }, 2000);
        }
      });
    });
  });

  $('.submit').click(function() {
    $('form').submit();
  });

  $(document).on('click', '.item-link', function(event) {
    event.preventDefault();
    $('#item-submit').attr('action', $(this).attr('href'));
    $('#item-submit').submit();
  });
  $(document).on('click', '.fancybox', function(event) {
    event.stopPropagation();
  });

  $(document).on('keydown', ".input-number", function(event) {
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
});
