<?php
/* @var $search Search */
/* @var $giftSelection GiftSelection */
/* @var $groups[] Category */
/* @var $product Product */
/* @var $group Category */
/* @var $categories[] Category */
?>
<?php $this->pageTitle = Yii::app()->name . ' - ' . $group->name; ?>
<?php $this->renderPartial('_topmenu'); ?>

<div class="container" id="page">
  <?php $this->renderPartial('_topblock'); ?>
  <?php
  $this->renderPartial('_mainmenu', array(
    'search' => $search,
    'groups' => $groups,
  ));
  ?>
  <?php
  $breadcrumbs = array();
  switch ($group->level) {
    case 3:
      $g3 = $group->getParent()->getParent();
      $breadcrumbs[$g3->name] = array('group', 'id' => $g3->id);
    case 2:
      $g2 = $group->getParent();
      $breadcrumbs[$g2->name] = array('group', 'id' => $g2->id);
  }
  $breadcrumbs[] = $group->name;
  $this->widget('zii.widgets.CBreadcrumbs', array(
    'links' => $breadcrumbs,
    'homeLink' => FALSE,
    'separator' => ' / ',
    'htmlOptions' => array(
      'class' => 'cufon green bold breadcrumbs',
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
      $this->renderPartial('_vGiftSelection', array(
        'giftSelection' => $giftSelection,
        'groups' => $groups,
      ));
      $this->renderPartial('_vAdvantage');
      ?>
    </div>

    <div style="width: 760px">

      <?php
      Yii::import('application.modules.catalog.models.Brand');
      Yii::import('application.modules.catalog.models.Product');
      Yii::import('application.modules.discount.models.Discount');
      echo CHtml::beginForm('', 'post', array('id' => 'item-submit'));
      echo CHtml::hiddenField('url', Yii::app()->request->url); //, array('id' => 'currentGroup'));
//      echo CHtml::hiddenField('currentCategory', $group->id); //, array('id' => 'currentGroup'));
      if ($group->level < 3) {
        $discount_products = Product::model()->subCategory($group->id)->availableOnly()
                ->discountOrder()->recommended()->findAll(array('limit' => 4, 'having' => 'percent>0'));
        if (count($discount_products) > 1) {
          ?>
          <div class="inline-blocks">
            <div style="width: 100%">
              <div class="inline-blocks right">
                <div class="icon-dicount"></div>
                <div class="cufon red bold" style="font-size: 20pt; position: relative; padding: 0 10px">Товары со скидкой</div>
              </div>
            </div>
          </div>
          <div style="margin: 20px 0">
            <?php
            foreach ($discount_products as $discount_product)
              $this->renderPartial('_item', array('data' => $discount_product));
            ?>
            <div style="text-align: right; line-height: 3"><a class="red" href="<?php echo $this->createUrl('discount', array('id'=>$group->id)); ?>">Все товары со скидкой</a></div>
          </div>
          <?php
        }
      }
      ?>

      <?php
      if ($group->level > 1) {
        $pagination = array();
        if (isset($page))
          $pagination['currentPage'] = $page;
        if ($group->level > 2){
          $pagination['pageSize'] = Yii::app()->request->getQuery('size', 16);
          $sizes = array(16, 28, 40);
        }
        else{
          $pagination['pageSize'] = Yii::app()->request->getQuery('size', 12);
          $sizes = array(12, 24, 36);
        }
        $data = Product::model()->searchCategory($group->id);
        $data->setPagination($pagination);
        if ($data->getItemCount() > 0) {
          $widget = $this->widget('ListView', array(
            'dataProvider' => $data,
            'itemView' => '_item',
            'cssFile'=>'/themes/abctoy/css/listview.css',
            'template' => "{pager}{sizer}{sorter}{items}{pager}",
            'sorterHeader' => 'Сортировать:',
            'sortableAttributes' => array('price'),
            'htmlOptions' => array('style' => 'margin-top:30px'),
            'viewData'=>array('sizes'=>$sizes),
              )
          );
        }
        else {
          ?>
          <div class="cufon blue bold" style="font-size: 26pt; text-align: center;margin-top: 40px">Товар отсутствуе</div>
          <?php
        }
      }
      else {
        $this->renderPartial('_recommended', array(
          'group' => $group,
          'product' => $product,));
      }
      ?>
      <?php echo CHtml::endForm(); ?>
          <div style="margin: 15px"><?php echo $group->seo; ?></div>
    </div>

  </div>
</div>
<?php $this->renderPartial('_footer', array('groups' => $groups)); ?>
<?php $this->renderPartial('_addProductModal'); ?>
