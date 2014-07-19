<?php
/* @var $this PriceController */
/* @var $model Price */

$this->breadcrumbs = array(
  'Виды цен' => array('index'),
  'Новый'
);
?>

<h3>Новый вид цены</h3>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>