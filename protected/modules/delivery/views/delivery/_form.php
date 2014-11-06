<?php
/* @var $this DeliveryController */
/* @var $model Delivery */
Yii::import('application.modules.payments.models.Currency');
?>

<div class="form">

  <?php
  $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'delivery-form',
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
    <?php echo $form->textFieldControlGroup($model, 'name', array('span' => 3, 'maxlength' => 30)); ?>
    <?php echo $form->dropDownListControlGroup($model, 'zone_type_id', $model->zone_types, array('prompt' => 'Выберите вид тарифных зон')); ?>
    <?php echo $form->dropDownListControlGroup($model, 'transport_type_id', $model->transportTypes, array('prompt'=>'Выберите тип транспорта')); ?>
  </div>

  <fieldset class="inline-blocks" style="width: 850px">
    <legend>Максимальный размер отправления (см)</legend>
    <div>
      <?php echo $form->numberFieldControlGroup($model, 'length', array('span' => 1, 'min' => 0)); ?>
    </div>
    <div style="margin: 0 20px">
      <?php echo $form->numberFieldControlGroup($model, 'width', array('span' => 1, 'min' => 0)); ?>
    </div>
    <div>
      <?php echo $form->numberFieldControlGroup($model, 'height', array('span' => 1, 'min' => 0)); ?>
    </div>
    <div style="margin: 0 20px">
      <?php echo $form->dropDownListControlGroup($model, 'size_method_id', $model->size_methods); ?>
    </div>
    <div>
      <?php echo $form->numberFieldControlGroup($model, 'size_summ', array('span' => 1, 'min' => 0)); ?>
    </div>
    <div style="margin-left: 20px">
      <?php echo $form->numberFieldControlGroup($model, 'oversize', array('span' => 1, 'min' => 0)); ?>
    </div>
  </fieldset>
  <div class="inline-blocks">
    <?php echo $form->numberFieldControlGroup($model, 'max_weight', array('span' => 2, 'min' => 0, 'step' => 0.01)); ?>
    <div style="margin: 0 20px">
      <?php echo $form->dropDownListControlGroup($model, 'currency_code', CHtml::listData(Currency::model()->findAll(), 'code', 'name')); ?>
    </div>
    <?php echo $form->numberFieldControlGroup($model, 'insurance', array('span' => 1, 'min' => 0)); ?>
    <?php echo $form->checkBoxControlGroup($model, 'active'); ?>
  </div>

  <?php echo $form->textAreaControlGroup($model, 'description', array('rows' => 6, 'span' => 8)); ?>

  <div class="form-actions">
    <?php
    echo TbHtml::linkButton('Закрыть', array(
      'url' => '/admin/delivery/delivery/index'));
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