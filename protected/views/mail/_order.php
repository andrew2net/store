<?php
/* @var $coupon_discount float */
/* @var $order Order */

echo CHtml::tag('div', array('style' => 'font-weight:bold;margin-top:1em'), 'Информация о заказе:');
echo CHtml::tag('div', array(), 'Покупатель: ' . $order->fio);
echo CHtml::tag('div', array(), 'E-mail: ' . $order->email);
echo CHtml::tag('div', array(), 'Телефон: ' . $order->phone);
echo CHtml::tag('div', array(), 'Город: ' . $order->city);
echo CHtml::tag('div', array(), 'Адрес: ' . $order->address);
echo CHtml::tag('div', array(), 'Заказ №' . $order->id . ' от ' . Yii::app()->dateFormatter->format('dd.MM.yyyy', $order->time));
echo CHtml::tag('div', array(), 'Вид доставки: ' . $order->delivery->name);
echo CHtml::tag('div', array('style' => 'margin-bottom:1em'), 'Вид оплаты: ' . $order->payment->name);
echo CHtml::tag('table', array('cellpadding' => 4, 'style' => 'border:2px solid;border-collapse:collapse'));
echo CHtml::tag('tr', array('style' => 'border:2px solid'));
echo CHtml::tag('th', array('style' => 'border-right:1px solid'), 'Артикул');
echo CHtml::tag('th', array('style' => 'border-right:1px solid'), 'Наименование товара');
echo CHtml::tag('th', array('style' => 'border-right:1px solid'), 'Количество');
echo CHtml::tag('th', array('style' => 'border-right:1px solid'), 'Цена');
echo CHtml::tag('th', array(), 'Сумма');
echo CHtml::closeTag('tr');
$total = $order->delivery_summ;
foreach ($order->orderProducts as $value) {
  echo CHtml::tag('tr', array());
  echo CHtml::tag('td', array('style' => 'border-right:1px solid'), $value->product->article);
  echo CHtml::tag('td', array('style' => 'border-right:1px solid'), $value->product->name);
  echo CHtml::tag('td', array('style' => 'text-align:right;border-right:1px solid'), $value->quantity);
  echo CHtml::tag('td', array('style' => 'text-align:right;border-right:1px solid'), $value->price);
  $summ = $value->quantity * $value->price;
  $total += $summ;
  echo CHtml::tag('td', array('style' => 'text-align:right'), money_format('%n', $summ));
  echo CHtml::closeTag('tr');
}
echo CHtml::tag('tr', array('style' => 'border:2px solid'));
echo CHtml::tag('td', array('colspan' => 4, 'style' => 'text-align:right'), 'Стоимость доставки:');
echo CHtml::tag('td', array('style' => 'text-align:right;border-left:1px solid'), money_format('%n', $order->delivery_summ));
echo CHtml::closeTag('tr');
echo CHtml::tag('tr', array('style' => 'border:2px solid'));
if (isset($coupon_discount)) {
  $total -= $coupon_discount;
  echo CHtml::tag('td', array('colspan' => 4, 'style' => 'text-align:right'), 'Скидка по купону:');
  echo CHtml::tag('td', array('style' => 'text-align:right;border-left:1px solid'), money_format('%n', $coupon_discount));
  echo CHtml::closeTag('tr');
}
echo CHtml::tag('tr', array('style' => 'border:2px solid'));
echo CHtml::tag('td', array('colspan' => 4, 'style' => 'text-align:right'), 'Итого:');
echo CHtml::tag('td', array('style' => 'text-align:right;border-left:1px solid'), money_format('%n', $total));
echo CHtml::closeTag('tr');
echo CHtml::closeTag('table');
?>