<?php

/**
 * Description of PaymentController
 *
 * @author
 */
class PayController extends Controller
{

    public $defaultAction = 'order';

    public function actionOrder($id)
    {
        Yii::import('application.modules.payments.models.Payment');
        Yii::import('application.modules.payments.models.PaymentParams');
        Yii::import('application.modules.payments.models.Currency');
        Yii::import('application.modules.catalog.models.Product');
        Yii::import('application.modules.delivery.models.Delivery');
        if (Yii::app()->user->isGuest)
            $this->redirect('/profile');
        $order = Order::model()->with(array('profile', 'payment'))->findByPk($id
            , 'profile.user_id=:uid'
            , array(':uid' => Yii::app()->user->id));
        /* @var $order Order */

        if ($order) {

//      $description = "Интернет-магазин DeMARK, оплата заказа № $id";

            $coupon_discount = $order->getCouponSumm();
            $total = $order->productSumm + $order->delivery_summ + $order->insuranceSumm - $coupon_discount;
            $paied = $order->paySumm + $order->authSumm;
            $to_pay = $total - $paied;

            $errors = '';

            if ($order->payment->type_id == 2) {
                if (isset($_POST['processingkz'])) {
                    require_once(Yii::getPathOfAlias('ext.CNPMerchantWebServiceClient') . '.php');

                    $client = new CNPMerchantWebServiceClient();

                    $basket = $order->getBasket();

                    $transactionDetail = new TransactionDetails();
                    $transactionDetail->merchantId = $order->payment->merchant_id;
                    $transactionDetail->totalAmount = $to_pay * 100;
                    $transactionDetail->currencyCode = $order->currency->iso;
                    $transactionDetail->description = 'DeMARK - оплата заказа №' . $order->id;
                    $transactionDetail->returnURL = Yii::app()->createAbsoluteUrl('/pay/result') . '?customerReference=';
                    $transactionDetail->goodsList = $basket;
                    $transactionDetail->languageCode = 'ru';
                    $dateTime = new DateTime;
                    $transactionDetail->merchantLocalDateTime = $dateTime->format('d.m.Y H:i:s');
                    $transactionDetail->orderId = $order->id;
                    $transactionDetail->purchaserName = $order->fio;
                    $transactionDetail->purchaserEmail = $order->email;

                    $st = new startTransaction();
                    $st->transaction = $transactionDetail;
                    $startTransactionResult = $client->startTransaction($st);

                    if ($startTransactionResult->return->success == true) {
                        $pay = new Pay;
                        $pay->order_id = $order->id;
                        $pay->operation_id = $startTransactionResult->return->customerReference;
                        $pay->currency_iso = $order->currency->iso;
                        $pay->amount = $to_pay;
                        $pay->currency_amount = $to_pay;
                        $pay->time = $dateTime->format('Y-m-d H:i:s');
                        if ($pay->save()) {
                            $_SESSION ["customerReference"] = $startTransactionResult->return->customerReference;
                            header("Location: " . $startTransactionResult->return->redirectURL);
                        }
                    } else {
                        Yii::log($startTransactionResult->return->errorDescription, CLogger::LEVEL_ERROR, 'payment');
                        $errors = 'Error: ' . $startTransactionResult->return->errorDescription;
                    }
                }
            }

            $this->render('order', array(
                'order' => $order,
                'coupon_discount' => $coupon_discount,
                'total' => $total,
                'paied' => $paied,
                'to_pay' => $to_pay,
                'errors' => $errors,
            ));
        } else
            throw new CHttpException(404, "Заказ № $id для оплаты не найден");
    }

