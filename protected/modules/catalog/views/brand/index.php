<?php
/* @var $this BrandController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
  'Бренды',
);
?>
<?php $this->beginContent('/catalog/menu'); ?>

<h3>Бренды</h3>
<div class="btn-toolbar">
  <?php
  echo TbHtml::linkButton(
      'Добавить бренд', array(
    'color' => TbHtml::BUTTON_COLOR_PRIMARY,
    'url' => array('/admin/catalog/brand/create'),
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
      'class' => 'bootstrap.widgets.TbButtonColumn',
      'template'=>'{update}{delete}',
    ),
  ),
    )
);
?>
<?php
$this->endContent()?>