<?php
/* @var $this PriceController */
/* @var $model Price */
?>

<div class="form">
  <?php
  $form = $this->beginWidget('ext.bootstrap.widgets.TbActiveForm');
  /* @var $form TbActiveForm */

  echo $form->textFieldControlGroup($model, 'name');
  echo $form->numberFieldControlGroup($model, 'summ');
  
  echo TbHtml::formActions(array(
    TbHtml::linkButton('Закрыть', array('url' => 'index')),
    TbHtml::submitButton('Сохранить', array('color' => TbHtml::BUTTON_COLOR_PRIMARY))
  ));

  $this->endWidget();
  ?>
</div>