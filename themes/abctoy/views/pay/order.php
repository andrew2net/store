<?php
/* @var $this PayController */
/* @var $order Order */

$this->pageTitle = Yii::app()->name . ' - Оплата заказа';
?>
<div class="container" id="page">
  <?php $this->renderPartial('/site/_topblock'); ?>
  <?php
  if ($order) {
    Yii::import('application.modules.delivery.models.Delivery');
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.payments.models.Payment');
    ?>
    <h1 class="cufon bold blue" style="margin: 20px 0">Оплата заказа</h1>
    <h3>Информация о заказе:</h3>
    <div>Покупатель: <?php echo $order->fio; ?></div>
    <div>Телефон: <?php echo $order->phone; ?></div>
    <div>Город: <?php echo $order->city; ?></div>
    <div>Адрес: <?php echo $order->address; ?></div>
    <div>Заказ №: <?php echo $order->id; ?></div>
    <div style="margin-bottom: 10px">Вид доставки: <?php echo $order->delivery->name; ?></div>
    <table cellpadding="4" style="border-collapse: collapse">
      <tr style="background: whitesmoke">
        <th>Артикул</th>
        <th>Наименование товара</th>
        <th style="text-align: right">Количество</th>
        <th style="text-align: right">Цена</th>
        <th style="text-align: right">Сумма</th>
      </tr>
      <?php
      $total = $order->delivery_summ;
      foreach ($order->orderProducts as $product) {
        $summ = $product->quantity * $product->price;
        $total += $summ;
        ?>
        <tr>
          <td><?php echo $product->product->article; ?></td>
          <td><?php echo $product->product->name; ?></td>
          <td style="text-align: right"><?php echo $product->quantity; ?></td>
          <td style="text-align: right"><?php echo number_format($product->price, 0, '.', ' '); ?></td>
          <td style="text-align: right"><?php echo number_format($summ, 0, '.', ' '); ?></td>
        </tr>
      <?php } ?>
      <tr style="border-top: 1px solid #CCC">
        <td colspan="4" style="text-align: right">Стоимость доставки:</td>
        <td style="text-align: right"><?php echo number_format($order->delivery_summ, 0, '.', ' ') ?></td>
      </tr>
      <?php
      $coupon_discount = $order->getCouponDiscount();
      if ($coupon_discount > 0) {
        $total -= $coupon_discount;
        ?>
        <tr>
          <td colspan="4" style="text-align: right">Скидка по купону:</td>
          <td style="text-align: right"><?php echo number_format($coupon_discount, 0, '.', ' ') ?></td>
        </tr>
      <?php } ?>
      <tr style="background: whitesmoke">
        <td class="bold" colspan="4" style="text-align: right">Итого:</td>
        <td class="bold" style="text-align: right"><?php echo number_format($total, 0, '.', ' ') ?></td>
      </tr>
      <?php
      $paied = $order->paySumm;
      $to_pay = $total - $paied;
      if ($paied > 0) {
        ?>
        <tr>
          <td class="bold" colspan="4" style="text-align: right">Оплачено:</td>
          <td class="bold" style="text-align: right"><?php echo number_format($paied, 0, '.', ' ') ?></td>
        </tr>
        <tr>
          <td class="bold" colspan="4" style="text-align: right">К оплате:</td>
          <td class="bold" style="text-align: right"><?php echo number_format($to_pay, 0, '.', ' ') ?></td>
        </tr>
      <?php } ?>
    </table>
    <?php echo CHtml::beginForm('https://www.moneta.ru/assistant.htm'); ?>
    <?php
    $paysumm = number_format($to_pay, 2, '.', '');
    echo CHtml::hiddenField('MNT_ID', $order->payment->mnt_id);
    echo CHtml::hiddenField('MNT_TRANSACTION_ID', $order->id);
    echo CHtml::hiddenField('MNT_CURRENCY_CODE', 'RUB');
    echo CHtml::hiddenField('MNT_AMOUNT', $paysumm);
    echo CHtml::hiddenField('paymentSystem.unitId', '499669');
//    echo CHtml::hiddenField('MNT_TEST_MODE', '1');
    echo CHtml::hiddenField('MNT_SUCCESS_URL'
        , Yii::app()->createAbsoluteUrl('pay/success'));
    echo CHtml::hiddenField('MNT_INPROGRESS_URL'
        , Yii::app()->createAbsoluteUrl('pay/inprogress'));
    echo CHtml::hiddenField('MNT_FAIL_URL'
        , Yii::app()->createAbsoluteUrl('pay/fail'));
    echo CHtml::hiddenField('MNT_RETURN_URL'
        , Yii::app()->createAbsoluteUrl(''));
    $string_sign = $order->payment->mnt_id . $order->id
        . $paysumm . 'RUB' . '0' . $order->payment->mnt_signature;
    $signature = md5($string_sign);
    echo CHtml::hiddenField('MNT_SIGNATURE', $signature);
    ?>
    <?php if ($to_pay > 0) { ?>
      <div style="margin-top: 40px">
        <a class="submit right" href="#">
          <div class="greenbutton inline-blocks">
            <div class="left"></div>
            <div class="center">ОПЛАТИТЬ</div>
            <div class="right"></div>
          </div>
        </a>
      </div>
    <?php } ?>
    <?php echo CHtml::endForm(); ?>
  <?php } ?>
</div>