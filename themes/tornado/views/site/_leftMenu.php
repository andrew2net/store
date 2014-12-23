<?php
/* @var $this CController */
/* @var $group Category */
/* @var $group NestedSetBehavior */
Yii::import('application.modules.catalog.models.ProductCategory');
?>

<div id="left-menu-cont">
  <?php
  /* @var $groups Category */
  /* @var $groups NestedSetBehavior */
  $groups = Category::model()->roots()->hasProducts()->findAll();
  $items = array();
  $items[] = array('label' => 'Категории:');
  foreach ($groups as $value) {
    /* @var $value Category */
    /* @var $value NestedSetBehavior */
    $groups1 = $value->hasProducts($value->root, 2)->findAll();
    $items1 = array();
    foreach ($groups1 as $value1) {
      /* @var $value1 Category */
      /* @var $value1 NestedSetBehavior */
      $groups2 = $value1->hasProducts($value->root, 3)->findAll();
      $items2 = array();
      foreach ($groups2 as $value2) {
        /* @var $value2 Category */
        /* @var $value2 NestedSetBehavior */
        $items2[] = array(
          'label' => $value2->name,
          'url' => Yii::app()->createUrl('group', array('id' => $value2->id)),
          'active' => isset($group) && $value2->id == $group->id,
        );
      }
      $items1[] = array(
        'label' => $value1->name,
        'url' => Yii::app()->createUrl('group', array('id' => $value1->id)),
        'active' => isset($group) && $value1->id == $group->id,
        'items' => $items2,
        'submenuOptions' => array('class' => 'left-submenu2'),
      );
    }
    $items[] = array(
      'label' => $value->name,
      'url' => Yii::app()->createUrl('/group', array('id' => $value->id)),
      'active' => isset($group) && ($value->id == $group->id || $group->isDescendantOf($value)),
      'items' => $items1,
      'submenuOptions' => array('class' => 'left-submenu1'),
    );
//    $children = $value->children()->findAll();
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
  <?php // echo CHtml::link($value->name, '/group', array('id' => $value->id));  ?>
    </div>-->
  <?php // }  ?>
</div>