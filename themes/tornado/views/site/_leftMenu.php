<?php
/* @var $this CController */
/* @var $group Category */
?>

<div style="border: 1px solid #999; width: 200px; font-size: 12pt; margin: 0px 14px 0 0">
  <?php
  $groups = Category::model()->roots()->findAll();
  $items = array();
  foreach ($groups as $value) {
    /* @var $value Category */
    $items[] = array(
      'label' => $value->name,
      'url' => Yii::app()->createUrl('/group', array('id' => $value->id)),
      'active' => isset($group) && $value->id == $group->id,
    );
    $children = $value->children()->findAll();
  }
  $this->widget('zii.widgets.CMenu', array(
    'items' => $items,
    'htmlOptions' => array('style' => 'list-style-type: none; margin: 0; padding: 0', 'id' => 'left-menu'),
    'itemCssClass' => 'left-menu-item',
    'activateItems' => true,
    'activeCssClass' => 'left-menu-active',
  ));
  ?>
  <!--  <div>
  <?php // echo CHtml::link($value->name, '/group', array('id' => $value->id)); ?>
    </div>-->
  <?php // } ?>
</div>