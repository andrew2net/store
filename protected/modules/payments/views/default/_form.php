<?php
/* @var $this PaymentController */
/* @var $payment Payment */
?>
<div class="form">
  <?php
  $form = $this->beginWidget('ext.bootstrap.widgets.TbActiveForm');
  ?>
  <p class="help-block"><span class="required">*</span> Обязательные поля.</p>

  <?php echo $form->errorSummary($payment); ?>

  <?php echo TbHtml::activeTextFieldControlGroup($payment, 'name'); ?>
  <?php
  echo TbHtml::activeTextAreaControlGroup($payment, 'description', array(
    'class' => 'span6'
  ));
  ?>
  <?php echo TbHtml::activeDropDownListControlGroup($payment, 'type_id', Payment::getTypes()); ?>
  <?php echo TbHtml::activeCheckBoxControlGroup($payment, 'active'); ?>

  <?php
  $display = '';
  if ($payment->type_id == 0 || $payment->type_id == 3)
    $display = ' style="display:none"';
  ?>
  <div id="pay_params"<?php echo $display; ?>>
    <?php
    echo TbHtml::activeTextFieldControlGroup($payment, 'action_url', array('class' => 'span5'));
    echo TbHtml::activeTextFieldControlGroup($payment, 'sign_name', array('class' => 'span5'));
    echo TbHtml::activeTextFieldControlGroup($payment, 'sign_key', array('class' => 'span5'));
    ?>
  </div>
  <div class="form-actions">
    <?php
    echo TbHtml::linkButton('Закрыть', array(
      'url' => '/admin/payments'));
    ?>
    <?php
    echo TbHtml::submitButton('Сохранить', array(
      'color' => TbHtml::BUTTON_COLOR_PRIMARY,
      'size' => TbHtml::BUTTON_SIZE_SMALL,
    ));
    ?>
  </div>

  <?php $this->endWidget() ?>
</div>
<script type="text/javascript">
  var pay_params = $('#pay_params');
  $('#Payment_type_id').change(function() {
    var type = $(this).find('option:selected').val();
    if (type == 1 || type == 2)
      pay_params.show();
    else
      pay_params.hide();
  });
</script>