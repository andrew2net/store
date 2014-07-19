<?php
/* @var $this CouponController */
/* @var $model Coupon */
?>

<?php
$this->breadcrumbs=array(
	'Купоны'=>array('index'),
	'Новый',
);
?>

<h3>Новый купон</h3>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>