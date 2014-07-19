<!--
 /**
  * Form for JsTreeBehavior model.
  *
  * Date: 1/29/13
  * Time: 12:00 PM
  *
  * @author: Spiros Kabasakalis <kabasakalis@gmail.com>
  * @link http://iws.kabasakalis.gr/
  * @link http://www.reverbnation.com/spiroskabasakalis
  * @copyright Copyright &copy; Spiros Kabasakalis 2013
  * @license http://opensource.org/licenses/MIT  The MIT License (MIT)
  * @version 1.0.0
  */
-->

<?php if ($model->isNewRecord) : ?>
  <h3><?php echo Yii::t('global', 'Create') ?> <?php echo Yii::t('global', $modelClassName) ?></h3>
<?php elseif (!$model->isNewRecord): ?>
  <h3><?php echo Yii::t('global', 'Update') ?> <?php echo Yii::t('global', $modelClassName) ?></h3>
<?php endif; ?>

<!--<p> <h2><?php // echo $model->name;    ?></h2><p>-->

<?php
$val_error_msg = Yii::t('global', "Error. $modelClassName was not saved.");
$val_success_message = ($model->isNewRecord) ?
    Yii::t('global', "$modelClassName has been created successfully.") :
    Yii::t('global', "$modelClassName has been updated successfully.");
?>

<div id="success-note" class="alert alert-success"
     style="display:none;">
       <?php echo $val_success_message; ?>
</div>

<div id="error-note" class="alert alert-error"
     style="display:none;">
       <?php echo $val_error_msg; ?>
</div>

<div id="ajax-form" class='form'>
  <?php
  $formId = "$modelClassName-form";

  $actionUrl = ($model->isNewRecord) ?
      (!isset($_POST['create_root']) ? CController::createUrl($this->id . '/createnode') : CController::createUrl($this->id . '/createRoot')) :
      CController::createUrl($this->id . '/updatenode');

  $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => $formId,
    //  'htmlOptions' => array('enctype' => 'multipart/form-data'),
    'action' => $actionUrl,
    // 'enableAjaxValidation'=>true,
//    'enableClientValidation' => true,
    'focus' => array($model, 'name'),
    'errorMessageCssClass' => 'alert alert-error',
    'clientOptions' => array(
      'validateOnSubmit' => true,
      'validateOnType' => false,
      'inputContainer' => '.control-group',
      'errorCssClass' => 'error',
      'successCssClass' => 'success',
      'afterValidate' => 'js:function(form,data,hasError){$.js_afterValidate(form,data,hasError);  }',
    ),
  ));
  ?>

  <?php
  echo $form->errorSummary($model, '<div style="font-weight:bold">Пожалуйста исправьте следующие ошибки:</div>', NULL, array('class' => 'alert alert-error')
  );
  echo TbHtml::tabbableTabs(array(
    array(
      'label' => 'Категория',
      'active' => TRUE,
      'content' => $this->renderPartial('_category', array('model' => $model, 'form' => $form), TRUE),
    ),
    array(
      'label' => 'Характеристики',
      'content' =>$this->renderPartial('_feature', array('model' => $model, 'form' => $form), TRUE),
    ),
  ));
  ?>
  <fieldset>

    <div class="control-group">
      <?php
      echo CHtml::button($model->isNewRecord ? Yii::t('global', 'Submit') : Yii::t('global', 'Save'), array(
        'class' => 'btn btn-primary pull-right',
        'id' => 'submit-category'
      ));
      ?>
    </div>
  </fieldset>
  <?php $this->endWidget(); ?>
</div>
<!-- form -->

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.ocupload-1.1.2.js"></script>
<script type="text/javascript">
  $('#upload1').upload({
    name: 'file',
    container: '#upload-container',
    method: 'post',
    enctype: 'multipart/form-data',
    action: '/admin/catalog/category/upload',
    onSubmit: function() {
      $('#image1').hide();
      $('#upload1').removeClass('noimg').addClass('loading');
    },
    onComplete: function(data) {
      $('#upload1').removeClass('loading');
      $('#image1').prop('src', data).show();
      $('#Category_url').prop('value', data);
    }
  });
  $('#del_img').on('click', function() {
    $('#upload1').addClass('noimg');
    $('#image1').prop('src', '');
    $('#Category_url').prop('value', '');
  });
</script>
