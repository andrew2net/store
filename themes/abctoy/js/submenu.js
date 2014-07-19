Number.prototype.formatMoney = function(c, d, t) {
  var n = this,
          c = isNaN(c = Math.abs(c)) ? 0 : c,
          d = d == undefined ? "." : d,
          t = t == undefined ? " " : t,
          s = n < 0 ? "-" : "",
          i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
          j = (j = i.length) > 3 ? j % 3 : 0;
  return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "") + '.-';
};


$(document).ready(function() {

  $('#aSubmit').click(function() {
    $('#giftSelect').submit();
  });
  $('#GiftCategory').val($('#currentGroup').val());

  $('#categoryOnly').change(function() {
    if (this.checked)
      $('#GiftCategory').val($('#currentGroup').val());
    else
      $('#GiftCategory').val('');
  });

  $(document).on('click', '.addToCart', function(event) {
    event.preventDefault();
    event.stopPropagation();
    var id = $(this).attr('product');
    var quantity = $('#ProductForm_quantity').val();
    if (!quantity)
      quantity = 1;
    $.post('/addtocart', {
      'id': id,
      'quantity': quantity
    },
    function(data) {
      $('#shoppingCart').html(data);
      $("#add-product-dialog").dialog("open");
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

  $("#add-product-dialog").dialog({
    autoOpen: false,
    modal: true,
    draggable: false,
    resizable: false,
    width: 410,
    dialogClass: "add-prodact-alert",
    show: {
      effect: "blind",
      duration: 500
    },
    hide: {
      effect: "explode",
      duration: 500
    }
  });

  $('#close-add-dialog').click(function() {
    $("#add-product-dialog").dialog('close');
  });

  $('#cart-add-dialog').click(function() {
    $("#add-product-dialog").dialog('close');
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
