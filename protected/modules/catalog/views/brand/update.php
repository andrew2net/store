<?php
/* @var $this BrandController */
/* @var $model Brand */

$this->breadcrumbs=array(
	'Бренды'=>array('index'),
	'Изменение',
);
?>

<h3>Изменение бренда: <?php echo $model->name; ?></h3>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>