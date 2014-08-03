<?php
/* @var $this DeliveryController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs = array(
  'Виды доставки',
);
?>
<?php $this->beginContent('/layout/menu'); ?>
<h3>Виды доставки</h3>

<div class="btn-toolbar">
  <?php
  echo TbHtml::linkButton(
      'Добавить вид доставки', array(
    'color' => TbHtml::BUTTON_COLOR_PRIMARY,
    'url' => array('create'),
      )
  );
  ?>
</div>

<?php
$this->widget('ext.bootstrap.widgets.TbGridView', array(
  'dataProvider' => $dataProvider,
  'columns' => array(
    'name',
    array(
      'name' => 'transport_type_id',
      'value' => '$data->transportType'
    ),
    'description',
    array(
      'name' => 'active',
      'value' => '$data->active ? "&#10003;" : ""',
      'type' => 'html',
    ),
    array(
      'class' => 'bootstrap.widgets.TbButtonColumn',
      'template' => '{update}{delete}',
    ),
  ),
));
?>
<?php $this->endContent(); ?>