<?php
$this->breadcrumbs=array(
	UserModule::t('Users')=>array('index'),
	$model->username,
);
//$this->layout='//layouts/column2';
$this->menu=array(
    array('label'=>UserModule::t('List User'), 'url'=>array('index')),
);
?>
<h3><?php echo UserModule::t('View User').' "'.$model->username.'"'; ?></h3>
<?php 

// For all users
	$attributes = array(
			'username',
	);
	
	$profileFields=ProfileField::model()->forAll()->sort()->findAll();
	if ($profileFields) {
		foreach($profileFields as $field) {
			array_push($attributes,array(
					'label' => UserModule::t($field->title),
					'name' => $field->varname,
					'value' => (($field->widgetView($model->profile))?$field->widgetView($model->profile):(($field->range)?Profile::range($field->range,$model->profile->getAttribute($field->varname)):$model->profile->getAttribute($field->varname))),

				));
		}
	}
	array_push($attributes,
		'create_at',
		array(
			'name' => 'lastvisit_at',
			'value' => (($model->lastvisit_at!='0000-00-00 00:00:00')?$model->lastvisit_at:UserModule::t('Not visited')),
		)
	);
			
	$this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>$attributes,
	));

?>
<h3>Заказы</h3>
<?php
Yii::import('application.modules.payments.models.Payment');
Yii::import('application.modules.delivery.models.Delivery');
Yii::import('application.models.CustomerProfile');
$this->widget('bootstrap.widgets.TbGridView', array(
  'id' => 'order-grid',
  'dataProvider' => $orders,
  'columns' => array(
    'id',
    'time',
    [
      'name' => 'productSumm',
      'value' => 'number_format($data->productSumm + $data->delivery_summ + $data->insuranceSumm - $data->getCouponSumm(),2,"."," ")',
      'htmlOptions' => ['style' => 'text-align:right;width:90px']
    ],
    array(
      'name' => 'status_id',
      'value' => '$data->status',
    ),
    array(
      'name' => 'payment_id',
      'value' => '$data->payment->name',
    ),
    array(
      'name' => 'delivery_id',
      'value' => '$data->delivery->zone_type_id == 4 ? $data->delivery->zone_type : ($data->delivery->zone_type_id == 3 ? $data->delivery->name . " (" . $data->delivery->transportType . ")" : $data->delivery->name)',
    ),
  )
    )
);
?>
