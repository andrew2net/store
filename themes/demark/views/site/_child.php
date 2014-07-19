<?php
/* @var $child Child */
?>
<div class="inline-blocks">
  <?php
  $children = Child::model()->userChildren()->findAll();
  foreach ($children as $value) {
    ?>
    <?php $this->renderPartial('_childUpdate', array('child' => $value)); ?>
  <?php } ?>
  <div class="inline-blocks" style="display: block; margin-top: 5px">
    <div class="icon-add-child" title="Добавить"></div>
    <a id="add-child-link" style="font-size: 16pt; cursor: pointer">Добавить</a>
    <div id="child-add" style="display: none; line-height: 1.5" class="inline-blocks valign-middle">
      <?php $this->renderPartial('_childAdd', array('child' => $child)); ?>
    </div>
  </div>
</div>
