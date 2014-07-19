<?php
/* @var $this DiscountController */
/* @var $model Discount */
/* @var $form TbActiveForm */
/* @var $product Product */
?>

<div class="form">
  <?php
  $cs = Yii::app()->getClientScript();
  $cs->registerCoreScript('jquery.ui');
  $coreScriptUrl = $cs->getCoreScriptUrl();
  $cs->registerScriptFile($coreScriptUrl . '/jui/js/jquery-ui-i18n.min.js', CClientScript::POS_END);
  $cs->registerCssFile($coreScriptUrl . '/jui/css/base/jquery-ui.css');
  ?>
  <?php
  $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'discount-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => FALSE,
  ));
  ?>

  <div class="table">
    <div style="width: 14em">
      <p class="help-block"><span class="required">*</span> Обязательные поля.</p>

      <?php echo $form->errorSummary($model); ?>

      <div>
        <?php
        echo $form->dropDownListControlGroup($model, 'type_id'
            , $model->types, array('span' => 2));
        ?>
      </div>
      <div>
        <?php echo $form->textFieldControlGroup($model, 'begin_date', array('span' => 2)); ?>
      </div>
      <div>
        <?php echo $form->textFieldControlGroup($model, 'end_date', array('span' => 2)); ?>
      </div>
      <div>
        <?php
        echo $form->dropDownListControlGroup($model, 'product_id'
            , $model->productTypes, array('span' => 2));
        ?>
      </div>
      <div>
        <?php echo $form->textFieldControlGroup($model, 'percent', array('span' => 1)); ?>
      </div>
      <div>
        <?php echo $form->checkBoxControlGroup($model, 'actual', array('span' => 5)); ?>
      </div>
    </div>
    <div id="product-select">
      <div id="category" 
           style="display: <?php echo $model->product_id == 1 ? 'inherit' : 'none'; ?>">
        <?php $this->renderPartial('_category', array('model' => $model)); ?>
      </div>
      <div id="product" 
           style="display: <?php echo $model->product_id == 2 ? 'inherit' : 'none'; ?>">
        <?php $this->renderPartial('_product', array('model' => $model, 'product' => $product)); ?>
      </div>
    </div>
  </div>

  <div class="form-actions">
    <?php
    echo TbHtml::linkButton('Закрыть', array(
      'url' => '/admin/discount/discount/index'));
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
<?php
$datepicker = <<<EOD
$(function() {
  $('#Discount_begin_date').datepicker(
          $.extend({
    onClose: function(selectedDate) {
      $("#Discount_end_date").datepicker("option", "minDate", selectedDate);
    }
  },
  $.datepicker.regional['ru']));
  $("#Discount_end_date").datepicker(
          $.extend({
    onClose: function(selectedDate) {
      $("#Discount_begin_date").datepicker("option", "maxDate", selectedDate);
    }
  },
  $.datepicker.regional['ru']));
  $('#Discount_product_id').change(function(data) {
    switch ($(this).val()) {
      case "0":
        $('#product-select > div').css("display", "none");
        break;
      case "1":
        $('#category').css("display", "inherit");
        $('#product').css("display", "none");
        break;
      case "2":
        $('#category').css("display", "none");
        $('#product').css("display", "inherit");
        break;
    }

  });
});
$("#category-tree").jstree({
  "core": {"load_open": true},
  "plugins": ["themes", "html_data", "checkbox"],
  "checkbox": {
    "two_state": true,
    "real_checkboxes": true,
    "real_checkboxes_names": function(n) {
      var id = n[0].id.replace(/node_/, "");
      return ["Categories[" + id + "]"];
    }
  }
}).bind("loaded.jstree", function(event, data) {
  $(this).jstree("open_all");
});
EOD;
$dir = Yii::getPathOfAlias('ext.jstree') . DIRECTORY_SEPARATOR . 'assets';
$assets = Yii::app()->getAssetManager()->publish($dir);
$cs->registerCssFile($assets . '/themes/default/style.css');
$cs->registerScriptFile($assets . '/jquery.jstree.js', CClientScript::POS_END);
$cs->registerScript(__CLASS__ . 'jstree_category', $datepicker, CClientScript::POS_END);
//$cs->registerCoreScript('cookie');
?>