<?php
/* @var $this NewsletterController */
/* @var $model Newsletter */
/* @var $blocks NewsletterBlock[] */
?>

<?php
$this->breadcrumbs=array(
	'Рассылки'=>array('index'),
	'Изменение',
);
?>

<h3>Изменение рассылки</h3>

<?php $this->renderPartial('_form', array('model'=>$model, 'blocks' => $blocks)); ?>