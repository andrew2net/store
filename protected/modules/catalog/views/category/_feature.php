<?php
/* @var $model Category */
/* @var $form TbActiveForm */
/* @var $this CategoryController */

Yii::import('application.modules.catalog.models.Feature');
Yii::import('application.modules.catalog.models.CategoryFeature');
$feature = Feature::model()->with(array(
      'categoryFeatures' => array(
        'on' => 'categoryFeatures.category_id=:id',
        'params' => array(':id' => $model->id)
  )))->findAll();
?>
<div style="height: 479px; overflow-y: scroll">
  <table class="table-striped">
    <?php foreach ($feature as $value) { ?>
      <tr>
        <td><?php echo TbHtml::checkBox("feature[$value->id]", isset($value->categoryFeatures[0])); ?></td>
        <td><?php echo $value->name . ' ' . $value->unit; ?></td>
      </tr>
    <?php } ?>
  </table>
</div>