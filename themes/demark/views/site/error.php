<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle = Yii::app()->name . ' - Ошибка';
//$this->breadcrumbs = array(
//  'Error',
//);
?>

<div class="container" id="page">
  <?php
  $this->renderPartial('_topblock');
  $this->renderPartial('_mainmenu');
  ?>
  <h1 style="margin: 2em 0" class="bold red">Error <?php echo $code; ?></h1>

  <div class="error blue bold"><?php echo CHtml::encode($message); ?></div>
</div>
<?php $this->renderPartial('_footer'); ?>
