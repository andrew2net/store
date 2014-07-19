<?php
/* @var $model User */

$this->breadcrumbs = array(
  (UserModule::t('Users')) => array('/admin/user'),
//	$model->username=>array('admin/view','id'=>$model->id),
  'Изменение',
);
$this->menu = array(
  array('label' => UserModule::t('Create User'), 'url' => array('create')),
  array('label' => UserModule::t('View User'), 'url' => array('view', 'id' => $model->id)),
  array('label' => UserModule::t('Manage Users'), 'url' => array('admin')),
  array('label' => UserModule::t('Manage Profile Field'), 'url' => array('profileField/admin')),
  array('label' => UserModule::t('List User'), 'url' => array('/user')),
);
?>

<h3>Изменение пользователя <i><?php echo $model->username; ?></i></h3>

<?php
echo $this->renderPartial('_form', array(
  'model' => $model,
  'profile' => $profile,
  'customer_profile' => $customer_profile,
));
?>