<?php
/* @var $customer_profile CustomerProfile */
/* @var $user User */
/* @var $profile Profile */
/* @var $order CActiveDataProvider */
/* @var $new_passw NewPassword */

Yii::import('application.modules.payments.models.Currency');

$this->pageTitle = Yii::app()->name . ' - Личный кабинет';
?>
<div class="container" id="page">
  <?php
  $this->breadcrumbs = array(
    'Личный кабинет',
  );
  $this->widget('zii.widgets.CBreadcrumbs', array(
    'links' => $this->breadcrumbs,
  ));
  $form = $this->beginWidget('CActiveForm', array('id' => 'profile'));
  /* @var $form CActiveForm */
  $this->renderPartial('//site/_contact_form', array(
    'profile' => $profile,
    'customer_profile' => $customer_profile,
    'user' => $user,
    'form' => $form,
  ));
  $this->endWidget();
  ?>
  <fieldset>
    <legend><span class="page-title blue bold">Изменение пароля</span></legend>
    <div class="inline-blocks">
      <div>
        <div>
          <?php echo $form->labelEx($new_passw, 'passw1'); ?>
        </div>
        <div>
          <?php echo $form->passwordField($new_passw, 'passw1'); ?>
        </div>
      </div>
      <div style="margin: 0 20px">
        <div>
          <?php echo $form->labelEx($new_passw, 'passw2'); ?>
        </div>
        <div>
          <?php echo $form->passwordField($new_passw, 'passw2'); ?>
        </div>
      </div>
      <div style="vertical-align: bottom">
        <?php echo CHtml::button('Изменить', array('style' => 'width:100px', 'id' => 'change-passw', 'disabled' => true)); ?>
      </div>
    </div>
    <div id="passw-err" style="font-size:10pt; height: 16pt" class="red"></div>
  </fieldset>
  <fieldset>
    <legend><span class="page-title blue bold">Заказы</span></legend>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
      'id' => 'profile-grid-order',
      'dataProvider' => $order,
      'emptyText' => 'Заказов нет',
      'template' => '{items}{summary}{pager}',
      'columns' => array(
        array(
          'name' => '№',
          'value' => '$data->id',
        ),
        array(
          'name' => 'time',
          'value' => 'Yii::app()->dateFormatter->format("dd.MM.yyyy", $data->time)'
        ),
        array(
          'name' => 'summ',
          'value' => 'number_format($data->summ - $data->couponDiscount + $data->delivery_summ, 2, ".", " ")." ".$data->currency->class',
          'type' => 'html',
        ),
        array(
          'name' => 'status_id',
          'type' => 'html',
          'value' => '$data->status_id==3 && $data->payment->type_id!=0 && $data->productSumm>$data->paySumm ? CHtml::link($data->status, Yii::app()->createUrl("pay/order", array("id"=>$data->id))) : $data->status',
        ),
      )
    ));
    ?>
  </fieldset>
</div>
<script type="text/javascript">

  var change_passw_bt = $('#change-passw');
  var passw1 = $('#NewPassword_passw1');
  var passw2 = $('#NewPassword_passw2');
  var passw_err_msg = $('#passw-err');

  $('input[type="password"]').typing({
    stop: function() {
      var p1 = passw1.val();
      var p2 = passw2.val();
      if (p1.length > 5)
        if (p1 === p2) {
          passw_err_msg.html('');
          change_passw_bt.prop('disabled', false);
        }
        else {
          change_passw_bt.prop('disabled', true);
          passw_err_msg.html('Пароль и подтверждение не совпадают');
        }
      else {
        change_passw_bt.prop('disabled', true);
        passw_err_msg.html('Длина пароля должна быть не менее 6 символов');
      }
    }
  });

  change_passw_bt.click(function() {
    change_passw_bt.prop('disabled', true);
    $.post('/profile/changepassw', {
      passw1: passw1.val(),
      passw2: passw2.val()
    }, function(data) {
      var result = $.parseJSON(data);
      passw_err_msg.html(result.msg);
      if (result.result) {
        passw1.val('');
        passw2.val('');
      } else
        change_passw_bt.prop('disabled', false);
    });
  });
</script>