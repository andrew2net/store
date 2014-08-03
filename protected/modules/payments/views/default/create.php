<?php
/* @var $this PaymentController */
/* @var $payment Payment */

$this->breadcrumbs = array(
  'Виды оплаты' => array('index'),
  'Новый',
);
?>
<h3>Новый вид оплаты</h3>

<?php $this->renderPartial('_form', array('payment' => $payment)); ?>
