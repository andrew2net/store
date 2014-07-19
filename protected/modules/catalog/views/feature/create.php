<?php
/* @var $this FeatureController */
/* @var $model Feature */
/* @var $values FeatureValue[] */

$this->breadcrumbs = array(
  'Характеристики' => array('index'),
  'Новая'
);
?>
<h3>Новая характеристика</h3>

<?php $this->renderPartial('_form', array('model' => $model, 'values' => $values)); ?>