    public function actionNotify()
    {
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
        } elseif (isset($_POST['WMI_MERCHANT_ID'])) {
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
                            echo 'WMI_RESULT=OK&WMI_DESCRIPTION=' . urlencode('Уведомление о платеже уже принято');
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
                        } else {
                            Yii::trace('Pay notify. Ошибка записи платежа. Заказ: ' . $order->id . ' WMI_MERCHANT_ID:' . $_POST['WMI_MERCHANT_ID']);
                            echo 'WMI_RESULT=RETRY&WMI_DESCRIPTION=Ошибка записи платежа';
                        }
                    } else {
                        echo 'WMI_RESULT=RETRY&WMI_DESCRIPTION=Неверное состояние ' . $_POST['WMI_ORDER_STATE'];
                    }
                } else {
                    echo 'WMI_RESULT=RETRY&WMI_DESCRIPTION=' . 'Неверная подпись ' . $_POST['WMI_SIGNATURE'];
                }
            }
        }
        Yii::app()->end();
    }

    public function actionResult()
    {
        $message = array(
            'title' => 'Платеж обрабатывается',
            'txt' => 'Просмотреть статус заказа Вы можете личном кабинете.',
        );

        if (isset($_REQUEST['customerReference']) || isset($_SESSION['customerReference'])) {
            Yii::import('application.modules.payments.models.Payment');
            require_once Yii::app()->basePath . '/extensions/CNPMerchantWebServiceClient.php';

            $rrn = isset($_REQUEST['customerReference']) ?
                $_REQUEST['customerReference'] : $_SESSION['customerReference'];

            $payment = Payment::model()->findByAttributes(array('type_id' => Payment::TYPE_PROCESSINGKZ));
            /* @var $payment Payment */
            if (is_null($payment)) {
                Yii::log('Payment type Processing.kz not set', CLogger::LEVEL_ERROR, 'pay_notify');
                $this->render('result', array('message' => array(
                    'title' => 'Ошибка',
                    'txt' => 'Метод оплаты Processing.kz не настроен.',
                )));
                Yii::app()->end();
            }

            $client = new CNPMerchantWebServiceClient();

            $transactionResult = $payment->getProcessingKzStatus($client, $rrn);

            $pay = Pay::model()->findByAttributes(array(
                'operation_id' => $rrn,
                'order_id' => $transactionResult->return->orderId,
            ));
            /* @var $pay Pay */
            if ($pay) {
                $status_id = $pay->renewStatus($client);

                switch ($status_id) {
                    case Pay::NO_SUCH_TRANSACTION:
                    case Pay::DECLINED:
                    case Pay::REVERSED:
                    case Pay::REFUNDED:
                    case Pay::INVALID_MID:
                    case Pay::MID_DISABLED:
                        $message['title'] = 'Ошибка';
                        break;
                    case Pay::PENDING_CUSTOMER_INPUT:
                    case Pay::PENDING_AUTH_RESULT:
                        $message['title'] = 'Платеж обрабатывается';
                        break;
                    case Pay::AUTHORISED:
                    case Pay::PAID:
                        $message['title'] = 'Оплата прошла успешно';
//            $oldStatus = $pay->order->status_id;
//            $pay->order->status_id = Order::STATUS_PAID;
//            $pay->order->save();
//            $pay->order->changeStatusMessage($oldStatus);
                }
                $message['txt'] = 'Статус платежа: "' . $pay->status . '"';
            } else {
                $message['title'] = 'Ошибка';
                $message['txt'] = 'Незвестный платеж ' . $rrn;
            }
        }
        $this->render('result', array('message' => $message));
    }

    public function actionFail()
    {
        $this->render('fail');
    }

    public function actionInProgress()
    {
        $this->render('inProgress');
    }

    public function actionLiqPayNotify()
    {
        if (isset($_POST['order_id']))
            $order = Order::model()->findByPk($_POST['order_id']);
        else
            throw new CHttpException('400');
        /* @var $order Order */
        if (!$order)
            throw new CHttpException('404');

        Yii::import('application.modules.payments.models.Payment');
        $string = $order->payment->sign_key;

        if (isset($_POST['amount']))
            $string .= $_POST['amount'];
        else
            throw new CHttpException('400');

        if (isset($_POST['currency']))
            $string .= $_POST['currency'];
        else
            throw new CHttpException('400');

        if (isset($_POST['public_key']))
            $string .= $_POST['public_key'];
        else
            throw new CHttpException('400');

        if (isset($_POST['order_id']))
            $string .= $_POST['order_id'];
        else
            throw new CHttpException('400');

        if (isset($_POST['type']))
            $string .= $_POST['type'];
        else
            throw new CHttpException('400');

        if (isset($_POST['description']))
            $string .= $_POST['description'];
        else
            throw new CHttpException('400');

        if (isset($_POST['status']))
            $string .= $_POST['status'];
        else
            throw new CHttpException('400');

        if (isset($_POST['transaction_id']))
            $string .= $_POST['transaction_id'];
        else
            throw new CHttpException('400');

        if (isset($_POST['sender_phone']))
            $string .= $_POST['sender_phone'];
        else
            throw new CHttpException('400');

        if (!isset($_POST['signature']))
            throw new CHttpException('400');

        $sign = base64_encode(sha1($string, 1));

        Yii::log($_POST['status'], CLogger::LEVEL_INFO, 'pay_notify');
        if ($_POST['signature'] != $sign)
            throw new CHttpException('401');

        Yii::log('sign ok', CLogger::LEVEL_INFO, 'pay_notify');

        $profile = CustomerProfile::model()->findByAttributes(array('user_id' => $order->profile->user_id));
        /* @var $profile CustomerProfile */
        if (empty($profile->phone)) {
            $profile->phone = $_POST['sender_phone'];
            $profile->save();
        }

        Yii::log('phone ok', CLogger::LEVEL_INFO, 'pay_notify');

        $pay = Pay::model()->findByAttributes(array('operation_id' => $_POST['transaction_id']));
        /* @var $pay Pay */
        if (is_null($pay)) {
            $pay = new Pay;
            $pay->operation_id = $_POST['transaction_id'];
            $pay->time = date('Y-m-d H:i:s');
        }

        Yii::log('transaction ok', CLogger::LEVEL_INFO, 'pay_notify');

        $pay->attributes = $_POST;

        Yii::log('attributes ok', CLogger::LEVEL_INFO, 'pay_notify');

        $statuses = $order->payment->getStatuses();

        Yii::log('get statuses ok', CLogger::LEVEL_INFO, 'pay_notify');

        $pay->status_id = constant("Pay::{$statuses[$_POST['status']]}");

        Yii::log('status ok', CLogger::LEVEL_INFO, 'pay_notify');

        if ($_POST['currency'] == $order->currency_code) {
            Yii::import('application.modules.payments.models.Currency');
            $pay->currency_amount = $pay->amount;
            $pay->currency_iso = $order->currency->iso;
        }

        Yii::log('currency ok', CLogger::LEVEL_INFO, 'pay_notify');

        if (!$pay->save())
            throw new CHttpException('501');

        Yii::log('Save ok', CLogger::LEVEL_INFO, 'pay_notify');

        $pay->setData('Телефон покупателя', $_POST['sender_phone']);

//    Yii::trace($order->getToPaySumm(), 'pay_notify');
        $saveOrder = false;
        $oldStatus = $order->status_id;
        if ($order->getToPaySumm() <= 0) {
            $order->status_id = Order::STATUS_PAID;
            $order->exchange = TRUE;
            $saveOrder = true;
        }
        if (empty($order->phone)) {
            $order->phone = $_POST['sender_phone'];
            $saveOrder = true;
        }
        if ($saveOrder) {
            $order->exchange = 1;
            $order->save();
            $order->changeStatusMessage($oldStatus);
        }

        Yii::app()->end();
    }

}
