<?php
/* @var $this GroupController */
/* @var $group Category */

$brands = $group->getBrands();
if (is_array($brands) && count($brands) > 1){
?>
<div style="border: 1px dashed #666666; border-radius: 3px; min-height: 50px; margin-bottom: 20px">
  <div style="margin: 10px 15px 12px; font-weight: bold">Бренды</div>
  <div style="margin: 10px 15px">
    <?php
    $checked = [];
    if (isset($_GET['filter'])){
      $checked = $_GET['filter']['brands'];
    }
    echo CHtml::beginForm(CHtml::normalizeUrl($this->createUrl($this->route, $this->getActionParams())), 'get', array('id' => 'filterForm'));
    echo CHtml::checkBoxList('filter[brands]', $checked, CHtml::listData($brands, 'id', 'name')
        , array('class' => 'brandFilter', 'labelOptions' => array('style' => 'color:#39c;margin:3px 0')));
//    echo CHtml::hiddenField('filter[brands][]');
    echo CHtml::endForm();
    ?>
  </div>
</div>
<?php } ?>