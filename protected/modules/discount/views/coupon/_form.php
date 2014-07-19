<?php
/* @var $this CouponController */
/* @var $model Coupon */
/* @var $form TbActiveForm */
?>

<div class="form">

  <?php
  $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'coupon-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
  ));
  /* @var $form TbActiveForm */
  ?>

  <p class="help-block"><span class="required">*</span> Обязательные поля.</p>

  <?php echo $form->errorSummary($model); ?>

  <div class="inline-blocks">
    <div>
      <?php
      $options = array(
        'span' => 2,
        'maxlength' => 8,
      );
      if (!$model->isNotUsed)
        $options['readOnly'] = TRUE;

      echo $form->textFieldControlGroup($model, 'code', $options);
      ?>
    </div>
    <div>
      <?php
      $used_options = array('span' => 2);
      if (!$model->isNotUsed && !is_null($model->time_used))
        $used_options['disabled'] = TRUE;
      echo $form->dropDownListControlGroup($model, 'used_id'
          , $model->usedValues, $used_options);
      ?>
    </div>
  </div>

  <div class="inline-blocks">
    <div>
      <?php
      $options['maxlength'] = 5;
      echo $form->textFieldControlGroup($model, 'value', $options);
      ?>
    </div>
    <div id="coupon-type">
      <?php echo $form->textFieldControlGroup($model, 'value_tenge', $options);
      ?>
    </div>
    <div>
      <?php
      unset($options['maxlength'], $options['readOnly']);
      if (!$model->isNotUsed)
        $options['disabled'] = TRUE;
      echo $form->dropDownListControlGroup($model, 'type_id'
          , $model->types, $options);
      ?>
    </div>
  </div>
  <?php
  echo CHtml::activeLabel($model, 'date_limit');
  $this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'model' => $model,
    'attribute' => 'date_limit',
    'language' => 'ru',
    'htmlOptions' => array('style' => 'width: 80px')
  ));
  ?>
  <div class="form-actions">
    <?php
    echo TbHtml::linkButton('Закрыть', array(
      'url' => '/admin/discount/coupon/index'));
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
<script type="text/javascript">
  var tenge = $('#Coupon_value_tenge').parent().parent();
  var tengeShow = function(elm) {
    if (elm.val() == 0)
      tenge.show();
    else
      tenge.hide();
  }
  var couponType = $('#Coupon_type_id');
  $().ready(tengeShow(couponType));
  couponType.change(function (){tengeShow(couponType); });
</script>