<?php
/* @var $child Child */
?>
<div id="child-<?php echo $child->id; ?>" class="child-block inline-blocks" style="margin: 5px 0; width: 267px">
  <div class="<?php echo ($child->gender_id == 1 ? 'icon-boy' : 'icon-girl'); ?>" title="Изменить"></div>
  <div class="child-view">
    <div style="font-size: 20pt"><?php echo $child->name; ?></div>
    <div style="font-size: 14pt"><?php echo $child->birthday; ?></div>
  </div>
  <div class="child-update" style="display: none">
    <?php
    echo CHtml::activeTextField($child, "[$child->id]name", array(
      'class' => 'input-text name',
      'style' => 'width:120px',
    ));
    ?><br>
    <?php
    echo CHtml::activeTextField($child, "[$child->id]birthday", array(
      'class' => 'input-text date',
      'style' => 'width:100px',
    ));
    ?>
    <div>
      <div class="blue right update" style="text-align: right; cursor: pointer">Сохранить</div>
      <div class="red delete" style="cursor: pointer">Удалить</div>
    </div>
  </div>
  <div class="child-error" style="display: block; line-height: 1">
    <?php echo CHtml::error($child, 'birthday', array('style' => 'font-size:10pt', 'class' => 'red')); ?>
    <?php echo CHtml::error($child, 'name', array('style' => 'font-size:10pt', 'class' => 'red')); ?>
  </div>
</div>
