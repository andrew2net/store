<div id="mainmenuarea" cart="<?php echo ($this instanceof CartController); ?>">
  <div id="mainmenucont">
    <div id="mainmenu">
      <div>
        <?php
        Yii::import('application.controllers.SiteController');
        Yii::import('application.modules.catalog.models.Category');
        $groups = Category::model()->roots()->findAll();
        $items = array();
        foreach ($groups as $group) {
          $items[] = array('label' => $group->name, 'url' => Yii::app()->createUrl('group', array('id' => $group->id)));
        }
        if (!$this instanceof CartController)
          $items[] = array(
            'label' => SiteController::cartLabel(),
            'url' => array('/cart'),
            'linkOptions' => array('id' => 'shoppingCart'),
            'itemOptions' => array('class' => 'align-right icon-cart', 'style'=>'width: initial'),
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
    var page = $('#page');
    if (menu.attr('cart'))
      return false;
    var cont = $('#mainmenucont');
    var offset = menu.offset();
    var fix = false;
    $(window).scroll(function() {
      if ($(this).scrollTop() > offset.top && !fix) {
        login_dialog.hide();
        menu.addClass('f-menu');
        page.css('margin-top', '45px');
        cont.css('box-shadow', '0 5px 5px');
        cont.animate({width: "100%"}, 300);
        fix = true;
      }
      else if ($(this).scrollTop() < offset.top && fix) {
        menu.removeClass('f-menu');
        page.css('margin-top', '10px');
        cont.css({width: '950px', 'box-shadow': 'none'});
        fix = false;
      }
    });
  });
</script>