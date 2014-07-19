<?php
/* $this CurrencyrateController */
/* $model CurrencyRate */
?>
<?php
$form = $this->beginWidget('ext.bootstrap.widgets.TbActiveForm', array());
/* @var $form TbActiveForm */
?>

<p class="help-block"><span class="required">*</span> Обязательные поля.</p>

<?php echo $form->errorSummary($model); ?>

<div class="inline-blocks">
  <div style="margin-right: 20px">
    <?php
    echo $form->labelEx($model, 'date');
    $form->widget('zii.widgets.jui.CJuiDatePicker', array(
      'model' => $model,
      'attribute' => 'date',
      'language' => 'ru',
    ));
    ?>
  </div>
  <div>
    <?php echo $form->textFieldControlGroup($model, 'rate', array('span' => 2)); ?>
  </div>
</div>

<div class="inline-blocks">
  <div style="margin-right: 40px">
    <fieldset class="inline-blocks" style="padding: 0 20px">
      <legend>Из</legend>
      <div>
        <?php echo $form->dropDownListControlGroup($model, 'from', CHtml::listData(Currency::model()->findAll(), 'code', 'name')); ?>
      </div>
      <div>
        <?php echo $form->numberFieldControlGroup($model, 'from_quantity', array('span' => 1)); ?>
      </div>
    </fieldset>
  </div>
  <div>
    <fieldset class="inline-blocks" style="padding: 0 20px">
      <legend>В</legend>
      <div>
        <?php echo $form->dropDownListControlGroup($model, 'to', CHtml::listData(Currency::model()->findAll(), 'code', 'name')); ?>
      </div>
      <div>
        <?php echo $form->numberFieldControlGroup($model, 'to_quantity', array('span' => 1)); ?>
      </div>
    </fieldset>
  </div>
</div>
<?php echo TbHtml::formActions(array(
  TbHtml::linkButton('Закрыть', array('url' => '/payments/currencyrate')),
  TbHtml::submitButton('Сохранить', array(
    'color' => TbHtml::BUTTON_COLOR_PRIMARY
  ))
)); ?>
<?php $this->endWidget(); ?>