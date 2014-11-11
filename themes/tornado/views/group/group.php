<?php
/* @var $search Search */
/* @var $giftSelection GiftSelection */
/* @var $product Product */
/* @var $group Category */
/* @var $group NestedSetBehavior */
/* @var $categories[] Category */
/* @var $view string */

$groupParent = $group;
$parents= array();
$breadcrumbs[] = $group->name;
while (TRUE){
  $parents[] = $groupParent->name;
  $groupParent = $groupParent->getParent();
  if (is_null($groupParent))
    break;
  $breadcrumbs[$groupParent->name] = array('group', 'id' => $groupParent->id);
}

$this->pageTitle = Yii::app()->name . ' - ' . implode(' - ', array_reverse($parents)); //$group->name;
?>

<div class="container" id="page">
  <?php
  $breadcrumbs['Главная'] = '/';
  
  $this->widget('zii.widgets.CBreadcrumbs', array(
    'links' => array_reverse($breadcrumbs),
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
      echo CHtml::hiddenField('url', Yii::app()->request->url);

      $this->renderPartial($view, array(
        'group' => $group,
        'product' => $product,));

      echo CHtml::endForm();
      ?>
      <div style="margin: 15px"><?php echo $group->seo; ?></div>
    </div>

  </div>
</div>
