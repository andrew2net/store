<?php
/* @var $this BrandController */
/* @var $model Brand */
/* @var $form CActiveForm */
?>

<div class="form">

  <?php
  $form = $this->beginWidget('ext.bootstrap.widgets.TbActiveForm', array(
    'id' => 'brand-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
  ));
  ?>

  <p class="note"><span class="required">*</span> Обязательные поля.</p>

  <?php echo $form->errorSummary($model); ?>

  <div class="row">
    <?php
    echo $form->textFieldControlGroup($model, 'name'
        , array('span' => 5, 'maxlength' => 255));
    ?>
  </div>

  <input id="Brand_img" name="Brand[img]" type="text" value="<?php echo $model->img ?>" style="display: none">

  <div id="upload-container">
    <div id="upload1" 
         style="width: 200px; height: 200px; line-height: 200px; border: 1px solid" class="noimg">
      <img id="logo1" alt="Логотип" height="200px" width="200px" class="img"
           style="text-align: center" src="<?php echo $model->img ?>">
    </div>
  </div>
  <?php
  echo TbHtml::button('Удалить логотип', array(
    'color' => TbHtml::BUTTON_COLOR_DEFAULT,
    'size' => TbHtml::BUTTON_SIZE_SMALL,
    'id' => 'delLogo'
  ));
  ?>

  <div class="form-actions">
    <?php
    echo TbHtml::linkButton('Закрыть', array(
      'url' => '/admin/catalog/brand/index'));
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

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.ocupload-1.1.2.js"></script>
<script type="text/javascript">
  $('#upload1').upload({
    name: 'file',
    container: '#upload-container',
    method: 'post',
    enctype: 'multipart/form-data',
    action: '/admin/catalog/brand/upload',
    onSubmit: function() {
      $('#logo1').hide();
      $('#upload1').removeClass('noimg').addClass('loading');
    },
    onComplete: function(data) {
      $('#upload1').removeClass('loading');
      $('#logo1').prop('src', data).show();
      $('#Brand_img').prop('value', data);
    }
  });
  $('#delLogo').on('click', function() {
    $('#upload1').addClass('noimg');
    $('#logo1').prop('src', '');
    $('#Brand_img').prop('value', '');
  });
</script>
