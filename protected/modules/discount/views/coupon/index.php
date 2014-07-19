<?php
/* @var $this CouponController */
/* @var $model Coupon */
?>

<?php
$this->breadcrumbs=array(
	'Купоны',
);
?>

<?php $this->beginContent('/discount/menu'); ?>
<h3>Купоны</h3>

<div class="btn-toolbar">
  <?php
  echo TbHtml::linkButton(
      'Добавить купон', array(
    'color' => TbHtml::BUTTON_COLOR_PRIMARY,
    'url' => array('/admin/discount/coupon/create'),
      )
  );
  ?>
</div>

  <?php $this->widget('ext.bootstrap.widgets.TbGridView',array(
	'dataProvider'=>$model->search(),
  'filter' => $model,
	'columns'=>array(
    'code',
    array(
      'name' => 'used_id',
      'value' => '$data->used',
      'filter' => $model->usedValues,
    ),
    'value',
    'value_tenge',
    array(
      'name' => 'type_id',
      'value' => '$data->type',
      'filter' => $model->types,
    ),
    'time_issue',
    'time_used',
    'date_limit',
    array(
      'class' => 'bootstrap.widgets.TbButtonColumn',
      'template' => '{update}{delete}',
      'buttons'=>array(
        'update'=>array(
          'visible'=>'$data->isNotUsed || $data->used_id==1 || $data->used_id==2 && !$data->hasUsedTime'
        ),
        'delete'=>array(
          'visible'=>'$data->isNotUsed'
        ),
      ),
    ),
  ),
)); ?>
<?php $this->endContent(); ?>