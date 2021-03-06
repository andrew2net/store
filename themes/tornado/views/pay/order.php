<?php
/* @var $this PayController */
/* @var $order Order */
/* @var $coupon_discount float */
/* @var $total float */
/* @var $payed float */
/* @var $to_pay float */

$this->pageTitle = Yii::app()->name . ' - Информация о заказе';
?>
<div class="container" id="page">
    <?php
    Yii::import('application.modules.delivery.models.Delivery');
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.payments.models.Payment');
    Yii::import('application.controllers.ProfileController');
    switch ($order->delivery->zone_type_id) {
        case 3:
            $delivery = $order->delivery->name . ' (' . $order->delivery->transportType . ')';
            break;
        case 4:
            $delivery = $order->customer_delivery;
            break;
        default :
            $delivery = $order->delivery->name;
    }
    ?>
    <h1 class="bold blue" style="margin: 20px 0">Информация о заказе:</h1>

    <div style="display: table; width: 100%">
        <div style="display: table-cell">
            <div><b>Заказ
                    №: </b><?php echo $order->id . ' <b>от</b> ' . Yii::app()->dateFormatter->format('dd.MM.yyyy', $order->time); ?>
            </div>
            <div><b>Статус: </b><?php echo $order->status; ?></div>
            <div><b>Покупатель: </b><?php echo CHtml::encode($order->fio); ?></div>
            <div><b>Телефон: </b><?php echo CHtml::encode($order->phone); ?></div>
            <div><b>Город: </b><?php echo CHtml::encode($order->city); ?></div>
            <div><b>Адрес: </b><?php echo CHtml::encode($order->address); ?></div>
            <div><b>Вид доставки: </b><?php echo CHtml::encode($delivery); ?></div>
            <div style="margin-bottom: 10px"><b>Вид оплаты: </b><?php echo $order->payment->name; ?></div>
        </div>
        <?php if ($order->status_id == 4 && $order->payment->type_id == Payment::TYPE_BANK) { ?>
            <div style="display: table-cell">
                <div class="bold">Реквизиты для оплаты</div>
                <div><b>Получатель: </b><?php echo Yii::app()->params['enterprise']['name']; ?></div>
                <div><b>ИНН: </b><?php echo Yii::app()->params['enterprise']['inn']; ?>
                    <b>КПП: </b><?php echo Yii::app()->params['enterprise']['kpp']; ?></div>
                <div><b>Юр. адрес: </b><?php echo Yii::app()->params['enterprise']['legal_address']; ?></div>
                <div><b>Банк получателя: </b><?php echo Yii::app()->params['enterprise']['bank']['name']; ?></div>
                <div><b>БИК: </b><?php echo Yii::app()->params['enterprise']['bank']['bik']; ?></div>
                <div><b>Корр. счет: </b><?php echo Yii::app()->params['enterprise']['bank']['ks']; ?></div>
                <div><b>Расч. счет: </b><?php echo Yii::app()->params['enterprise']['bank']['rs']; ?></div>
            </div>
        <?php } ?>
    </div>
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
        <?php } ?>
        <tr style="background: whitesmoke">
            <td class="bold" colspan="4" style="text-align: right">Итого:</td>
            <td class="bold" style="text-align: right"><?php echo number_format($total, 0, '.', ' ') ?></td>
        </tr>
        <?php
        if ($payed > 0) {
            ?>
            <tr>
                <td class="bold" colspan="4" style="text-align: right">Оплачено:</td>
                <td class="bold" style="text-align: right"><?php echo number_format($payed, 0, '.', ' ') ?></td>
            </tr>
            <tr>
                <td class="bold" colspan="4" style="text-align: right">К оплате:</td>
                <td class="bold" style="text-align: right"><?php echo number_format($to_pay, 0, '.', ' ') ?></td>
            </tr>
        <?php } ?>
    </table>
    <?php if ($to_pay > 0 && $order->status_id == Order::STATUS_WAITING_FOR_PAY && $order->payment->type_id == Payment::TYPE_LIQPAY) { ?>
        <?php echo CHtml::beginForm($order->payment->getActionUrl()); ?>
        <?php
        $params = $order->payment->getParams($order);
        foreach ($params as $key => $value)
            echo CHtml::hiddenField($key, $value);
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
    <a class="right" id="profile-link" href="/profile">Перейти в личный кабинет</a>
</div>