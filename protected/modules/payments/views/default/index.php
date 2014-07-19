<?php
/* @var $this DefaultController */
/* @var $payment CActiveDataProvider */

$this->breadcrumbs = array(
  'Виды оплаты',
);

$this->beginContent('/layout/menu');
?>
<h3>Виды оплаты</h3>

<?php
$this->widget('ext.bootstrap.widgets.TbGridView', array(
  'dataProvider' => $payment,
  'columns' => array(
    'name',
    'description',
    'type',
    array(
      'name' => 'active',
      'value' => '$data->active ? "&#10003;" : ""',
      'type' => 'html',
    ),
    array(
      'class' => 'bootstrap.widgets.TbButtonColumn',
      'template' => '{update}',
    ),
  ),
));

$this->endContent();
?>
