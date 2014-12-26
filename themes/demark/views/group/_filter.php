<?php
/* @var $this GroupController */
/* @var $group Category */

$brands = $group->getBrands();
if (is_array($brands) && count($brands) > 1){
?>
<div style="border: 1px dashed #666666; border-radius: 3px; min-height: 50px; margin-bottom: 20px">
  <div style="margin: 10px 15px; font-weight: bold">Бренды</div>
  <div style="margin: 10px 15px">
    <?php echo CHtml::checkBoxList('brands_filter', array_map(function($elm) {return $elm->id;}, $brands), CHtml::listData($brands, 'id', 'name')
        , array('class' => 'brandFilter', 'labelOptions' => array('style' => 'color:#39c'))); ?>
  </div>
</div>
<?php } ?>