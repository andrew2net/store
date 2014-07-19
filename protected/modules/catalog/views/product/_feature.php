<?php
/* @var $this ProductController */
/* @var $model Product */
/* @var $form TbActiveForm */
/* @var $value Feature */
/* @var $feature Array */

?>
<table class="table-striped">
  <tr>
    <th>Наименование</th><th>Значение</th>
  </tr>
  <?php foreach ($feature as $key => $value) { ?>
    <tr>
      <td><?php echo $value['feature']->name; ?></td>
      <td>
        <?php
        if ($value['value'] instanceof ProductFeature)
          echo TbHtml::activeTextField($value['value'], "[$key]value");
        elseif ($value['value'] instanceof ProductFeatureValue)
          echo TbHtml::activeDropDownList($value['value'], "[$key]value_id", $value['feature']->values, array('empty' => 'Выберете значение'));
        elseif ($value['value'] instanceof ProductFeatureRange) {
          echo TbHtml::activeTextField($value['value'], "[$key]from", array('style' => 'width:89px'));
          echo ' - ' . TbHtml::activeTextField($value['value'], "[$key]to", array('style' => 'width:89px'));
        }
        echo ' ' . $value['feature']->unit;
        ?>
      </td>
    </tr>
  <?php } ?>
</table>