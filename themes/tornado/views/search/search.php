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
<?php // $this->renderPartial('_topmenu');       ?>

<div class="container" id="page">
  <?php
  $this->widget('zii.widgets.CBreadcrumbs', array(
    'links' => array(
      'Главная' => '/',
      'Поиск',
    ),
    'homeLink' => FALSE,
    'separator' => ' > ',
    'htmlOptions' => array(
      'class' => 'breadcrumbs',
    )
  ));
  ?>
  <div class="inline-blocks">
    <?php $this->renderPartial('//site/_leftMenu'); ?>
    <div>
      <div style="margin: 5px 0">
        <?php
        if (isset($brand)) {
          $notfind = 'Товар отсутствуе';
          ?>
          <span class="blue bold" style="font-size: 18pt">Бренд: </span>
          <span style="font-size: 18pt"><?php echo $brand->name; ?></span>
          <?php
        }
        else {
          $notfind = 'По вашему запросу товар не найден';
          if (isset($isSearch)) {
            ?>
            <span class="blue bold" style="font-size: 18pt">Вы искали: </span>
          <?php } ?>
          <span style="font-size: 18pt"><?php echo $search->text; ?></span>
        <?php } ?>
      </div>
      <div>
        <?php
        if ($data->getItemCount() > 0) {
          echo CHtml::beginForm('/search', 'post', array('id' => 'item-submit'));
          echo CHtml::hiddenField('url', Yii::app()->request->url);
          $this->renderPartial($view, array(
            'data' => $data,));
          echo CHtml::endForm();
        }
        else {
          ?>
          <div class="blue bold" style="font-size: 26pt; text-align: center;margin: 40px 0"><?php echo $notfind; ?></div>
          <?php
        }
        ?>
      </div>
    </div>
  </div>
</div>
