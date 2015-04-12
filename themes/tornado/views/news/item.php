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
    <div class="inline-blocks" style="margin-top: 20px">
        <?php $this->renderPartial('//site/_newsMini', ['news_id' => $model->id]); ?>
        <div>
            <h1><?php echo $model->title; ?></h1>
            <?php echo $model->text; ?>
        </div>
    </div>
</div>