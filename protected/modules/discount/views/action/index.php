<?php
/* @var $this ActionController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
Yii::import('application.modules.catalog.models.Product');
$this->breadcrumbs = array(
  'Акции',
);
?>
<?php $this->beginContent('/discount/menu'); ?>
<h3>Акции</h3>

<div class="btn-toolbar">
  <?php
  echo TbHtml::linkButton(
      'Добавить акцию', array(
    'color' => TbHtml::BUTTON_COLOR_PRIMARY,
    'url' => array('/admin/discount/action/create'),
      )
  );
  ?>
</div>

<?php
$this->widget('ext.bootstrap.widgets.TbGridView', array(
  'dataProvider' => $dataProvider,
  'columns' => array(
    array(
      'name' => 'type_id',
      'value' => '$data->type',
    ),
    'name',
    'advert.date',
    array(
      'name' => 'advert.product_id',
      'value' => 'is_null($data->product) ? "" : $data->product->name'),
    array(
      'name'=>'show',
      'value'=>'$data->show ? "Да" : "Нет"'
      ),
    array(
      'class' => 'bootstrap.widgets.TbButtonColumn',
      'template' => '{update}{delete}',
    ),
  ),
));
?>
<?php $this->endContent(); ?>