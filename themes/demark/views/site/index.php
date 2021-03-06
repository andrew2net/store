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
$this->pageTitle = Yii::app()->name . ' - силовая техника, садовая техника, электроинструмент, электростанции';

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
    echo CHtml::beginForm('', 'post', array('id' => 'item-submit'));
    echo CHtml::hiddenField('url', Yii::app()->request->url);
    $this->renderPartial('_weekDiscount');
    $this->renderPartial('_top10');
//  $this->renderPartial('_recommended', array('product' => $product));
    echo CHtml::endForm();
    // $this->renderPartial('_brands');  
    Yii::import('application.controllers.ProfileController');
    $profile = ProfileController::getProfile();
    $seo = Page::model()->findByAttributes(array('url' => '/', 'lang' => $profile->price_country));
    if ($seo) {
      ?>
      <div style="margin: 30px 10px">
          <?php echo $seo->content; ?>
      </div>
    <?php } ?>
</div><!-- page -->
<?php $this->renderPartial('_footer'); ?>
