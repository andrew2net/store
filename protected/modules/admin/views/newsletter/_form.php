<?php
/* @var $this NewsletterController */
/* @var $model Newsletter */
/* @var $form TbActiveForm */
/* @var $blocks NewsletterBlock[] */

$cs = Yii::app()->clientScript;
$cs->registerCssFile('/js_plugins/jQueryFileUpload/css/jquery.fileupload.css');
$cs->registerCoreScript('jquery.ui');
//$cs->registerScriptFile('/js_plugins/jQueryFileUpload/js/vendor/jquery.ui.widget.js');
$cs->registerScriptFile('//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js');
$cs->registerScriptFile('//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js');
$cs->registerScriptFile('/js_plugins/jQueryFileUpload/js/jquery.iframe-transport.js');
$cs->registerScriptFile('/js_plugins/jQueryFileUpload/js/jquery.fileupload.js');
$cs->registerScriptFile('/js_plugins/jQueryFileUpload/js/jquery.fileupload-process.js');
$cs->registerScriptFile('/js_plugins/jQueryFileUpload/js/jquery.fileupload-image.js');
$cs->registerScriptFile('/js_plugins/jQueryFileUpload/js/jquery.fileupload-validate.js');
$tiny_mce_path = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.tinymce.vendors.tinymce.jscripts.tiny_mce'));
$cs->registerScriptFile($tiny_mce_path . '/tiny_mce.js');
$cs->registerScriptFile($tiny_mce_path . '/jquery.tinymce.js');
$cs->registerScriptFile('/js/newsLetter.js');
?>

<div class="form">

    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
      'id' => 'newsletter-form',
      // Please note: When you enable ajax validation, make sure the corresponding
      // controller action is handling ajax validation correctly.
      // There is a call to performAjaxValidation() commented in generated controller code.
      // See class documentation of CActiveForm for details on this.
      'enableAjaxValidation' => false,
      'htmlOptions' => ['enctype' => 'multipart/form-data'],
    ));
    ?>

    <p class="help-block">Обязательные поля <span class="required">*</span></p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldControlGroup($model, 'subject', array('span' => 8, 'maxlength' => 255)); ?>
    <?php echo $form->checkBoxControlGroup($model, 'send_price'); ?>

    <div id="blocks">
        <?php foreach ($blocks as $key => $value) { ?>
          <div class="inline-blocks">
              <?php echo $form->textAreaControlGroup($value, "[$key]text", ['span' => 6, 'rows' => 6]); ?>
              <div>
                  <span class="btn remove-img"<?php echo ($value->image ? ' style="display:inline-block"' : ''); ?>>Удалить изображение..</span>
                  <span class="btn fileinput-button"<?php echo ($value->image ? ' style="display:none"' : ''); ?>>
                      <span>Добавить изображение..</span>
                      <?php echo $form->fileField($value, "[$key]image"); ?>
                  </span>
                  <div class="image-thumbnail">
                      <?php if ($value->image) { ?>
                        <img src="/images/<?php echo Yii::app()->params['img_storage'] . '/newsletter/' . $value->image; ?>">
                      <?php } else { ?>
                        Перенесите файл сюда
                      <?php } ?>
                  </div>
              </div>
              <span class="btn remove-block"<?php echo (count($blocks) > 1 ? '' : 'style="display:none"'); ?>>Удалить блок</span>
          </div>
        <?php } ?>
    </div>
    <?php echo TbHtml::linkButton('Добавить блок', ['id' => 'add-block', 'style' => 'margin-top:15px']); ?>
    <div class="form-actions">
        <?php
        echo TbHtml::linkButton('Закрыть', array(
          'url' => '/admin/newsletter'));
        ?>
        <?php
        echo TbHtml::submitButton('Сохранить', array(
          'color' => TbHtml::BUTTON_COLOR_PRIMARY,
        ));
        ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
<div class="loading"></div>