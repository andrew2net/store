<?php
/* @var $this CurrencyController */
/* @var $model Currency */

$this->breadcrumbs = array('Виды валют' => array('index'), 'Изменение');
?>

<h3>Изменение валюты: <i><?php echo $model->name; ?></i></h3>

<?php $this->renderPartial('_form', array('model' => $model)); ?>