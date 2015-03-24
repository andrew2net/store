<?php
/* @var $this NewsletterController */
/* @var $model Newsletter */
/* @var $blocks NewsletterBlock[] */
?>

<?php
$this->breadcrumbs=array(
	'Рассылки'=>array('index'),
	'Новая',
);
?>

<h3>Новая рассылка</h3>

<?php $this->renderPartial('_form', array('model'=>$model, 'blocks' => $blocks)); ?>