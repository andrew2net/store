<?php
/* @var $this RegionController */
/* @var $model Region */
?>

<?php
$this->breadcrumbs=array(
	'Регионы'=>array('index'),
	'Новый',
);
?>

<h3>Новый регион</h3>

<?php $this->renderPartial('_form', array('model'=>$model, 'delivery' => $delivery)); ?>