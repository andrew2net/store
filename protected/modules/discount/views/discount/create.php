<?php
/* @var $this DiscountController */
/* @var $model Discount */
/* @var $product Product */
?>

<?php
$this->breadcrumbs = array(
  'Скидки' => array('index'),
  'Новая',
);
?>

<h3>Новая скидка</h3>

<?php $this->renderPartial('_form', array('model' => $model, 'product' => $product)); ?>