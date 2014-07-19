<?php
/* @var $this CurrencyController */
/* @var $model Currency */

Yii::import('application.controllers.ProfileController');

$form = $this->beginWidget('ext.bootstrap.widgets.TbActiveForm', array());
/* @var $form TbActiveForm */
?>

<p class="help-block"><span class="required">*</span> Обязательные поля.</p>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldControlGroup($model, 'code'); ?>
<?php echo $form->textFieldControlGroup($model, 'name'); ?>
<?php echo $form->textFieldControlGroup($model, 'short'); ?>
<?php echo $form->dropDownListControlGroup($model, 'country_code', ProfileController::getCountries()); ?>
<?php echo $form->textFieldControlGroup($model, 'class'); ?>
<?php echo $form->textFieldControlGroup($model, 'iso'); ?>

<?php 
echo TbHtml::formActions(array(
  TbHtml::linkButton('Закрыть', array('url' => '/payments/currency')),
  TbHtml::submitButton('Сохранить', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)),
));
$this->endWidget(); ?>