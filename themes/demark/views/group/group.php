<?php
/* @var $search Search */
/* @var $giftSelection GiftSelection */
/* @var $product Product */
/* @var $group Category */
/* @var $categories[] Category */
/* @var $view string */
/* @var $data CActiveDataProvider */
?>
<?php $this->pageTitle = Yii::app()->name . ' - ' . $group->name; ?>
<?php // $this->renderPartial('_topmenu');    ?>

<div class="container" id="page">
  <?php $this->renderPartial('//site/_topblock', array('search' => $search)); ?>
  <?php $this->renderPartial('//site/_mainmenu'); ?>
  <?php
  $breadcrumbs = array();
  switch ($group->level) {
    case 3:
      $g3 = $group->getParent()->getParent();
      $breadcrumbs[$g3->name] = array('/group', 'id' => $g3->id);
    case 2:
      $g2 = $group->getParent();
      $breadcrumbs[$g2->name] = array('/group', 'id' => $g2->id);
  }
  $breadcrumbs[] = $group->name;
  $this->widget('zii.widgets.CBreadcrumbs', array(
    'links' => $breadcrumbs,
    'homeLink' => FALSE,
    'separator' => ' > ',
    'htmlOptions' => array(
      'class' => 'breadcrumbs',
    )
  ));
  $currentGroup = $group;
  while (!$currentGroup->isRoot())
    $currentGroup = $currentGroup->getParent();
  echo CHtml::hiddenField('currentGroup', $currentGroup->id);
  ?>
  <div class="inline-blocks">
    <div style="width: 180px; margin-right: 6px; float: left">
      <?php
      $this->renderPartial('_menuCategory', array('group' => $group));
//      $this->renderPartial('_filter', array('group' => $group));
      ?>
    </div>

    <div style="width: 760px">

      <?php
      Yii::import('application.modules.catalog.models.Brand');
      Yii::import('application.modules.catalog.models.Product');
      Yii::import('application.modules.discount.models.Discount');
      echo CHtml::beginForm('', 'post', array('id' => 'item-submit'));
      echo CHtml::hiddenField('url', Yii::app()->request->url);
      if ($group->level < 3) {
        $discount_products = Product::model()->subCategory($group->id)->availableOnly()
                ->discountOrder()->recommended()->findAll(array('limit' => 4, 'having' => 'percent>0'));
        if (count($discount_products) > 1) {
          ?>
          <div class="inline-blocks">
            <div style="width: 100%">
              <div class="inline-blocks right">
                <div class="red bold" style="font-size: 20pt; position: relative; padding: 0 10px">Товары со скидкой</div>
              </div>
            </div>
          </div>
          <div style="margin: 20px 0">
            <?php
            foreach ($discount_products as $discount_product)
              $this->renderPartial('//site/_item', array('data' => $discount_product));
            ?>
            <div style="text-align: right; line-height: 3"><a class="red" href="<?php echo $this->createUrl('/discount_product', array('id' => $group->id)); ?>">Все товары со скидкой</a></div>
          </div>
          <?php
        }
      }
      ?>

      <?php
      $this->renderPartial($view, array(
        'group' => $group,
        'product' => $product,
//        'data' => $data,
//        'sizes' => $sizes,
        'filter' => $filter,
      ));
      ?>
      <?php echo CHtml::endForm(); ?>
    </div>

  </div>
</div>
<?php $this->renderPartial('//site/_footer'); ?>
