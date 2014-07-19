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
        , 'profile.user_id=:uid AND t.status_id=3 AND payment.type_id<>0'
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
    if (isset($_POST['MNT_OPERATION_ID'])) {
      $order = Order::model()->findByPk($_POST['MNT_TRANSACTION_ID']);
      if ($order) {
        Yii::import('application.modules.payments.models.Payment');
        $signature = md5($_POST['MNT_ID'] . $_POST['MNT_TRANSACTION_ID']
            . $_POST['MNT_OPERATION_ID'] . $_POST['MNT_AMOUNT']
            . $_POST['MNT_CURRENCY_CODE']
            . (isset($_POST['MNT_SUBSCRIBER_ID']) ? $_POST['MNT_SUBSCRIBER_ID'] : '')
            . $_POST['MNT_TEST_MODE'] . $order->payment->mnt_signature);

        if ($_POST['MNT_SIGNATURE'] == $signature) {
          $pay = Pay::model()->findByAttributes(array(
            'mnt_operation_id' => $_POST['MNT_OPERATION_ID'],
            'order_id' => $order->id,
          ));
          if (is_null($pay))
            $pay = new Pay;
          $pay->order_id = $order->id;
          Yii::trace('Pay notify. Pay: ' . $_POST['MNT_CORRACCOUNT']);
          $pay->mnt_operation_id = $_POST['MNT_OPERATION_ID'];
          $pay->mnt_amount = $_POST['MNT_AMOUNT'];
          $pay->pay_system_id = isset($_POST['paymentSystem.unitId']) ?
              $_POST['paymentSystem.unitId'] : '';
          $pay->mnt_corr_acc = isset($_POST['MNT_CORRACCOUNT']) ?
              $_POST['MNT_CORRACCOUNT'] : '';
          if ($pay->save()) {
            echo 'SUCCESS';
            Yii::app()->end();
          }
        }
      }
    }
    echo 'FAIL';
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
