<?php
/* @var $this PageController */
/* @var $model Page */
/* @var $search Search */
/* @var $groups array */
?>
<?php $this->renderPartial('_topmenu'); ?>
<div class="container" id="page">
  <?php $this->renderPartial('_topblock'); ?>
  <?php
  $this->renderPartial('_mainmenu', array(
    'search' => $search,
    'groups' => $groups,
  ));
  ?>

  <?php
//  $this->breadcrumbs = array(
//    $model->title,
//  );
//  $this->widget('zii.widgets.CBreadcrumbs', array(
//    'links' => $this->breadcrumbs,
//  ));
  ?>

  <?php // echo $model->id; ?>
  <div style="margin-top: 20px">
  <?php
  echo $model->content;
  ?>
  </div>
</div>
<?php $this->renderPartial('_footer', array('groups' => $groups)); ?>
