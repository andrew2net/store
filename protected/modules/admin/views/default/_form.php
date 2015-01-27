<?php
/* @var $this DefaultController */
/* @var $model Order */
/* @var $product OrderProduct[] */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('ext.bootstrap.widgets.TbActiveForm', array(
      'id' => 'order-form',
      // Please note: When you enable ajax validation, make sure the corresponding
      // controller action is handling ajax validation correctly.
      // There is a call to performAjaxValidation() commented in generated controller code.
      // See class documentation of CActiveForm for details on this.
      'enableAjaxValidation' => false,
    ));
    /* @var $form TbActiveForm */
    ?>

    <p class="note"><span class="required">*</span> обязательные поля.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="inline-blocks">
        <div>
            <div class="inline-blocks">
                <div class="control-group" style="position: relative; top: 3px">
                    <?php
                    echo TbHtml::label('Покупатель', 'fio');
                    echo TbHtml::tag('div', array(
                      'id' => 'fio',
                      'class' => 'display-field', 'style' => 'width:16em'));
                    echo $model->fio;
                    echo TbHtml::closeTag('div');
                    ?>
                </div>
                <?php echo TbHtml::activeTextFieldControlGroup($model, 'email'); ?>
                <?php echo TbHtml::activeTextFieldControlGroup($model, 'phone'); ?>
                <?php // echo TbHtml::activeDropDownListControlGroup($model, 'call_time_id', $model->callTimes); ?>
            </div>
            <div class="inline-blocks" style="position: relative">
                <div>
                    <?php
                    echo TbHtml::activeLabelEx($model, 'city');
                    ?>
                    <?php
                    $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                      'model' => $model,
                      'attribute' => 'city',
                      'sourceUrl' => '/site/suggestcity',
                      'htmlOptions' => array('style' => 'width: 220px')
                    ));
                    ?>
                    <?php echo TbHtml::error($model, 'city'); ?>
                </div>
                <?php
                echo TbHtml::activeTextFieldControlGroup($model, 'address'
                  , array('span' => 7));
                ?>
                <div style="position: absolute; right: 3px; bottom: 45px">
                    <?php echo $form->checkBoxControlGroup($model, 'insurance'); ?>
                </div>
            </div>
            <div class="inline-blocks">

                <?php
                echo $form->textFieldControlGroup($model, 'time', array(
                  'style' => 'width:135px',
                  'readonly' => TRUE));
                ?>

                <?php
                $options_param = CalcDelivery::getDeliveryList($model->country_code, $model->post_code, $model->city, $model->orderProducts, $model);
                echo $form->dropDownListControlGroup($model, 'delivery_id'
                  , isset($options_param['options']) ? $options_param['options'] : array(), array(
                  'options' => isset($options_param['params']) ? $options_param['params'] : array(), 'style' => 'width:320px'));
                ?>

                <?php
                echo $form->dropDownListControlGroup($model, 'payment_id'
                  , $model->paymentOptions, array('style' => 'width:135px'));
                ?>

                <?php
                echo $form->dropDownListControlGroup($model, 'status_id'
                  , $model->statuses, array('span' => 2));
                ?>

                <div class="control-group" style="position: relative; top: 3px">
                    <?php
                    $couponOptions = array(
                      'id' => 'coupon_code',
                      'class' => 'display-field',
                      'style' => 'width:5em',
                    );
                    if (!is_null($model->coupon)) {
                      $couponOptions['type_id'] = $model->coupon->type_id;
                      $couponOptions['disc'] = $model->coupon->value;
                    }
                    echo TbHtml::label('Купон', 'coupon_code');
                    echo TbHtml::tag('div', $couponOptions);
                    echo is_null($model->coupon) ? '&nbsp' : $model->coupon->code;
                    echo TbHtml::closeTag('div');
                    ?>
                </div>
            </div>
        </div>
        <div style="vertical-align: top">
            <?php
            echo TbHtml::activeTextAreaControlGroup($model, 'description'
              , array('span' => 3, 'rows' => 6));
            ?>
            <?php echo $form->checkBoxControlGroup($model, 'exchange'); ?>
        </div>
    </div>
    <?php
    echo TbHtml::tabbableTabs(array(
      array(
        'label' => 'Товар',
        'active' => TRUE,
        'content' => $this->renderPartial('_product', array(
          'model' => $model,
          'product' => $product,
          ), TRUE),
      ),
      array(
        'label' => 'Оплата',
        'content' => $this->renderPartial('_pay', array('model' => $model), TRUE),
      ),
      ), array('id' => 'order-table'));
    ?>
    <div class="form-actions">
        <?php
        echo TbHtml::linkButton('Закрыть', array(
          'url' => '/admin'));
        ?>
        <?php
        echo TbHtml::submitButton('Сохранить', array(
          'color' => TbHtml::BUTTON_COLOR_PRIMARY,
          'size' => TbHtml::BUTTON_SIZE_SMALL,
        ));
        ?>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->
