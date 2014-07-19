<?php
/* @var $this DeliveryController */
/* @var $model Delivery */
?>

<?php
$this->breadcrumbs=array(
	'Виды доставки'=>array('index'),
	'Новая',
);
?>

<h3>Новый вид доставки</h3>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>