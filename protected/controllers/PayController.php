<?php

/**
 * Description of PaymentController
 *
 * @author 
 */
class PayController extends Controller {

  public $defaultAction = 'order';

  public function actionOrder($id) {
    Yii::import('application.modules.payments.models.Payment');
    Yii::import('application.modules.payments.models.PaymentParams');
    Yii::import('application.modules.payments.models.Currency');
    if (Yii::app()->user->isGuest)
      $this->redirect('/profile');
    $order = Order::model()->with(array('profile', 'payment'))->findByPk($id
        , 'profile.user_id=:uid'
        , array(':uid' => Yii::app()->user->id));
    /* @var $order Order */

    if ($order) {

      $description = "Интернет-магазин DeMARK, оплата заказа № $id";

      $coupon_discount = $order->getCouponDiscount();
      $total = $order->productSumm + $order->delivery_summ - $coupon_discount;
      $paied = $order->paySumm;
      $to_pay = $total - $paied;

      $pay_values = array();
      foreach ($order->payment->params as $param) {
        ob_start();
        eval($param->value);
        if (!$value = ob_get_contents())
          $value = $param->value;
        ob_end_clean();
        $pay_values[$param->name] = $value;
      }
      uksort($pay_values, 'strcasecmp');

      $fieldValues = '';
      foreach ($pay_values as $value) {
        $value = iconv('utf-8', 'windows-1251', $value);
        $fieldValues .= $value;
      }
      $sign = base64_encode(pack('H*', md5($fieldValues . $order->payment->sign_key)));
      $pay_values[$order->payment->sign_name] = $sign;

      $this->render('order', array('order' => $order, 'coupon_discount' => $coupon_discount,
        'total' => $total, 'paied' => $paied, 'to_pay' => $to_pay, 'pay_values' => $pay_values));
    }
    else
      throw new CHttpException(404, "Заказ № $id для оплаты не найден");
  }

  public function actionNotify() {
    Yii::trace('Pay notify', 'application');
    Yii::import('application.modules.payments.models.Payment');
    Yii::import('application.modules.payments.models.Currency');
    if (isset($_POST['MNT_OPERATION_ID'])) {
      $order = Order::model()->findByPk($_POST['MNT_TRANSACTION_ID']);
      /* @var $order Order */
      if ($order) {
        $signature = md5($_POST['MNT_ID'] . $_POST['MNT_TRANSACTION_ID']
            . $_POST['MNT_OPERATION_ID'] . $_POST['MNT_AMOUNT']
            . $_POST['MNT_CURRENCY_CODE']
            . (isset($_POST['MNT_SUBSCRIBER_ID']) ? $_POST['MNT_SUBSCRIBER_ID'] : '')
            . $_POST['MNT_TEST_MODE'] . $order->payment->sign_key);

        if ($_POST['MNT_SIGNATURE'] == $signature) {
          $pay = Pay::model()->findByAttributes(array(
            'mnt_operation_id' => $_POST['MNT_OPERATION_ID'],
            'order_id' => $order->id,
          ));
          if (is_null($pay))
            $pay = new Pay;
          $pay->order_id = $order->id;
          Yii::trace('Pay notify. Pay: ' . $_POST['MNT_CORRACCOUNT']);
          $pay->operation_id = $_POST['MNT_OPERATION_ID'];
          $pay->amount = $_POST['MNT_AMOUNT'];
          $pay->pay_system_id = isset($_POST['paymentSystem.unitId']) ?
              $_POST['paymentSystem.unitId'] : '';
          $pay->corr_acc = isset($_POST['MNT_CORRACCOUNT']) ?
              $_POST['MNT_CORRACCOUNT'] : '';
          $pay->currency_iso = $_POST['MNT_CURRENCY_CODE'];
          if ($pay->save()) {
            echo 'SUCCESS';
            Yii::app()->end();
          }
        }
      }
      echo 'FAIL';
    }
    elseif (isset($_POST['WMI_MERCHANT_ID'])) {
      $order = Order::model()->findByPk($_POST['WMI_PAYMENT_NO']);
      /* @var $order Order */
      if ($order) {
        foreach ($_POST as $name => $value) {
          if ($name != 'WMI_SIGNATURE')
            $params[$name] = $value;
        }
        uksort($params, "strcasecmp");
        $values = "";
        foreach ($params as $name => $value) {
          //Конвертация из текущей кодировки (UTF-8)
          //необходима только если кодировка магазина отлична от Windows-1251
//        $value = iconv("utf-8", "windows-1251", $value);
          $values .= $value;
        }
        $signature = base64_encode(pack("H*", md5($values . $order->payment->sign_key)));

        if ($_POST['WMI_SIGNATURE'] == $signature) {
          if (strtoupper($_POST["WMI_ORDER_STATE"]) == "ACCEPTED") {
            $pay = Pay::model()->findByAttributes(array(
              'operation_id' => $_POST['WMI_ORDER_ID'],
              'order_id' => $order->id,
            ));
            if ($pay) {
              echo 'WMI_RESULT=OK&WMI_DESCRIPTION=Уведомление о платеже уже принято';
              Yii::app()->end();
            }
            $pay = new Pay;
            $pay->order_id = $order->id;
//            Yii::trace('Pay notify. Pay: ' . $_POST['MNT_CORRACCOUNT']);
            $pay->operation_id = $_POST['WMI_ORDER_ID'];
            $pay->currency_amount = $_POST['WMI_PAYMENT_AMOUNT'];

            if ($order->currency->iso == $_POST['WMI_CURRENCY_ID'])
              $pay->amount = $_POST['WMI_PAYMENT_AMOUNT'];
            else {
              //converr currency
              Yii::trace('Pay notify. Оплата в другой валюте. Заказ: ' . $order->id 
                  . ' WMI_MERCHANT_ID:' . $_POST['WMI_MERCHANT_ID'] . ' Валюта: ' . $_POST['WMI_CURRENCY_ID']);
            }

            $pay->pay_system_id = isset($_POST['WMI_PAYMENT_TYPE']) ?
                $_POST['WMI_PAYMENT_TYPE'] : '';
            $pay->corr_acc = isset($_POST['WMI_EXTERNAL_ACCOUNT_ID']) ?
                $_POST['WMI_EXTERNAL_ACCOUNT_ID'] : '';
            if ($pay->save()) {
              echo 'WMI_RESULT=OK';
            }
            else {
              Yii::trace('Pay notify. Ошибка записи платежа. Заказ: ' . $order->id . ' WMI_MERCHANT_ID:' . $_POST['WMI_MERCHANT_ID']);
              echo 'WMI_RESULT=RETRY&WMI_DESCRIPTION=Ошибка записи платежа';
            }
          }else{
            echo 'WMI_RESULT=RETRY&WMI_DESCRIPTION=Неверное состояние ' . $_POST['WMI_ORDER_STATE'];
          }
        }else {
          echo 'WMI_RESULT=RETRY&WMI_DESCRIPTION=Неверная подпись ' . $_POST['WMI_SIGNATURE'];
        }
      }
    }
    Yii::app()->end();
  }

  public function actionSuccess() {
    $this->render('success');
  }

  public function actionFail() {
    $this->render('fail');
  }

  public function actionInProgress() {
    $this->render('inProgress');
  }

}
