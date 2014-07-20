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
<script type="text/javascript">
  $().ready(function($) {
    var menu = $('#mainmenuarea');
    if (menu.attr('cart'))
      return false;
//    var cont = $('#mainmenucont');
    var page = $('#page');
    var offset = menu.offset();
    var fix = false;
    $(window).scroll(function() {
      if ($(this).scrollTop() > offset.top && !fix) {
        login_dialog.hide();
        menu.addClass('f-menu');
        page.css('margin-top', '50px')
//        cont.css('box-shadow', '0 5px 5px');
//        cont.animate({width: "100%"}, 300);
        fix = true;
      }
      else if ($(this).scrollTop() < offset.top && fix) {
        menu.removeClass('f-menu');
        page.css('margin-top', '10px')
//        cont.css({width: '950px', 'box-shadow': 'none'});
        fix = false;
      }
    });
  });
</script>