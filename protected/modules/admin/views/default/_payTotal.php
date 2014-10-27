<?php
/* @var $this DefaultController */
/* @var $model Order */
echo TbHtml::openTag('div', array('name' => 'pay_summ', 'class' => 'display-field'));
echo number_format($model->paySumm + $model->authSumm, 2, '.', ' ');
echo TbHtml::closeTag('div');
?>
