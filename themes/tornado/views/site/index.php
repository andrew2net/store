<?php
/* @var $product Product */
/* @var $price_type Price */
?>
<?php
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile('/js/jquery.jcarousel.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/jcarousel.skeleton.js', CClientScript::POS_END);
$cs->registerScriptFile('/js/countdown.clock.js', CClientScript::POS_END);
$cs->registerScriptFile('/js/moment.min.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/slider.tooltip.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js_plugins/coin-slider/coin-slider.min.js', CClientScript::POS_HEAD);
$cs->registerCssFile(Yii::app()->theme->baseUrl . '/css/coin-slider.css');
$cs->registerCssFile('/js_plugins/coin-slider/coin-slider-style.css');
$cs->registerScriptFile($cs->getCoreScriptUrl() . '/jui/js/jquery-ui-i18n.min.js', CClientScript::POS_HEAD);
$this->pageTitle = Yii::app()->name . ' - аксессуары для мобильных устройств оптом';

Yii::import('application.modules.admin.models.Page');
?>
<div class="container" id="page">
  <?php
  $this->renderPartial('_cslider');
  ?>
  <div class="inline-blocks" style="margin-top: 20px">
    <?php $this->renderPartial('_leftMenu'); ?>
    <div style="width: 970px">
      <?php
      echo CHtml::beginForm('', 'post', array('id' => 'item-submit'));
      echo CHtml::hiddenField('url', Yii::app()->request->url);
      $this->renderPartial('_weekDiscount');
      ?>
      <div id="top10">
        <?php $this->renderPartial('_top10', array('price_type' => $price_type)); ?>
      </div>
      <?php
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
