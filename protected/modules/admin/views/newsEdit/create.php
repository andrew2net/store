<?php
/* @var $this NewsController */
/* @var $model News */
?>

<?php
$this->breadcrumbs=array(
	'Новости'=>array('index'),
	'Новая',
);
?>

<h3>Новая новость</h3>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>