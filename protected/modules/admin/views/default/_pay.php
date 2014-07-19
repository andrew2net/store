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
      <?php echo TbHtml::label('Номер операции', 'pay_mnt_operation_id'); ?>
    </th>
    <th>
      <?php echo TbHtml::label('Платежная система', 'pay_pay_system_id'); ?>
    </th>
    <th>
      <?php echo TbHtml::label('Корр.счет', 'pay_mnt_corr_acc'); ?>
    </th>
    <th>
      <?php echo TbHtml::label('Сумма', 'pay_mnt_amount'); ?>
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
        echo TbHtml::tag('div', array('name' => 'pay_mnt_operation_id', 'class' => 'display-field'));
        echo $item->mnt_operation_id;
        echo TbHtml::closeTag('div');
        ?>
      </td>
      <td>
        <?php
        echo TbHtml::tag('div', array('name' => 'pay_pay_system_id', 'class' => 'display-field'));
        echo $item->pay_system_id;
        echo TbHtml::closeTag('div');
        ?>
      </td>
      <td>
        <?php
        echo TbHtml::tag('div', array('name' => 'pay_mnt_corr_acc', 'class' => 'display-field'));
        echo $item->mnt_corr_acc;
        echo TbHtml::closeTag('div');
        ?>
      </td>
      <td>
        <?php
        echo TbHtml::tag('div', array('name' => 'pay_mnt_amount', 'class' => 'display-field'));
        echo $item->mnt_amount;
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