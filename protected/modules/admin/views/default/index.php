<?php
/* @var $this DefaultController */
/* @var $model Order */

$this->breadcrumbs = array(
  'Заказы',
);
?>

<h3>Заказы</h3>
<?php // $this->beginWidget('CHtmlPurifier'); ?>
<?php
Yii::import('application.modules.payment.models.Payment');
Yii::import('application.modules.delivery.models.Delivery');
$this->widget('bootstrap.widgets.TbGridView', array(
  'id' => 'order-grid',
  'dataProvider' => $model->timeOrderDesc()->search(),
  'filter' => $model,
  'columns' => array(
    'id',
    'time',
    array(
      'name' => 'status_id',
      'value' => '$data->status',
      'filter' => $model->statuses,
    ),
//    'fio',
    array(
      'header' => 'Покупатель',
      'value' => '$data->fio',
      'filter' => CHtml::activeTextField($model, 'fio'),
    ),
    'email',
//    array(
//      'name' => 'profile_email',
//      'value' => '$data->profile->email',
//    ),
    'phone',
//    array(
//      'name' => 'profile_phone',
//      'value' => '$data->profile->phone',
//    ),
    array(
      'name' => 'payment_id',
      'value' => '$data->payment->name',
      'filter' => $model->paymentOptions,
    ),
    array(
      'name' => 'delivery_id',
      'value' => '$data->delivery->zone_type_id == 4 ? $data->delivery->zone_type : ($data->delivery->zone_type_id == 3 ? $data->delivery->name . " (" . $data->delivery->transportType . ")" : $data->delivery->name)',
      'filter' => Delivery::getList(),
    ),
    array(
      'class' => 'bootstrap.widgets.TbButtonColumn',
      'template' => '{update}',
    ),
  )
    )
);
?>
<?php // $this->endWidget(); ?>