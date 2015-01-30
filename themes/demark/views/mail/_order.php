<?php

/* @var $order Order */

echo CHtml::tag('table');
echo CHtml::tag('td');
echo CHtml::tag('div', array('style' => 'font-weight:bold;margin-top:1em'), 'Информация о заказе:');
echo CHtml::tag('div', array(), 'Заказ №' . $order->id . ' от ' . Yii::app()->dateFormatter->format('dd.MM.yyyy', $order->time));
echo CHtml::tag('div', array(), 'Покупатель: ' . CHtml::encode($order->fio));
echo CHtml::tag('div', array(), 'E-mail: ' . CHtml::encode($order->email));
echo CHtml::tag('div', array(), 'Телефон: ' . CHtml::encode($order->phone));
echo CHtml::tag('div', array(), 'Город: ' . CHtml::encode($order->city));
echo CHtml::tag('div', array(), 'Адрес: ' . CHtml::encode($order->address));

switch ($order->delivery->zone_type_id) {
  case Delivery::ZONE_NRJ:
    $delivery = $order->delivery->name . ' (' . $order->delivery->transportType . ')';
    break;
  case Delivery::ZONE_CUSTOM:
    $delivery = $order->customer_delivery;
    break;
  case Delivery::ZONE_SELF:
    $delivery = $order->delivery->name . ' ' . $order->delivery->description;
    break;
  default :
    $delivery = $order->delivery->name;
}
echo CHtml::tag('div', array(), 'Вид оплаты: ' . $order->payment->name);
echo CHtml::tag('div', array('style' => 'margin-bottom:1em'), 'Вид доставки: ' . CHtml::encode($delivery));
echo CHtml::closeTag('td');
echo CHtml::tag('td', array('style' => 'vertical-align: top; padding-left: 20px'));
//echo CHtml::tag('div', array('style' => 'font-weight:bold;margin-top:1em'), 'Реквизиты для оплаты:');
//echo CHtml::tag('div', array(), 'Получатель: ' . Yii::app()->params['enterprise']['name']);
//echo CHtml::tag('div', array(), 'ИНН: ' . Yii::app()->params['enterprise']['inn'] . ' КПП:' . Yii::app()->params['enterprise']['kpp']);
//echo CHtml::tag('div', array(), 'Юр. адрес: ' . Yii::app()->params['enterprise']['legal_address']);
//echo CHtml::tag('div', array(), 'Банк получателя: ' . Yii::app()->params['enterprise']['bank']['name']);
//echo CHtml::tag('div', array(), 'БИК: ' . Yii::app()->params['enterprise']['bank']['bik']);
//echo CHtml::tag('div', array(), 'Корр. счет: ' . Yii::app()->params['enterprise']['bank']['ks']);
//echo CHtml::tag('div', array(), 'Расч. счет: ' . Yii::app()->params['enterprise']['bank']['rs']);
echo CHtml::closeTag('td');
echo CHtml::closeTag('table');

echo CHtml::tag('table', array('cellpadding' => 4, 'style' => 'border:2px solid;border-collapse:collapse'));
echo CHtml::tag('tr', array('style' => 'border:2px solid'));
echo CHtml::tag('th', array('style' => 'border-right:1px solid'), 'Артикул');
echo CHtml::tag('th', array('style' => 'border-right:1px solid'), 'Наименование товара');
echo CHtml::tag('th', array('style' => 'border-right:1px solid'), 'Количество');
echo CHtml::tag('th', array('style' => 'border-right:1px solid'), 'Цена ' . $order->currency_code);
echo CHtml::tag('th', array(), 'Сумма ' . $order->currency_code);
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
if ($order->insurance) {
  $insuranceSumm = $order->insuranceSumm;
  $total += $insuranceSumm;
  echo CHtml::tag('tr', array('style' => 'border:2px solid'));
  echo CHtml::tag('td', array('colspan' => 4, 'style' => 'text-align:right'), 'Страховка посылки:');
  echo CHtml::tag('td', array('style' => 'text-align:right;border-left:1px solid'), money_format('%n', $insuranceSumm));
  echo CHtml::closeTag('tr');
}
$couponSumm = $order->getCouponSumm();
if ($couponSumm > 0) {
  $total -= $couponSumm;
  echo CHtml::tag('tr', array('style' => 'border:2px solid'));
  echo CHtml::tag('td', array('colspan' => 4, 'style' => 'text-align:right'), 'Скидка по купону:');
  echo CHtml::tag('td', array('style' => 'text-align:right;border-left:1px solid'), money_format('%n', $couponSumm));
  echo CHtml::closeTag('tr');
}
echo CHtml::tag('tr', array('style' => 'border:2px solid'));
echo CHtml::tag('td', array('colspan' => 4, 'style' => 'text-align:right'), 'Итого:');
echo CHtml::tag('td', array('style' => 'text-align:right;border-left:1px solid'), money_format('%n', $total));
echo CHtml::closeTag('tr');
echo CHtml::closeTag('table');
?>