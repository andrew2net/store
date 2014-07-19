<?php
/* @var $this BrandController */
/* @var $model Brand */

$this->breadcrumbs=array(
	'Бренды'=>array('index'),
	'Новый',
);
?>

<h3>Новый бренд</h3>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>