<?php
/* $model User */
$this->breadcrumbs = array(
  UserModule::t("Users"),
);
if (UserModule::isAdmin()) {
//	$this->layout='//layouts/column2';
  $this->menu = array(
    array('label' => UserModule::t('Manage Users'), 'url' => array('/user/admin')),
    array('label' => UserModule::t('Manage Profile Field'), 'url' => array('profileField/admin')),
  );
}
?>

<h3><?php echo UserModule::t("List User"); ?></h3>

<div class="btn-toolbar">
  <?php
  echo TbHtml::linkButton(
      'Добавить пользователя', array(
    'color' => TbHtml::BUTTON_COLOR_PRIMARY,
    'url' => array('/admin/user/create'),
      )
  );
  ?>
</div>
<?php
$this->widget('ext.bootstrap.widgets.TbGridView', array(
  'dataProvider' => $model->search(),
  'filter' => $model,
  'columns' => array(
    'id',
    array(
      'name' => 'username',
      'type' => 'raw',
      'value' => 'CHtml::link(CHtml::encode($data->username),array("user/view","id"=>$data->id))',
    ),
    'email',
    'create_at',
    'lastvisit_at',
    array(
      'class' => 'ext.bootstrap.widgets.TbButtonColumn',
    )
  ),
));
?>
