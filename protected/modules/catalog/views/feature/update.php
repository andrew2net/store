<?php
/* @var $this FeatureController */
/* @var $model Feature */
/* @var $values FeatureValue[] */

$this->breadcrumbs = array(
  'Характеристики' => 'index',
  'Изменение',
);
?>

<h3>Изменение характеристики: <i><?php echo $model->name; ?></i></h3>

<?php $this->renderPartial('_form', array('model' => $model, 'values' => $values)); ?>