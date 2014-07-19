<?php
/* $model CActiveDataProvider */
/* $this CurrencyController */

$this->breadcrumbs = array('Виды валют');
?>

<?php $this->beginContent('/layout/menu'); ?>
<h3>Виды валют</h3>

<div class="btn-toolbar">
  <?php
  echo TbHtml::linkButton('Добавить валюту', array(
    'color' => TbHtml::BUTTON_COLOR_PRIMARY,
    'url' => '/admin/payments/currency/create'
  ));
  ?>
</div>

<?php
$this->widget('ext.bootstrap.widgets.TbGridView', array(
  'dataProvider' => $model,
  'columns' => array(
    'code',
    'name',
    'country_code',
    array(
      'class' => 'ext.bootstrap.widgets.TbButtonColumn',
      'template' => '{update}{delete}',
    ),
  ),
));
?>

<?php $this->endContent(); ?>