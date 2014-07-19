<?php
/* @var $group Category */
?>

<div style="border: 1px dashed #666666; border-radius: 3px; min-height: 50px; margin-bottom: 20px">
  <div style="margin-top: 10px">
    <?php
    if ($group->level > 1) {
      $root = $group->ancestors($group->level)->findAll();
      $menu = $root[0];
    }
    else
      $menu = $group;

    $discount_products = Product::model()->subCategory($menu->id)->availableOnly()->discount()->count();
    if ($discount_products) {
      echo CHtml::tag('a', array(
        'href' => $this->createUrl('discount', array('id' => $menu->id)),
        'style' => 'padding-left:1.5em',
        'class' => 'red',
          ), 'Товары со скидкой');
//    echo CHtml::tag('div', array('class'=>'icon-dicount', 'style'=>'display:inline-block;background-size:34px 34px'), '&nbsp;');
    }
    $categories = $menu->descendants(2)->findAll(array('order' => 'lft'));
    $level = 0;
    foreach ($categories as $category) {
      if ($category->level == $level)
        echo CHtml::closeTag('li');
      else if ($category->level > $level)
        echo CHtml::openTag('ul', array('style' => 'margin-right:8px'));
      else {
        echo CHtml::closeTag('li');

        for ($i = $level - $category->level; $i; $i--) {
          echo CHtml::closeTag('ul');
          echo CHtml::closeTag('li');
        }
      }

      $class = 'category';
      if (($category->level - 1) == 1)
        $class .= ' nomarker';
      else
        $class .= ' subcategory';

      if ($category->id == $group->id)
        $class .= ' current-category';

      echo CHtml::openTag('li', array('class' => $class));
      echo CHtml::openTag('a', array('href' => $this->createUrl('group', array(
          'id' => $category->id
      ))));
      if (($category->level - 1) == 1 && $category->url) {
        echo CHtml::openTag('div', array('class' => 'category-menu-lev1'));
        echo CHtml::openTag('div');
        echo CHtml::openTag('span');
        echo CHtml::encode($category->getAttribute('name'));
        echo CHtml::closeTag('span');
        echo CHtml::closeTag('div');
        echo CHtml::openTag('div');
        echo CHtml::tag('img', array('src' => $category->url, 'alt' => $category->name, 'width' => 34));
        echo CHtml::closeTag('div');
        echo CHtml::closeTag('div');
      }
      else
        echo CHtml::encode($category->getAttribute('name'));

      echo CHtml::closeTag('a');

      $level = $category->level;
    }
    for ($i = $level; $i; $i--) {
      echo CHtml::closeTag('li');
      echo CHtml::closeTag('ul');
    }
    ?>
  </div>
</div>
