<?php
/* @var $this DiscountController */
/* @var $model Discount */
/* @var $product Product */
?>

<?php
$this->breadcrumbs = array(
  'Скидки' => array('index'),
  'Изменение',
);
?>

<h3>Изменение скидки</h3>

<?php $this->renderPartial('_form', array('model' => $model, 'product' => $product)); ?>