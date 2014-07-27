<?php
/* @var $search Search */
/* @var $giftSelection GiftSelection */
/* @var $product Product */
/* @var $group Category */
/* @var $categories[] Category */
?>
<?php $this->pageTitle = Yii::app()->name . ' - ' . $group->name; ?>

<div class="container" id="page">
  <?php
  $breadcrumbs = array('Главная' => '/');
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
  <div class="inline-blocks" style="margin-top: 20px">
    <?php $this->renderPartial('//site/_leftMenu', array('group' => $group)); ?>

    <div style="padding-left: 15px">
      <?php
      Yii::import('application.modules.catalog.models.Brand');
      Yii::import('application.modules.catalog.models.Product');
      Yii::import('application.modules.discount.models.Discount');

      echo CHtml::beginForm('', 'post', array('id' => 'item-submit'));
      echo CHtml::hiddenField('url', Yii::app()->request->url); //, array('id' => 'currentGroup'));

      $this->renderPartial('//site/_items_inline', array(
        'group' => $group,
        'product' => $product,));

      echo CHtml::endForm();
      ?>
      <div style="margin: 15px"><?php echo $group->seo; ?></div>
    </div>

  </div>
</div>
