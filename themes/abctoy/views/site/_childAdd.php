<?php
/* @var $child Child */
?>
<div style="vertical-align: top">
  <?php
  echo CHtml::activeLabel($child, 'gender_id', array(
    'class' => 'bold',
    'style' => 'font-size:10pt',
  ));
  ?><br>
  <?php
  echo CHtml::activeRadioButtonList($child, 'gender_id', $child->genders, array(
    'labelOptions' => array('style' => 'line-height:2')
  ));
  ?>
</div>
<div style="vertical-align: top; margin: 0 30px">
  <?php
  echo CHtml::activeLabel($child, 'birthday', array(
    'class' => 'bold',
    'style' => 'font-size:10pt',
  ));
  ?><br>
  <?php
  echo CHtml::activeTextField($child, 'birthday', array(
    'class' => 'input-text date',
    'style' => 'width:100px',
  ));
  ?>
</div>
<div style="vertical-align: top">
  <?php
  echo CHtml::activeLabel($child, 'name', array(
    'class' => 'bold',
    'style' => 'font-size:10pt',
  ));
  ?><br>
  <?php
  echo CHtml::activeTextField($child, 'name', array(
    'class' => 'input-text',
    'style' => 'width:120px',
  ));
  ?>
  <div id="save-child" class="blue bold" style="text-align: right; cursor: pointer">Сохранить</div>
</div>
<div id="error-block" style="display: block; line-height: 1">
  <?php echo CHtml::error($child, 'gender_id', array('style' => 'font-size:10pt', 'class' => 'red')); ?>
  <?php echo CHtml::error($child, 'birthday', array('style' => 'font-size:10pt', 'class' => 'red')); ?>
  <?php echo CHtml::error($child, 'name', array('style' => 'font-size:10pt', 'class' => 'red')); ?>
</div>