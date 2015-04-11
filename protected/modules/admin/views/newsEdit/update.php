<?php
/* @var $this NewsController */
/* @var $model News */
?>

<?php
$this->breadcrumbs=array(
	'Новости'=>array('index'),
	'Изменение',
);
?>

<h3>Изменение новости <i><?php echo $model->title; ?></i></h3>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>