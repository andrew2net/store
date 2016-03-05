<?php
/* @var $this CController */
/* @var $group Category */
/* @var $group NestedSetBehavior */
Yii::import('application.modules.catalog.models.ProductCategory');
Yii::import('application.modules.catalog.models.Category');
?>
<div>
    <div id="left-menu-cont">
        <?php
        /* @var $groups Category */
        /* @var $groups NestedSetBehavior */
        $groups = Category::model()->roots()->hasProducts()->findAll(['order' => 'name']);
        $items = array();
        $items[] = array('label' => 'Категории:');
        foreach ($groups as $value) {
          /* @var $value Category */
          /* @var $value NestedSetBehavior */
          $groups1 = $value->hasProducts($value->root, 2)->findAll(['order' => 'name']);
          $items1 = array();
          foreach ($groups1 as $value1) {
            /* @var $value1 Category */
            /* @var $value1 NestedSetBehavior */
            $groups2 = $value1->hasProducts($value->root, 3)->findAll(['order' => 'name']);
            $items2 = array();
            foreach ($groups2 as $value2) {
              /* @var $value2 Category */
              /* @var $value2 NestedSetBehavior */
              if ($value2->url){
              $url2 = $value2->url;
              if (preg_match('/^http:/', $value2->url)){
                  $linkOpt = ['target' => '_blank'];
                }else{
                  $linkOpt2 = [];
                }
              }  else {
                $url2 = Yii::app()->createUrl('group', array('id' => $value2->id));
                $linkOpt2 = [];
              }
              $items2[] = array(
                'label' => $value2->name,
                'url' => $url2,
                'active' => isset($group) && $value2->id == $group->id,
                'linkOptions' => $linkOpt2,
              );
            }
            if ($value1->url){
              $url1 = $value1->url;
              if (preg_match('/^http:/', $value1->url)){
                $linkOpt1 = ['target' => '_blank'];
              }else{
                $linkOpt1 = [];
              }
            }  else {
              $url1 = Yii::app()->createUrl('group', array('id' => $value1->id));
              $linkOpt1 = [];
            }
            $items1[] = array(
              'label' => $value1->name,
              'url' => $url1,
              'active' => isset($group) && $value1->id == $group->id,
              'items' => $items2,
              'submenuOptions' => array('class' => 'left-submenu2'),
              'linkOptions' => $linkOpt1,
            );
          }
          if ($value->url){
              $url = $value->url;
              if (preg_match('/^http:/', $value->url)){
                $linkOpt = ['target' => '_blank'];
              }else{
                $linkOpt = [];
              }
          }  else {
              $url = Yii::app()->createUrl('/group', array('id' => $value->id));
              $linkOpt = [];
          }
          $items[] = array(
            'label' => $value->name,
            'url' => $url,
            'active' => isset($group) && ($value->id == $group->id || $group->isDescendantOf($value)),
            'items' => $items1,
            'submenuOptions' => array('class' => 'left-submenu1'),
            'linkOptions' => $linkOpt,
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
    </div>
    <?php $this->renderPartial('//site/_newsMini'); ?>
</div>