<?php
/* @var $this DefaultController */
/* @var $model Order */
?>
<table>
  <thead>
    <tr>
      <th>
        <?php echo TbHtml::label('Дата платежа', 'pay_time'); ?>
      </th>
      <th>
        <?php echo TbHtml::label('Операция №', 'pay_operation_id'); ?>
      </th>
      <th>
        <?php echo TbHtml::label('Статус', 'pay_status'); ?>
      </th>
      <th>
        <?php echo TbHtml::label('Код валюты', 'pay_currency_iso'); ?>
      </th>
      <th>
        <?php
        echo TbHtml::label('Сумма', 'pay_amount', array(
          'class' => $model->currency->getCss(),
          'style' => 'display:inline-block;position:relative',
        ));
        ?>
      </th>
      <th></th>
    </tr>
  </thead>
  <tbody id="pay-table-body">
    <?php $this->renderPartial('_payBody', array('model' => $model)); ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="3"></td>
      <td style="text-align: right">Итого:</td>
      <td id="pay-table-total">
        <?php $this->renderPartial('_payTotal', array('model' => $model)); ?>
      </td>
    </tr>
  </tfoot>
</table>
<div style="display: none"></div>