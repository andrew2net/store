<?php
/* @var $this RegionController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs = array(
  'Регионы',
);
?>
<?php $this->beginContent('/layout/menu'); ?>

<h3>Регионы</h3>

<div class="btn-toolbar">
  <?php
  echo TbHtml::linkButton(
      'Добавить Регион', array(
    'color' => TbHtml::BUTTON_COLOR_PRIMARY,
    'url' => array('/admin/delivery/region/create'),
      )
  );
  ?>
</div>

<?php
$this->widget('ext.bootstrap.widgets.TbGridView', array(
  'dataProvider' => $dataProvider,
  'columns' => array(
    array(
      'name' => 'country_code',
      'value' =>'$data->country->name_ru'
      ),
    'name',
    array(
      'class' => 'bootstrap.widgets.TbButtonColumn',
      'template' => '{update}{delete}',
    ),
  ),
));
?>
<?php $this->endContent(); ?>