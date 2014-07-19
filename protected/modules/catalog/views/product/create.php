<?php
/* @var $this ProductController */
/* @var $model Product */
/* @var $feature array */
/* @var $prices array */
?>

<?php
$this->breadcrumbs = array(
  'Товары' => array('index'),
  'Новый',
);
?>

<h3>Новый товар</h3>

<?php
$this->renderPartial('_form', array(
  'model' => $model,
  'feature' => $feature,
  'prices' => $prices,
));
?>