<?php
/* @var $this DefaultController */
/* @var $model Order */
?>
<table>
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
      <?php echo TbHtml::label('Сумма', 'pay_amount', array('style' => 'display:inline-block'))
      . $model->currency->class;
      ?>
    </th>
  </tr>
  <?php
  foreach ($model->pay as $item) {
    ?>
    <tr>
      <td>
        <?php
        echo TbHtml::tag('div', array('name' => 'pay_time', 'class' => 'display-field'));
        echo Yii::app()->dateFormatter->format('dd.MM.yyyy HH:mm:ss', $item->time);
        echo TbHtml::closeTag('div');
        ?>
      </td>
      <td>
        <?php
        echo TbHtml::tag('div', array('name' => 'pay_operation_id', 'class' => 'display-field'));
        echo $item->operation_id;
        echo TbHtml::closeTag('div');
        ?>
      </td>
      <td>
        <?php
        echo TbHtml::tag('div', array('name' => 'pay_status', 'class' => 'display-field'));
        echo $item->status;
        echo TbHtml::closeTag('div');
        ?>
      </td>
      <td>
        <?php
        echo TbHtml::tag('div', array('name' => 'pay_currency_iso', 'class' => 'display-field'));
        echo $item->currency_iso;
        echo TbHtml::closeTag('div');
        ?>
      </td>
      <td>
        <?php
        echo TbHtml::tag('div', array('name' => 'pay_amount', 'class' => 'display-field'));
        echo $item->currency_amount;
        echo TbHtml::closeTag('div');
        ?>
      </td>
    </tr>
<?php } ?>
  <tr>
    <td colspan="3"></td>
    <td style="text-align: right">Итого:</td>
    <td>
      <?php
      echo TbHtml::tag('div', array('name' => 'pay_summ', 'class' => 'display-field'));
      echo $model->paySumm;
      echo TbHtml::closeTag('div');
      ?>
    </td>
  </tr>
</table>