<?php
$this->widget('ext.bootstrap.widgets.TbModal', array(
  'id' => 'pay-data-modal',
  'content' => $this->renderPartial('_payModalContent', null, true),
//  'footer' => array(TbHtml::button('Закрыть', array('data-dismiss' => 'modal'))),
));
?>
<script type="text/javascript">
  $(function () {
      var order_num = $('#order-num');
      var order_city = $('#Order_city');
      var coupon_code = $('#coupon_code');
      var order_delivery_summ = $('#order-delivery-summ');
      var order_payment = $('#Order_payment_id');
      var order_status_id = $('#Order_status_id');
      var add_product = $('#add-product');
      var order_delivery_id = $('#Order_delivery_id');
      var table = $('#order-product');
      var tbody = table.find('tbody');
      var orderTotal = $('#order-total');
      var orderCouponDiscount = $('#order-coupon-discount');
      var orderInsurance = $('#Order_insurance');
      var orderInsuranceSumm = $('#order-insurance');
      var newrow = '<tr class="row-product"><td><input readonly="readonly" class="row-article" type="text" maxlength="25"/></td><td><input class="row-name input-block-level" type="text" maxlength="255" /></td><td><input class="row-quantity" type="number" value="1" /></td><td><input class="row-price" type="number" /><input class="row-discount" type="hidden" /></td><td><i class="row-del icon-trash" style="cursor:pointer" rel="tooltip" title="Удалить"></i></td></tr>';

      function calcSumm() {
          var sum = 0;
          var noDiscSum = 0;
          var discount = 0;
          $('.row-price').each(function () {
              var price = parseFloat(this.value);
              if (isNaN(price))
                  price = 0;
              var priceid = this.id;
              var quantityid = priceid.replace('price', 'quantity');
              var quantity = parseInt($('#' + quantityid).val());
              if (isNaN(quantity))
                  quantity = 0;
              var disc = $(this).parent().find('.row-discount').val(); //parseFloat($(this).attr('disc'));
              var s = price * quantity;
              if (disc > 0)
                  discount += disc * quantity;
              else
                  noDiscSum += s;
              sum += s;
          });
          var couponType = coupon_code.attr('type_id');
          var couponDisc = parseFloat(coupon_code.attr('disc'));
          var couponSum = 0;
          switch (couponType) {
              case '0':
                  couponSum = noDiscSum > couponDisc ? couponDisc : noDiscSum;
                  break;
              case '1':
                  couponSum = noDiscSum * couponDisc / 100;
                  break;
          }
          sum -= couponSum;
          var delivery = parseFloat(order_delivery_summ.val());
          sum += delivery;
          var insuranceSumm = 0;
          if (orderInsurance.prop('checked')) {
              insuranceSumm = parseFloat(order_delivery_id.find('option:selected').attr('data-insurance'));
              sum += insuranceSumm;
          }
          orderInsuranceSumm.html(insuranceSumm);
          orderTotal.html(sum);
          orderCouponDiscount.html(couponSum);
      }

      function setStatus() {
          var stat = order_status_id.val();
          var read = true;
          if (stat === '1' || stat === '2') {
              read = false;
              add_product.show();
              if ($('.row-product').length > 1)
                  $('.row-del').show();
          } else {
              add_product.hide();
              $('.row-del').hide();
          }
          $('.row-name, .row-quantity, .row-price').prop('readonly', read);
          order_delivery_summ.prop('readonly', read);
          order_delivery_id.prop('disabled', read);
          order_payment.prop('disabled', read);
          orderInsurance.prop('readonly', read);
      }

      calcSumm();
      setStatus();

      orderInsurance.click(function () {
          if (orderInsurance.prop('readonly')) {
              return false;
          }
      });
      orderInsurance.change(function () {
          calcSumm();
      });

      table.on('keyup change', '.row-price, #order-delivery-summ', function () {
          calcSumm();
      });

      var quantityTimeOut;
      table.on('keyup change', '.row-quantity', function () {
          clearTimeout(quantityTimeOut);
          calcSumm();
          quantityTimeOut = setTimeout(function () {
              getCityDeliveries(order_city.val());
          }, 500)
      });

      order_status_id.change(function () {
          setStatus();
      });

      var response = function (event, ui) {
          for (var i = 0; i < ui.content.length; i++) {
              ui.content[i].label = ui.content[i].article + ', ' + ui.content[i].value;
          }
      }

      var current_id;
      var selectItem = function (event, ui) {
          current_id = ui.item.id;
          var row = $(this).parent().parent();
          var art = row.find('.row-article');
          art.val(ui.item.article);
          art.attr('id', 'Product_' + ui.item.id + '_article');
          art.attr('name', 'Product[' + ui.item.id + '][article]');
          this.id = 'Product_' + ui.item.id + '_name';
          this.name = 'Product[' + ui.item.id + '][name]';
          var quant = row.find('.row-quantity');
          quant.attr('id', 'OrderProduct_' + ui.item.id + '_quantity');
          quant.attr('name', 'OrderProduct[' + ui.item.id + '][quantity]');
          var price = row.find('.row-price');
          price.val(ui.item.price);
          price.attr('id', 'OrderProduct_' + ui.item.id + '_price');
          price.attr('name', 'OrderProduct[' + ui.item.id + '][price]');
          var disc = row.find('.row-discount');
          disc.val(ui.item.disc);
          disc.attr('id', 'OrderProduct_' + ui.item.id + '_discount');
          disc.attr('name', 'OrderProduct[' + ui.item.id + '][discount]');
          getCityDeliveries(order_city.val());
      }

      function getId(str) {
          var res = /^.+_(\d+)_/.exec(str);
          if (res)
              return res[1];
          return undefined;
      }

      function productSuggest(request, response) {
          var ord_pr = {};
          var summ = 0;
          $('.row-quantity').each(function (i, e) {
              if (e.id.length > 0) {
                  var prod_id = getId(e.id);
                  if (prod_id != current_id) {
                      summ += parseFloat($(this).parent().parent().find('.row-price').val()) * parseInt(e.value);
                      ord_pr[i] = prod_id;
                  }
              }
          });
          $.get('/admin/default/orderproduct', {
              oid: order_num.html(),
              term: request.term,
              summ: summ,
              ord_pr: ord_pr},
          function (data) {
              var result = $.parseJSON(data);
              response(result);
          });
      }

      add_product.click(function (event) {
          event.preventDefault();
          tbody.append(newrow);
          tbody.find('.row-name').last().autocomplete({
              source: function (request, response) {
                  productSuggest(request, response);
              },
              response: response,
              select: selectItem,
          });
          setStatus();
      });

      tbody.on('click', '.row-del', function () {
          $(this).parent().parent().remove();
          if ($('.row-product').length < 2)
              $('.row-del').css('display', 'none');
          getCityDeliveries(order_city.val());
      });

      tbody.on('focus', '.row-name', function () {
          current_id = getId(this.id);
      });

      $(function () {
          $('.row-name').autocomplete({
              source: productSuggest,
              response: response,
              select: selectItem
          });
      });

      function getCityDeliveries(city) {
          var delivery_id = order_delivery_id.val();
          var products = {};
          $('.row-quantity').each(function (i, el) {
              var id = getId(el.id);
              products[id] = el.value;
          });
          $.get('/admin/default/citydeliveries', {city: city, oid: order_num.html(), products: products}, function (data) {
              var result = JSON && JSON.parse(data) || $.parseJSON(data);
              order_delivery_id.empty();
              $.each(result, function (key, value) {
                  order_delivery_id.append('<option value="' + key + '" price="' + value.price + '">'
                          + value.text + '</option>');
              });
              var opt = order_delivery_id.find('option[value="' + delivery_id + '"]');
              var price;
              if (opt.length > 0) {
                  opt.prop('selected', true);
                  price = opt.attr('price');
              } else
                  price = order_delivery_id.find('option:selected').attr('price');
              if (price == undefined)
                  price = 0;
              order_delivery_summ.val(price);
              calcSumm();
          });
      }

      order_city.on('change autocompleteselect', function (event, ui) {
          event.stopImmediatePropagation();
          getCityDeliveries(this.value);
      });

      order_delivery_id.change(function () {
          order_delivery_summ.val(order_delivery_id.find('option:selected').attr('price'));
          calcSumm();
      });

      var payTable = $('#pay-table-body');
      var payTotal = $('#pay-table-total');
      var payModal = $('#pay-data-modal');
      var payModalHeader = payModal.find('.modal-header > h3');
      var payModalMessage = payModal.find('#pay-model-message');
      var payModalProcess = payModal.find('#pay-modal-process');
      var payModalFooter = payModal.find('.modal-footer');

      payTable.on('click', '.pay-action~ul > li > a', function (event) {
          event.preventDefault();
          var tr = $(this).parents('tr');
          var id = tr.find('input#pay_id').val();
          payModalHeader.html('Обновление статуса платежа');
          payModalMessage.hide();
          payModalProcess.show();
          payModalFooter.html('');
          payModal.modal('show');
          $.get(this.href, {id: id}, function (data) {
              if (data.status) {
                  payModalHeader.html(data.header);
                  payModalMessage.html(data.message);
                  payModalProcess.hide();
                  payModalMessage.show();
                  payModalFooter.html(data.footer);
                  if (data.body !== undefined)
                      payTable.html(data.body);
                  if (data.total !== undefined)
                      payTotal.html(data.total);
              }
              return;
          }, 'json');
          return;
      });

      payModal.on('click', '#modal-ok-bt', function () {
          var btOk = $(this);
          var btCancel = btOk.parent().find('button[data-dismiss="modal"]');
          btOk.prop('disabled', true);
          btCancel.prop('disabled', true);
          payModalMessage.hide();
          payModalProcess.show();
          var id = payModal.find('input#modal-pay-id').val();
          var url = btOk.attr('acturl');
          $.post(url, {id: id}, function (data) {
              btCancel.prop('disabled', false);
              payModalMessage.html(data.message);
              payModalProcess.hide();
              payModalMessage.show();
              if (data.status) {
                  payTable.html(data.body);
                  payTotal.html(data.total);
                  if (data.ostatus !== undefined)
                      order_status_id.find('option[value="' + data.ostatus + '"]').attr('selected', 'selected');
                  btOk.hide();
                  btCancel.html('Закрыть');
              } else {
                  btOk.prop('disabled', false);
              }
          }, 'json');
      });
  });
</script>