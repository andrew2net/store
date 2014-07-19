<?php
/* @var $this CouponController */
/* @var $model Coupon */
?>

<?php
$this->breadcrumbs=array(
	'Купоны'=>array('index'),
	'Изменение',
);
?>

<h3>Изменение купона <?php echo $model->code; ?></h3>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>