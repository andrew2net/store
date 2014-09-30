<?php
/* @var $this FeatureController */
/* @var $model Feature */
/* @var $values FeatureValue[] */
?>
<div class="form">
  <?php
  $form = $this->beginWidget('ext.bootstrap.widgets.TbActiveForm');
  /* @var $form TbActiveForm */
  echo $form->errorSummary($values);
  ?>
  <div style="display: table-cell; padding-right: 20px">
    <?php
    echo $form->textFieldControlGroup($model, 'name', array('span' => 6));
    echo $form->dropDownListControlGroup($model, 'type_id', $model->types);
    echo $form->textFieldControlGroup($model, 'unit', array('style'=>'width:50px'));
    echo $form->checkBoxControlGroup($model, 'search');
    ?>
  </div>
  <div id="feature-values" style="display: <?php echo ($model->type_id == 1 ? 'table-cell' : 'none'); ?>">
    <?php echo TbHtml::label('Набор значений', 'value'); ?>
    <?php echo TbHtml::linkButton('Добавить значение', array('style' => 'margin-top:3px', 'id' => 'add-value')); ?>
    <table class="table-striped">
      <tr style="display: none">
        <td></td>
        <td>
          <?php
          echo TbHtml::icon(TbHtml::ICON_TRASH, array('title' => 'Удалить'));
          ?>
        </td>
      </tr>
      <?php foreach ($values as $key => $value) { ?>
        <tr>
          <td><?php echo TbHtml::activeTextField($value, "[$key]value", array('style' => 'width:230px')); ?></td>
          <td><?php echo TbHtml::icon(TbHtml::ICON_TRASH, array('title' => 'Удалить')); ?></td>
        </tr>
      <?php } ?>
    </table>
  </div>
  <div class="form-actions">
    <?php
    echo TbHtml::linkButton('Закрыть', array('url' => '/admin/catalog/feature'));
    ?>
    <?php
    echo TbHtml::submitButton('Сохранить', array(
      'color' => TbHtml::BUTTON_COLOR_PRIMARY,
      'size' => TbHtml::BUTTON_SIZE_SMALL,
    ));
    ?>
  </div>
  <?php $this->endWidget(); ?>
</div>
<script type="text/javascript">
  $('#Feature_type_id').change(function() {
    switch (this.value) {
      case "1":
        $('#feature-values').css('display', 'table-cell');
        break;
      default:
        $('#feature-values').hide();
    }
  });

  var replace = function(str) {
    str++;
    return str;
  };
  var d = /\d+/;
  $('#add-value').click(function() {
    var lastRow = $('table tr').last();
    var row = lastRow.clone();
    var input = $(row).find('input');
    if (input.length === 0)
      $(row).find('td').first().append('<input maxlength="30" style="width:230px" type="text" value="" name="FeatureValue[0][value]" id="FeatureValue_0_value" />');
    else {
      input[0].id = input[0].id.replace(d, replace);
      input[0].name = input[0].name.replace(d, replace);
      input.val('');
    }
    lastRow.after(row);
    lastRow = $('table tr').last();
    lastRow.show();
    input.focus();
  });

  var table = $('table');

  table.on('click', '.icon-trash', function() {
    var parent = this.parentNode.parentNode;
    parent.remove();
  });

</script>