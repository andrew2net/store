<?php
/* @var $group Category */
?>
<div id="submenu-<?php echo $group->id; ?>" class="inline-blocks submenu container">
  <?php
  $categories = $group->children()->findAll();
  foreach ($categories as $category) {
    echo CHtml::openTag('div', array('style'=>'vertical-align:top'));
    echo CHtml::openTag('div');
    echo CHtml::link($category->name, $this->createUrl('group'
            , array('id' => $category->id)), array('class' => 'menu-category'));
    echo CHtml::closeTag('div');
    $subcategories = $category->children()->findAll();
    foreach ($subcategories as $subcategory) {
      echo CHtml::openTag('div');
      echo CHtml::link($subcategory->name, $this->createUrl('group'
              , array('id' => $subcategory->id)), array('class' => 'menu-subcategory'));
      echo CHtml::closeTag('div');
    }
    echo CHtml::openTag('div');
    echo CHtml::link('Смотреть все', $this->createUrl('group'
            , array('id' => $category->id)));
    echo CHtml::closeTag('div');
    echo CHtml::closeTag('div');
  }
  ?>
</div>