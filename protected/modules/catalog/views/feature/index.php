<?php
/* @var $this Controller */
/* @var $model Feature */

$this->breadcrumbs = array('Характеристики');

$this->beginContent('/catalog/menu');
?>
<h3>Характеристики</h3>
<div class="btn-toolbar">
  <?php
  echo TbHtml::linkButton('Добавить характеристику', array(
    'color' => TbHtml::BUTTON_COLOR_PRIMARY,
    'url' => '/admin/catalog/feature/create',
  ));
  ?>
</div>
<?php
$this->widget('ext.bootstrap.widgets.TbGridView', array(
  'dataProvider' => $model->search(),
//  'filter' => $model,
  'columns' => array(
    'name',
    array(
      'name' => 'type_id',
      'value' => '$data->type',
      'filter' => $model->types,
    ),
    array(
      'class' => 'ext.bootstrap.widgets.TbButtonColumn',
      'template' => '{update}{delete}',
    )
  ),
));
?>

<?php $this->endContent(); ?>