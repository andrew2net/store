<?php

/* @var $this Controller */

$this->widget('ext.bootstrap.widgets.TbNav', array(
  'type' => TbHtml::NAV_TYPE_TABS,
  'items' => array(
    array(
      'label' => 'Виды оплаты',
      'url' => '/admin/payments',
      'active' => $this instanceof DefaultController,
      'visible' => Yii::app()->user->checkAccess('payments.default.*') ||
      Yii::app()->user->checkAccess('payments.*'),
    ),
    array(
      'label' => 'Виды валют',
      'url' => '/admin/payments/currency',
      'active' => $this instanceof CurrencyController,
      'visible' => Yii::app()->user->checkAccess('payments.currency.*') ||
      Yii::app()->user->checkAccess('payments.*'),
    ),
    array(
      'label' => 'Курсы валют',
      'url' => '/admin/payments/currencyrate',
      'active' => $this instanceof CurrencyrateController,
      'visible' => Yii::app()->user->checkAccess('payments.currencyrate.*') ||
      Yii::app()->user->checkAccess('payments.*'),
    ),
  )
));
?>

<?php echo $content; ?>
