<?php
/* @var $this SiteController */
/* @var $error array */

Yii::log('test log', CLogger::LEVEL_INFO, '1c_exchange');

$this->pageTitle = Yii::app()->name . ' - Ошибка';
//$this->breadcrumbs = array(
//  'Error',
//);
?>

<div class="container" id="page">
    <h1 style="margin-top: 2em" class="bold red">Error <?php echo $code; ?></h1>
    <div class="error blue bold"><?php echo CHtml::encode($message); ?></div>
</div>