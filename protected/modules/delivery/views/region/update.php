<?php
/* @var $this RegionController */
/* @var $model Region */
/* @var $delivery Delivery[] */
?>

<?php
$this->breadcrumbs = array(
  'Регионы' => array('index'),
  'Изменение',
);
?>

<h3>Изменение региона: <i><?php echo $model->country->name_ru . ' ' . $model->name; ?></i></h3>

<?php $this->renderPartial('_form', array('model' => $model, 'delivery' => $delivery)); ?>