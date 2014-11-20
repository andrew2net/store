<?php
/* @var $data CActiveDataProvider */
/* @var $groups[] Category */
/* @var $search Search */
/* @var $brand Brand */
/* @var $group Category */
?>
<?php
$title = (isset($brand) ? ' - Бренд: ' . $brand->name : (isset($isSearch) ? ' - Поиск: ' . $search->text :
            ' - Товары со скидкой' . (isset($group) ? ': ' . $group->name : '')));
$this->pageTitle = Yii::app()->name . $title;
?>
<?php // $this->renderPartial('_topmenu');  ?>

<div class="container" id="page">
  <?php $this->renderPartial('//site/_topblock', array('search' => $search,)); ?>
  <?php
  $this->renderPartial('//site/_mainmenu', array('groups' => $groups,));
  ?>
  <div style="margin: 20px 0">
    <?php
    if (isset($brand)) {
      $notfind = 'Товар отсутствуе';
      ?>
      <span class="blue bold" style="font-size: 18pt">Бренд: </span>
      <span class="" style="font-size: 18pt"><?php echo $brand->name; ?></span>
      <?php
    }
    else {
      $notfind = 'По вашему запросу товар не найден';
      if (isset($isSearch)) {
        ?>
        <span class="blue bold" style="font-size: 18pt">Вы искали: </span>
      <?php } ?>
      <span class="" style="font-size: 18pt"><?php echo $search->text; ?></span>
    <?php } ?>
  </div>
  <div class="inline-blocks">
    <div style="width: 180px; margin-right: 6px; float: left">
      <?php
//      $this->renderPartial('_vGiftSelection', array(
//        'giftSelection' => $giftSelection,
//        'groups' => $groups,
//      ));
//      $this->renderPartial('_vAdvantage');
      ?>
    </div>
    <div>
      <div style="margin-bottom: 20px">
        <?php
        if ($data->getItemCount() > 0) {
          echo CHtml::beginForm('', 'post', array('id' => 'item-submit'));
          echo CHtml::hiddenField('url', Yii::app()->request->url);
          $this->widget('zii.widgets.CListView', array(
            'dataProvider' => $data,
            'itemView' => '//site/_item',
            'template' => '{pager}{items}{pager}',
              )
          );
          echo CHtml::endForm();
        }
        else {
          ?>
          <div class="blue bold" style="font-size: 26pt; text-align: center"><?php echo $notfind; ?></div>
          <?php
        }
        ?>
      </div>
    </div>
  </div>
</div>
<?php $this->renderPartial('//site/_footer', array('groups' => $groups)); ?>
