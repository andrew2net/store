<?php
/* @var $this ProductController */
/* @var $model Product */
/* @var $form TbActiveForm */
?>
<p class="help-block"><span class="required">*</span>  Обязательные поля.</p>

<?php echo $form->errorSummary($model); ?>

<div id="upload-container" style="float: right">
  <div id="upload1" 
       style="width: 450px; height: 450px; line-height: 450px; border: 1px solid"
       class="noimg">
    <img id="img1" alt="Изображение" class="img" src="<?php echo $model->img ?>">
  </div>
</div>
<input id="Product_img" name="Product[img]" type="text" value="<?php echo $model->img ?>" style="display: none">

<div id="upload-container2" style="float: right; margin: 0 10px">
  <div id="upload2"
       style="width: 200px; height: 200px; line-height: 200px; border: 1px solid; position: relative"
       class="noimg">
    <img id="img2" alt="Миниатюра" class="img" src="<?php echo $model->small_img ?>">
  </div>
</div>
<input id="Product_small_img" name="Product[small_img]" type="text" value="<?php echo $model->small_img ?>" style="display: none">

<div>
  <?php echo $form->textFieldControlGroup($model, 'name', array('span' => 4, 'maxlength' => 255,)); ?>
</div>

<div class="inline-blocks">
  <div>
    <?php echo $form->textFieldControlGroup($model, 'article', array('span' => 3, 'maxlength' => 25)); ?>
  </div>

  <div>
    <?php
    echo $form->dropDownListControlGroup($model, 'brand_id'
        , $model->getBrandOptions(), array('span' => 3));
    ?>
  </div>
</div>
<fieldset class="inline-blocks" style="width: 350px">
  <legend>Размер и вес товара в упаковке</legend>
  <div>
    <?php echo $form->numberFieldControlGroup($model, 'weight', array('span' => 1, 'step' => '0.001', 'min' => 0, 'max' => 999.999)); ?>
  </div>
  <div style="margin: 0 10px">
    <?php echo $form->numberFieldControlGroup($model, 'length', array('span' => 1, 'step' => '0.1', 'min' => 0, 'max' => 9999.9)); ?>
  </div>
  <div>
    <?php echo $form->numberFieldControlGroup($model, 'width', array('span' => 1, 'step' => '0.1', 'min' => 0, 'max' => 9999.9)); ?>
  </div>
  <div style="margin-left: 10px">
    <?php echo $form->numberFieldControlGroup($model, 'height', array('span' => 1, 'step' => '0.1', 'min' => 0, 'max' => 9999.9)); ?>
  </div>
</fieldset>
<div class="inline-blocks">
  <div style="vertical-align: top">
    <?php echo $form->textFieldControlGroup($model, 'remainder', array('span' => 2)); ?>
  </div>

  <div style="vertical-align: top">
    <?php echo $form->textFieldControlGroup($model, 'price', array('span' => 2, 'maxlength' => 12, 'labelOptions' => array('class' => 'ruble'))); ?>
  </div>
  <?php if (Yii::app()->params['mcurrency']) { ?>
  <div style="vertical-align: top">
      <?php echo $form->textFieldControlGroup($model, 'price_tenge', array('span' => 2, 'maxlength' => 12, 'labelOptions' => array('class' => 'tenge'))); ?>
    </div>
  <?php } ?>

  <div style="top: 28px; position: relative">
    <?php echo $form->checkBoxControlGroup($model, 'show_me'); ?>
  </div>
</div>
<?php echo $form->textAreaControlGroup($model, 'description', array('rows' => 6, 'span' => 7)); ?>

<?php
echo TbHtml::button('Удалить изображение', array(
  'color' => TbHtml::BUTTON_COLOR_DEFAULT,
  'size' => TbHtml::BUTTON_SIZE_SMALL,
  'id' => 'delImg',
  'class' => 'pull-right',
));
?>

<?php
echo TbHtml::button('Удалить миниатюру', array(
  'color' => TbHtml::BUTTON_COLOR_DEFAULT,
  'size' => TbHtml::BUTTON_SIZE_SMALL,
  'id' => 'delImg2',
  'class' => 'pull-right',
  'style' => 'margin-right: 10px',
));
?>
