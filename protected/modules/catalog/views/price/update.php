<?php
/* @var $this PriceController */
/* @var $model Price */

$this->breadcrumbs = array(
  'Виды цен' => array('index'),
  $model->name
);
?>

<h3>Изменение вида цены: <i><?php echo $model->name; ?></i></h3>

<?php $this->renderPartial('_form', array('model' => $model)); ?>