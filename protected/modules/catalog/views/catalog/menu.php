<?php

/* @var $this Controller */

$this->widget('ext.bootstrap.widgets.TbNav', array(
  'type' => TbHtml::NAV_TYPE_TABS,
  'items' => array(
    array('label' => 'Товары',
      'url' => '/admin/catalog/product',
      'active' => $this instanceof ProductController,
      'visible' => Yii::app()->user->checkAccess('catalog.product.*') ||
      Yii::app()->user->checkAccess('catalog.*')
    ),
    array('label' => 'ТОП 10',
      'url' => '/admin/catalog/top10',
      'active' => $this instanceof Top10Controller,
      'visible' => Yii::app()->user->checkAccess('catalog.top10.*') ||
      Yii::app()->user->checkAccess('catalog.*')
    ),
    array('label' => 'Категории',
      'url' => '/admin/catalog/category',
      'active' => $this instanceof CategoryController,
      'visible' => Yii::app()->user->checkAccess('catalog.category.*') ||
      Yii::app()->user->checkAccess('catalog.*')
    ),
    array('label' => 'Бренды',
      'url' => '/admin/catalog/brand',
      'active' => $this instanceof BrandController,
      'visible' => Yii::app()->user->checkAccess('catalog.brand.*') ||
      Yii::app()->user->checkAccess('catalog.*')
    ),
    array('label' => 'Характеристики',
      'url' => '/admin/catalog/feature',
      'active' => $this instanceof FeatureController,
      'visible' => Yii::app()->user->checkAccess('catalog.feature.*') || Yii::app()->user->checkAccess('catalog.*'),
    ),
    array('label' => 'Виды цен',
      'url' => '/admin/catalog/price',
      'active' => $this instanceof PriceController,
      'visible' => Yii::app()->user->checkAccess('catalog.price.*') || Yii::app()->user->checkAccess('catalog.*'),
    ),
  )
));
?>
<?php echo $content; ?>
