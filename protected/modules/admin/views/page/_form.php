<?php
/* @var $this PageController */
/* @var $model Page */
/* @var $form TbActiveForm */

$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery.ui');
$tiny_mce_path = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.tinymce.vendors.tinymce.jscripts.tiny_mce'));
$cs->registerScriptFile($tiny_mce_path . '/tiny_mce.js');
$cs->registerScriptFile($tiny_mce_path . '/jquery.tinymce.js');
?>

<div class="form">

    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
      'id' => 'page-form',
      // Please note: When you enable ajax validation, make sure the corresponding
      // controller action is handling ajax validation correctly.
      // There is a call to performAjaxValidation() commented in generated controller code.
      // See class documentation of CActiveForm for details on this.
      'enableAjaxValidation' => false,
    ));
    ?>

    <p class="help-block">Обязательные поля <span class="required">*</span></p>

    <?php echo $form->errorSummary($model); ?>

    <div class="inline-blocks">
        <div>
            <?php echo $form->textFieldControlGroup($model, 'url', array('span' => 5, 'maxlength' => 255)); ?>
        </div>
        <div class="table-cell">
            <?php echo $form->textFieldControlGroup($model, 'menu_show', array('span' => 1, 'maxlength' => 3)); ?>
        </div>
    </div>

    <div class="inline-blocks">
        <?php echo $form->textFieldControlGroup($model, 'title', array('span' => 5, 'maxlength' => 255)); ?>
        <?php echo $form->dropDownListControlGroup($model, 'lang', $model->getLicales()); ?>
    </div>

    <?php // echo CHtml::activeLabel($model, 'content')// echo $form->textAreaControlGroup($model,'content',array('rows'=>6,'span'=>8)); ?>
    <?php echo $form->textAreaControlGroup($model, 'content', ['rows' => 20,]); ?>

    <div class="form-actions">
        <?php
        echo TbHtml::linkButton('Закрыть', array(
          'url' => '/admin/page'));
        ?>
        <?php
        echo TbHtml::submitButton('Сохранить', array(
          'color' => TbHtml::BUTTON_COLOR_PRIMARY,
          'size' => TbHtml::BUTTON_SIZE_SMALL,
        ));
        ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
<script type="text/javascript">
  $(function () {
      tinyMCE.init({
          mode: 'textareas',
          elements: this.id,
          theme: "advanced",
          language: 'ru',
          plugins: "inlinepopups,fullscreen,advimage",
          dialog_type: "modal",
          convert_urls: true,
          relative_urls: false,
          remove_script_host: true,
          forced_root_block: false,
          file_browser_callback: function (field_name, url, type, win) {
              var elfinder_url = '/admin/elfinder/elfinder';    // use an absolute path!
              tinyMCE.activeEditor.windowManager.open({
                  file: elfinder_url,
                  title: 'elFinder 2.0',
                  width: 900,
                  height: 402,
                  resizable: 'yes',
                  inline: 'yes', // This parameter only has an effect if you use the inlinepopups plugin!
                  popup_css: false, // Disable TinyMCE's default popup CSS
                  close_previous: 'no'
              }, {
                  window: win,
                  input: field_name
              });
              return false;
          },
          theme_advanced_buttons1: 'formatselect,fontselect,fontsizeselect,forecolor,backcolor,bold,italic,underline,strikethrough,sub,sup,charmap,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,outdent,indent,undo,redo,link,unlink,cleanup,hr,image,code,fullscreen'
      });
//      tinyMCE.execCommand('mceAddControl', false, this.id);
  });
</script>