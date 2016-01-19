<?php
/* @var $this CategoryController */
/* @var $model Category */
/* @var $form TbActiveForm */
?>
<p class="note"><span class="required">*</span> Обязательные поля.</p>

<div class="control-group">
  <?php echo $form->labelEx($model, 'name', array('class' => 'control-label')); ?>
  <div class="controls">
    <?php // $name = (!$model->isNewRecord) ? $model->name : '' ?>
    <?php echo $form->textField($model, 'name', array('class' => 'span4', 'size' => 30, 'maxlength' => 30)); ?>
    <p class="help-block"><?php echo $form->error($model, 'name'); ?></p>
  </div>
</div>

<div class="control-group">
    <?php echo $form->label($model, 'url', ['class' => 'control-label']); ?>
    <div class="controls">
        <?php echo $form->textField($model, 'url', ['class' => 'span4', 'size' => 30, 'maxlength' => 255]); ?>
        <p class="help-block"><?php echo $form->error($model, 'url'); ?></p>
    </div>
</div>

<div class="control-group">
  <!--            <?php // echo $form->labelEx($model, 'url', array('class' => 'control-label'));         ?>
              <div class="controls">
  <?php //  $url=(!$model->isNewRecord)?$model->url:''   ?>
  <?php // echo $form->textField($model, 'url', array('value' => $url, 'class' => 'span4', 'size' => 60, 'maxlength' => 255));  ?>
                  <p class="help-block"><?php // echo $form->error($model, 'url');        ?></p>
              </div>-->
  <input id="Category_url" name="Category[url]" type="text" value="<?php echo $model->url ?>" style="display: none">

  <div id="upload-container">
    <div id="upload1" 
         style="width: 105px; height: 105px; line-height: 105px; border: 1px solid" class="noimg">
      <img id="image1" alt="Изображение" class="img"
           style="text-align: center; max-height: 105px; max-width: 105px" src="<?php echo $model->url ?>">
    </div>
  </div>
  <?php
  echo TbHtml::button('Удалить изображение', array(
    'color' => TbHtml::BUTTON_COLOR_DEFAULT,
    'size' => TbHtml::BUTTON_SIZE_SMALL,
    'id' => 'del_img'
  ));
  ?>
</div>
<div>
  <?php echo $form->textAreaControlGroup($model, 'seo', array('span' => 4, 'rows' => 8)); ?>
</div>

<input type="hidden" name="YII_CSRF_TOKEN"
       value="<?php echo Yii::app()->request->csrfToken; ?>"/>
<input type="hidden" name= "parent_id" value="<?php echo isset($_POST['parent_id']) ? $_POST['parent_id'] : ''; ?>"  />

<?php if (!$model->isNewRecord): ?>
  <input type="hidden" name="update_id"
         value="<?php echo $model->id; ?>"/>
<?php endif; ?>
