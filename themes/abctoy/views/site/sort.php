<?php
/* @var $product[] Product */
/* @var $productForm ProductForm */
/* @var $groups[] Category */
/* @var $search Search */
?>
<?php
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile('/js/slider.tooltip.js', CClientScript::POS_HEAD);

$this->pageTitle = Yii::app()->name . ' - Подбор товара';
?>
<?php $this->renderPartial('_topmenu'); ?>

<div class="container" id="page">
  <?php $this->renderPartial('_topblock'); ?>
  <?php
  $this->renderPartial('_mainmenu', array(
    'search' => $search,
    'groups' => $groups,
  ));
  ?>
  <div style="margin: 20px 0">
    <?php
    $this->renderPartial('_giftSelection', array(
      'giftSelection' => $giftSelection,
      'groups' => $groups,
    ));
    ?>
  </div>
  <div class="inline-blocks">
    <div style="width: 180px; margin-right: 6px; float: left">
      <?php $this->renderPartial('_vAdvantage'); ?>
    </div>
    <div style="width: 760px">
      <div style="margin-top: 20px">
        <?php
        if ($product->getItemCount() > 0) {
          echo CHtml::beginForm('', 'post', array('id' => 'item-submit'));
          echo CHtml::hiddenField('url', Yii::app()->request->url);
          $this->widget('zii.widgets.CListView', array(
            'dataProvider' => $product,
            'itemView' => '_item',
            'template' => '{pager}{items}{pager}',
              )
          );
          echo CHtml::endForm();
        }
        else {
          ?>
          <div class="cufon blue bold" style="font-size: 26pt; text-align: center;margin-top: 40px">По заданным параметрам товар не найден</div>
          <?php
        }
        ?>
      </div>
    </div>
  </div>
</div>
<?php $this->renderPartial('_footer', array('groups' => $groups)); ?>
<?php $this->renderPartial('_addProductModal'); ?>
