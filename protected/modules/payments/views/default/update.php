<?php
/* @var $this PaymentController */
/* @var $payment Payment */

$this->breadcrumbs = array(
  'Виды оплаты' => array('index'),
  'Изменение',
);
?>
<h3>Изменение вида оплаты <i><?php echo $payment->name; ?></i></h3>

<?php $this->renderPartial('_form', array('payment' => $payment)); ?>