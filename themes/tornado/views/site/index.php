<?php
/* @var $giftSelection GiftSelection */
/* @var $product Product */
?>
<?php
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile('/js/jquery.jcarousel.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/jcarousel.skeleton.js', CClientScript::POS_END);
$cs->registerScriptFile('/js/countdown.clock.js', CClientScript::POS_END);
$cs->registerScriptFile('/js/moment.min.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/slider.tooltip.js', CClientScript::POS_HEAD);
$cs->registerScriptFile($cs->getCoreScriptUrl() . '/jui/js/jquery-ui-i18n.min.js', CClientScript::POS_HEAD);
$this->pageTitle = Yii::app()->name . ' - аксессуары для мобильных устройств оптом';

//$this->renderPartial('_topmenu');
?>
<div class="container" id="page">
  <?php
  $this->renderPartial('_topblock');
  $this->renderPartial('_mainmenu');
  $this->renderPartial('_slider');
  // $this->renderPartial('_advantage'); 
//  $this->renderPartial('_giftSelection', array(
//    'giftSelection' => $giftSelection,
//    'groups' => $groups,
//  ));
  ?>
  <div class="inline-blocks" style="margin-top: 20px">
    <?php $this->renderPartial('_leftMenu'); ?>
    <div style="width: 980px">
      <?php
      echo CHtml::beginForm('', 'post', array('id' => 'item-submit'));
      echo CHtml::hiddenField('url', Yii::app()->request->url);
      $this->renderPartial('_weekDiscount');
      $this->renderPartial('_top10');
      echo CHtml::endForm();
      // $this->renderPartial('_brands');  
      $seo = Page::model()->findByAttributes(array('url' => '/'));
      if ($seo) {
        ?>
        <div style="margin: 30px 10px">
          <?php echo $seo->content; ?>
        </div>
      <?php } ?>
    </div>
  </div>
</div><!-- page -->
<?php $this->renderPartial('_footer'); ?>
