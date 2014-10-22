<div id="mainmenuarea" cart="<?php echo ($this instanceof CartController); ?>">
  <div id="mainmenucont" class="container">
    <div id="mainmenu" class="container">
      <div>
        <?php
        Yii::import('application.controllers.SiteController');
        Yii::import('application.modules.catalog.models.Category');
        $items = Yii::app()->db->createCommand()
                ->select("title AS label, CONCAT('/info/', url) AS url")
                ->from('{{page}}')
                ->where('menu_show>0 AND url<>"/"')
                ->order('menu_show')->queryAll();
        $items[] = array(
          'label' => 'СКАЧАТЬ ПРАЙС',
          'url' => '/uploads/tornado/price.xls',
        );
        if (!$this instanceof CartController)
          $items[] = array(
            'label' => SiteController::cartLabel(),
            'url' => array('/cart'),
            'linkOptions' => array('id' => 'shoppingCart'),
            'itemOptions' => array('class' => 'align-right icon-cart', 'style' => 'width: initial'),
          );
        $this->widget('zii.widgets.CMenu', array(
          'items' => $items,
          'itemCssClass' => 'top-menu-item',
          'firstItemCssClass' => 'top-menu-first',
          'lastItemCssClass' => 'top-menu-last',
          'encodeLabel' => FALSE,
        ));
        ?>
      </div>
    </div>
  </div>
</div>
    <?php
    Yii::import('application.modules.catalog.models.Price');
    $price = Price::getPrice();
    ?>
    <div id="price-mess" class="yellow-background red" style="display: none; position: fixed; z-index: 101; right: 50px; bottom: 50px; padding: 20px; font-size: 12pt"></div>
