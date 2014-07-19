<?php
/* @var $this RegionController */
/* @var $model Region */
/* @var $delivery array */
?>

<div class="form">

  <?php
  $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'region-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
  ));
  /* @var $form TbActiveForm */
  Yii::import('application.controllers.ProfileController');
  ?>

  <p class="help-block"><span class="required">*</span> Обязательные поля.</p>

  <?php
  $models = array($model);
  foreach ($delivery as $value) {
    $models[] = $value['regionDelivery'];
    foreach ($value['data'] as $val)
      $models[] = $val;
  }
  echo $form->errorSummary($models);
  ?>

  <div class="inline-blocks">

    <?php echo $form->dropDownListControlGroup($model, 'type_id', $model->types); ?>
    <div style="margin: 0 20px">
      <?php echo $form->dropDownListControlGroup($model, 'country_code', ProfileController::getCountries()); ?>
    </div>

    <div id="region-name" class="control-group" <?php echo ($model->type_id == 1 ? '' : 'style="display: none"'); ?>>
      <?php echo TbHtml::label('Населенный пункт', 'city_name', array('required' => TRUE)); ?>
      <?php
      $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
        'id' => 'region_name',
        'name' => 'name',
        'model' => $model,
        'attribute' => 'name',
        'source' => new CJavaScriptExpression('function (request, response){citySuggest(request, response);}'),
      ));
      ?>
    </div>
  </div>
  <div class="control-group">
<!--    <table>
      <th colspan="2">
    <?php // echo TbHtml::label('Способы доставки', 'delivery');  ?>
      </th>-->
    <?php
    $tabs = array();
    foreach ($delivery as $delivery_id => $value) {
      $tabs[] = array(
        'label' => $value['name'],
        'id' => 'delivery_' . $delivery_id,
        'content' => $this->renderPartial('_rates', array(
          'delivery' => $value,
          'delivery_id' => $delivery_id,
          'form' => $form,
            ), TRUE),
      );
    }
    if (is_array($tabs) && isset($tabs[0]))
      $tabs[0]['active'] = true;
    $this->widget('ext.bootstrap.widgets.TbTabs', array(
      'tabs' => $tabs,
      'placement' => 'left',
//      'htmlOptions' => array('style' => 'height: 520px')
    ));
    ?>
<!--        <tr>
          <td style="width: 1em">
    <?php // echo TbHtml::checkBox('delivery[' . $value->id . ']', $checked);  ?>
          </td>
          <td><?php // echo $value->name;       ?></td>
        </tr>-->
    <?php //      }       ?>
    <!--</table>-->
  </div>
  <div class="form-actions">
    <?php
    echo TbHtml::linkButton('Закрыть', array(
      'url' => '/admin/delivery/region/index'));
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
  $('#Region_type_id').change(function() {
    if (this.value == 0)
      $('#region-name').hide();
    else
      $('#region-name').show();

  });
  function citySuggest(request, response) {
    $.get("/site/suggestcity",
            {country: $("#Region_country_code").val(), term: request.term},
    function(data) {
      var result = $.parseJSON(data);
      response(result);
    });
  }

  $('.add-rate').click(function() {
    var tr = $(this).parent().parent().parent().parent().find('tr').last();
    var trNew = tr.clone();
    var trHtml = trNew.html();
    trHtml = trHtml.replace(/(?:\[|_)\d+(?:\]\[|_)(n|\d+)/g, function(str, n) {
      if (n === 'n')
        n = 0;
      else
        n++;
      var strNew = str.replace(/(?:\d+|n)$/, n);
      return strNew;
    });
    trNew.html(trHtml);
    tr.after(trNew);
    var input = trNew.find('input');
    input.attr('disabled', false);
    input.val('');
    trNew.show();
    input.first().focus();
  });

  $('table').on('click', '.icon-trash', function() {
    $(this).parent().parent().parent().remove();
  });

  $('#Region_country_code').change(function() {
    $.post('/admin/delivery/region/zones', {'code': this.value}, function(data) {
      var result = $.parseJSON(data);
      $.each(result, function(index, value) {
        $('#delivery_' + index).find('.select-zone').html(value);
      });
    });
  });
</script>