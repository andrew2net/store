<?php
/* @var $this DefaultController 
 * @var $discount CActiveDataProvider
 */

$this->breadcrumbs = array(
  'Скидки',
);
?>
<?php $this->beginContent('menu'); ?>
<h3>Скидки</h3>

<div class="btn-toolbar">
  <?php
  echo TbHtml::linkButton(
      'Добавить скидку', array(
    'color' => TbHtml::BUTTON_COLOR_PRIMARY,
    'url' => array('create'),
      )
  );
  ?>
</div>

  <?php
$this->widget('ext.bootstrap.widgets.TbGridView', array(
  'id' => 'discount-grid',
  'dataProvider' => $discount,
  'columns' => array(
    array(
      'name' => 'type_id',
      'value' => '$data->type',
    ),
    'begin_date',
    'end_date',
    array(
      'name' => 'product_id',
      'value' => '$data->productType',
    ),
    'percent',
    array(
      'name' => 'actual',
      'value' => '$data->actual ? "Да" : "Нет"',
    ),
    array(
      'class' => 'ext.bootstrap.widgets.TbButtonColumn',
      'template' => '{update}{delete}',
    )
  ),
));
?>

<?php $this->endContent(); ?>