<?php
/* @var $this DefaultController */
/* @var $model Order */
foreach ($model->pay as $item) {
  ?>
  <tr>
    <td>
      <?php
      echo TbHtml::openTag('div', array('name' => 'pay_time', 'class' => 'display-field'));
      echo Yii::app()->dateFormatter->format('dd.MM.yyyy HH:mm:ss', $item->time);
      echo TbHtml::closeTag('div');
      ?>
    </td>
    <td>
      <?php
      echo TbHtml::openTag('div', array('name' => 'pay_operation_id', 'class' => 'display-field'));
      echo $item->operation_id;
      echo TbHtml::hiddenField('pay_id', $item->id);
      echo TbHtml::closeTag('div');
      ?>
    </td>
    <td>
      <?php
      echo TbHtml::openTag('div', array('name' => 'pay_status', 'class' => 'display-field'));
      echo $item->status;
      echo TbHtml::closeTag('div');
      ?>
    </td>
    <td>
      <?php
      echo TbHtml::openTag('div', array('name' => 'pay_currency_iso', 'class' => 'display-field'));
      echo $item->currency_iso;
      echo TbHtml::closeTag('div');
      ?>
    </td>
    <td>
      <?php
      echo TbHtml::openTag('div', array('name' => 'pay_amount', 'class' => 'display-field'));
      echo number_format($item->currency_amount, 2, '.', ' ');
      echo TbHtml::closeTag('div');
      ?>
    </td>
    <td>
      <?php
      $payActions[] = array('label' => 'Данные транзакции', 'url' => '/admin/default/payData');
      if ($item->status_id < 5)
        $payActions[] = array('label' => 'Обновить статус', 'url' => '/admin/default/payGetStatus');
      if ($item->status_id == Pay::AUTHORISED) {
        $payActions[] = array('label' => 'Звершить транзакцию', 'url' => '/admin/default/payCompleteDialog');
        if ($model->status_id == Order::STATUS_PAID)
          $payActions[] = array('label' => 'Отменить транзакцию', 'url' => '/admin/default/payCancelDialog');
      }
      if ($model->payment->type_id == Payment::TYPE_LIQPAY) {
        if ($model->status_id == Order::STATUS_PAID && $item->status_id == Pay::PAID) {
          $payActions[] = array('label' => 'Возврат средств', 'url' => '/admin/default/payRefundDialog');
        }
      }
      echo TbHtml::buttonDropdown('Действие', $payActions, array('class' => 'pay-action'));
      ?>
    </td>
  </tr>
<?php } ?>
