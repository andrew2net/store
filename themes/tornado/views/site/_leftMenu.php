<?php
/* @var $this CController */
/* @var $group Category */
?>

<div id="left-menu-cont">
  <?php
  $groups = Category::model()->roots()->findAll();
  $items = array();
  $items[] = array('label' => 'Категории:');
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