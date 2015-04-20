<?php
/* @var $this PayController */
/* @var $order Order */
/* @var $coupon_discount float */
/* @var $total float */
/* @var $paied float */
/* @var $to_pay float */
/* @var $errors string */

$this->pageTitle = Yii::app()->name . ' - Информация о заказе';
?>
<div class="container" id="page">
    <?php $this->renderPartial('/site/_topblock'); ?>
    <?php $this->renderPartial('/site/_mainmenu'); ?>
    <?php
    Yii::import('application.modules.delivery.models.Delivery');
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.payments.models.Payment');
    ?>
    <?php if ($errors) echo CHtml::tag('p', array('class' => 'red', $errors)); ?>
    <h1 class="bold blue" style="margin: 20px 0">Информация о заказе:</h1>
    <div><b>Заказ №:</b> <?php echo $order->id; ?> <b>от</b> <?php echo Yii::app()->dateFormatter->format('DD.MM.yyyy', $order->time); ?></div>
    <div><b>Статус заказа:</b> <?php echo $order->status; ?></div>
    <div><b>Покупатель:</b> <?php echo $order->fio; ?></div>
    <div><b>Телефон:</b> <?php echo $order->phone; ?></div>
    <div><b>Страна:</b> <?php echo ProfileController::getCountryName($order->country_code); ?></div>
    <div><b>Почтовый индекс:</b> <?php echo $order->post_code; ?></div>
    <div><b>Город:</b> <?php echo $order->city; ?></div>
    <div><b>Адрес:</b> <?php echo $order->address; ?></div>
    <div><b>Вид оплаты:</b> <?php echo $order->payment->name; ?></div>
    <div style="margin-bottom: 10px"><b>Вид доставки:</b> <?php
        echo $order->delivery->name .
        ($order->delivery->zone_type_id == Delivery::ZONE_SELF ? ' ' . $order->delivery->description : '');
        ?></div>
    <table cellpadding="4" style="border-collapse: collapse">
        <tr style="background: whitesmoke">
            <th>Артикул</th>
            <th>Наименование товара</th>
            <th style="text-align: right">Количество</th>
            <th style="text-align: right">Цена <?php echo $order->currency->class; ?></th>
            <th style="text-align: right">Сумма <?php echo $order->currency->class; ?></th>
        </tr>
        <?php
        foreach ($order->orderProducts as $product) {
          $summ = $product->quantity * $product->price;
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
        if ($order->insurance) {
          $insurance = $order->insuranceSumm;
//          $total += $insurance;
          ?>
          <tr>
              <td colspan="4" style="text-align: right">Страховка посылки:</td>
              <td style="text-align: right"><?php echo number_format($insurance, 0, '.', ' '); ?></td>
          </tr>
        <?php } ?>
        <?php
        if ($coupon_discount > 0) {
          ?>
          <tr>
              <td colspan="4" style="text-align: right">Скидка по купону:</td>
              <td style="text-align: right"><?php echo number_format($coupon_discount, 0, '.', ' ') ?></td>
          </tr>
          <?php
        }

        $totalRU = $total;
        if ($order->currency_code != 'RUB') {
          $currencyTo = Currency::model()->findByAttributes(['code' => 'RUB']);
          $currencyTo->convert($order->currency->code, $totalRU);
        }
        ?>
        <tr style="background: whitesmoke">
            <td class="bold" colspan="4" style="text-align: right">Итого:</td>
            <td id="order-total" data-summ="<?php echo $totalRU; ?>" class="bold" style="text-align: right"><?php echo number_format($total, 0, '.', ' ') ?></td>
        </tr>
        <?php
        if ($paied > 0) {
          ?>
          <tr>
              <td class="bold" colspan="4" style="text-align: right">Оплачено:</td>
              <td class="bold" style="text-align: right"><?php echo number_format($paied, 0, '.', ' ') ?></td>
          </tr>
          <?php if ($to_pay > 0) { ?>
            <tr>
                <td class="bold" colspan="4" style="text-align: right">К оплате:</td>
                <td class="bold" style="text-align: right"><?php echo number_format($to_pay, 0, '.', ' ') ?></td>
            </tr>
          <?php } ?>
        <?php } ?>
    </table>
    <?php
    if ($to_pay > 0 && $order->status_id == Order::STATUS_WAITING_FOR_PAY && $order->payment->type_id != Payment::TYPE_CAHSH) {
      echo CHtml::beginForm($order->payment->getActionUrl());
      $params = $order->payment->getParams($order);
      foreach ($params as $key => $value) {
        echo CHtml::hiddenField($key, $value);
      }
      echo CHtml::hiddenField($order->payment->getSignName(), $order->payment->getSing($params));
      ?>
      <div style="margin: 40px 0 20px; height: 46px; position: relative">
          <div class="main-submit submit">
              <div>ОПЛАТИТЬ</div>
          </div>
          <img style="display: none; margin: auto; position: absolute;left: 0;right: 0;top: 0;bottom: 0" src="/images/load.gif" />
      </div>
      <?php echo CHtml::endForm(); ?>
    <?php } ?>
    <div style="text-align: center; margin-bottom: 15px"><?php echo CHtml::link('Перейти в личный кабинет', '/profile'); ?></div>
</div>
<?php $this->renderPartial('//site/_footer'); ?>
<script type="text/javascript">
  $('.main-submit').click(function () {
      var totalRU = parseFloat($('#order-total').attr('data-summ'));
      ga('send', {
          'hitType': 'event',
          'eventCategory': 'order',
          'eventAction': 'payorder',
          'eventValue': totalRU
      });
  });
</script>