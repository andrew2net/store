<?php
/* $model CActiveDataProvider */
/* $this CurrencyrateController */

$this->breadcrumbs = array('Курсы валют');

$this->beginContent('/layout/menu');
?>
<h3>Курсы валют</h3>

<div class="btn-toolbar">
<?php 
  echo TbHtml::linkButton('Добавить курс', array(
    'color' => TbHtml::BUTTON_COLOR_PRIMARY,
    'url' => '/admin/payments/currencyrate/create',
));
?>
</div>

<?php
$this->widget('ext.bootstrap.widgets.TbGridView', array(
  'dataProvider' => $model,
  'columns' => array(
    'date',
    'from',
    'from_quantity',
    'to',
    'to_quantity',
    'rate',
    array(
      'class' => 'ext.bootstrap.widgets.TbButtonColumn',
      'template' => '{update}{delete}',
    )
  )
));

$this->endContent();
?>