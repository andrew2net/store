<?php
/* @var $this CurrencyController */
/* @var $model Currency */

$this->breadcrumbs = array('Виды валют' => array('index'), 'Новый');
?>

<h3>Новая валюта</h3>

<?php $this->renderPartial('_form', array('model' => $model)); ?>