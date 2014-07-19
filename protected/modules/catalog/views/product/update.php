<?php
/* @var $this ProductController */
/* @var $model Product */
/* @var $feature array */
/* @var $prices array */
?>

<?php
$this->breadcrumbs = array(
  'Товары' => array('index'),
  $model->name,
);
?>

<h3>Изменение товара: <i><?php echo $model->name; ?></i></h3>

<?php
$this->renderPartial('_form', array(
  'model' => $model,
  'feature' => $feature,
  'prices' => $prices
));
?>