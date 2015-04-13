<?php
/* $model News */
/* $this CController */

$this->pageTitle = Yii::app()->name . ' - Новости: ' . $model->title;
?>
<div class="container" id="page">
    <?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
      'links' => ['Главная' => '/', 'Новость: ' . $model->title],
      'homeLink' => FALSE,
      'separator' => ' > ',
      'htmlOptions' => array(
        'class' => 'breadcrumbs',
      )
    ));
    ?>
    <div style="margin-top: 20px; display: table; width: 100%">
        <div style="display: table-cell; width: 220px">
        <?php $this->renderPartial('//site/_newsMini', ['news_id' => $model->id]); ?>
        </div>
        <div style="display: table-cell">
            <h1><?php echo $model->title; ?></h1>
            <?php echo $model->text; ?>
        </div>
    </div>
</div>