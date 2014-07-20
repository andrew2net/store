<?php
/* @var $this PageController */
/* @var $model Page */
/* @var $groups array */
?>
<?php // $this->renderPartial('_topmenu'); ?>
<div class="container" id="page">
  <?php
  $this->breadcrumbs = array(
    $model->title,
  );
  $this->widget('zii.widgets.CBreadcrumbs', array(
    'links' => $this->breadcrumbs,
  ));
  ?>
  <?php // echo $model->id; ?>
  <div style="margin-top: 20px">
    <?php
    echo $model->content;
    ?>
  </div>
</div>
