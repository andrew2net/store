<?php
/* @var $this PriceController */
/* @var $data CActiveDataProvider */

$this->breadcrumbs = array('Виды цен');
$this->beginContent('/catalog/menu');
?>

<h3>Виды цен</h3>

<div class="btn-toolbar">
  <?php
  echo TbHtml::linkButton('Добавить вид цены', array(
    'color' => TbHtml::BUTTON_COLOR_PRIMARY,
    'url' => array('/admin/catalog/price/create')
  ));
  ?>
</div>

<?php
$this->widget('ext.bootstrap.widgets.TbGridView', array(
  'dataProvider' => $data,
  'columns' => array(
    'name',
    'summ',
    array(
      'class' => 'ext.bootstrap.widgets.TbButtonColumn',
      'template' => '{update}{delete}'
    )
  )
));
?>

<?php $this->endContent(); ?>
