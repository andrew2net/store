<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle = Yii::app()->name . ' - Ошибка';
//$this->breadcrumbs = array(
//  'Error',
//);
?>

<div class="container" id="page">
  <?php $this->renderPartial('_topblock'); ?>
  <h1 style="margin-top: 2em" class="bold red">Error <?php echo $code; ?></h1>

  <div class="error blue bold"><?php echo CHtml::encode($message); ?></div>
</div>