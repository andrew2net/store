<?php

class DefaultController extends Controller {

  public function filters() {
    return array(
      array('auth.filters.AuthFilter'),
    );
  }

  public function actionIndex() {
    $model = new Order('search');
    $model->unsetAttributes();

    if (isset($_GET['Order'])) {
      $model->attributes = $_GET['Order'];
      Yii::app()->user->setState('Order', $_GET['Order']);
    }
    elseif (Yii::app()->user->hasState('Order')) {
      $model->attributes = Yii::app()->user->getState('Order');
    }

    if (isset($_GET['Order_page']))
      Yii::app()->user->setState('Order_page', $_GET['Order_page']);
    elseif (isset($_GET['ajax']))
      Yii::app()->user->setState('Order_page', NULL);
    elseif (Yii::app()->user->hasState('Order_page'))
      $_GET['Order_page'] = (int) Yii::app()->user->getState('Order_page');

    if (isset($_GET['Order_sort']))
      Yii::app()->user->setState('Order_sort', $_GET['Order_sort']);
    elseif (Yii::app()->user->hasState('Order_sort'))
      $_GET['Order_sort'] = Yii::app()->user->getState('Order_sort');

    $this->render('index', array('model' => $model));
  }

  /**
   * Updates a particular model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id the ID of the model to be updated
   */
  public function actionUpdate($id) {
    Yii::import('application.modules.delivery.models.Delivery');
    Yii::import('application.modules.payment.models.Payment');
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.catalog.models.Price');
    Yii::import('application.modules.discount.models.Coupon');

    $model = $this->loadModel($id);
    $products = array();
    foreach ($model->orderProducts as $value)
      $products[$value->product_id] = $value;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

    if (isset($_POST['Order'])) {
      $oldStatus = $model->status_id;
      $model->attributes = $_POST['Order'];
      $valid = $model->validate();
      $products = array();
      if (isset($_POST['OrderProduct']) && is_array($_POST['OrderProduct'])) {

        foreach ($_POST['OrderProduct'] as $key => $value) {
          $products[$key] = OrderProduct::model()->findByPk(array('order_id' => $id, 'product_id' => $key));
          if (is_null($products[$key]))
            $products[$key] = new OrderProduct;
          $products[$key]->attributes = $value;
          $products[$key]->order_id = $id;
          $products[$key]->product_id = $key;
          $valid = $products[$key]->validate() && $valid;
        }
      }
      if ($valid) {
        $tr = Yii::app()->db->beginTransaction();
        try {
          if ($model->save()) {
            $product_ids = implode(',', array_keys($_POST['OrderProduct']));
            OrderProduct::model()->deleteAllByAttributes(array('order_id' => $model->id)
                , $product_ids ? "product_id NOT IN ($product_ids)" : '');
            foreach ($products as $value) {
              $value->save();
            }
            if ($model->coupon && $model->coupon->type_id == Coupon::TYPE_SUMM &&
                $model->coupon->used_id != Coupon::STATUS_PERMANENT) {
              if ($model->notDiscountSumm < $model->coupon->value) {
                $model->coupon->used_id = Coupon::STATUS_NOT_USED;
                $model->coupon->time_used = '0000-00-00 00:00:00';
              }
              else {
                $model->coupon->used_id = Coupon::STATUS_USED;
                $model->coupon->time_used = Yii::app()->
                    dateFormatter->format('yyyy-MM-dd HH:mm:ss', $model->time);
              }
              $model->coupon->update(array('used_id', 'time_used'));
            }
            $model->changeStatusMessage($oldStatus);
            $tr->commit();
            $this->redirect(array('index'));
          }
        } catch (Exception $e) {
          $tr->rollback();
          throw $e;
        }
      }
    }

    if ($model->status_id == 0)
      $model->status_id = 1;

    $this->render('update', array(
      'model' => $model,
      'product' => $products,
    ));
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return Order the loaded model
   * @throws CHttpException
   */
  public function loadModel($id) {
    Yii::import('application.modules.discount.models.Coupon');
    $model = Order::model()->with(array('coupon'))->findByPk($id);
    if ($model === null)
      throw new CHttpException(404, 'The requested page does not exist.');
    return $model;
  }

  public function actionOrderProduct($oid, $term, $summ, array $ord_pr = array()) {
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.catalog.models.Price');
    Yii::import('application.models.Order');

    $order = Order::model()->findByPk($oid);
    /* @var $order Order */

    $product = strtr($term, array('%' => '\%', '_' => '\_'));
    $product_ids = implode(',', $ord_pr);
    $criteria = new CDbCriteria(array(
      'select' => 'id, name, article',
      'condition' => '(name LIKE :data OR article LIKE :data)' . ($product_ids ? 'AND id NOT IN (' . $product_ids . ')' : ''),
      'params' => array(':data' => '%' . $product . '%'),
      'limit' => 20,
    ));
    $suggest = Product::model()->findAll($criteria);

    $price_type = Price::model()->find(array('order' => 'summ DESC', 'condition' => 'summ<:summ', 'params' => array(':summ' => $summ)));
    if (!$price_type)
      $price_type = Price::model()->find(array('order' => 'summ'));
    $price_types = Price::model()->findAll(array('order' => 'summ', 'condition' => 'summ>=:summ', 'params' => array(':summ' => $summ)));
    /* @var $price_type Price */
    /* @var $price_types Price[] */
    $products = array();
    foreach ($suggest as $value) {
      /* @var $value Product */
      $price = $value->getPrice($price_type, $order->currency_code);
      if ($summ + $price > $price_type->summ) {
        foreach ($price_types as $pt) {
          $price_next = $value->getPrice($pt, $order->currency_code);
          if ($summ + $price_next <= $pt->summ)
            break;
          $price = $price_next;
        }
      }
      $discount = $value->getActualDiscount($order->time);
      $product_price = round($price * (1 - $discount / 100));
      $disc = ($price - $product_price);
      $products[] = array(
        'id' => $value->id,
        'article' => $value->article,
        'value' => $value->name,
        'price' => $product_price,
        'disc' => $disc,
      );
    }
    echo CJSON::encode($products);
    Yii::app()->end();
  }

  public function actionCitydeliveries($city, $oid, array $products) {
    Yii::import('application.modules.delivery.models.Delivery');
    $order = Order::model()->findByPk($oid);
    /* @var $order Order */
    $delivery_list = Delivery::getDeliveryList($order->country_code, $order->post_code, $city, $products, $order);
    echo json_encode($delivery_list);
    Yii::app()->end();
  }

  public function getCityDeliveries($city, $ajax = TRUE) {
    $delivery = Delivery::model()->region($city)->findAll();
    if (count($delivery) == 0)
      $delivery = Delivery::model()->findAllByAttributes(array('name' => 'Другой город'));
    $result = array();
    foreach ($delivery as $value) {
      $result[$value->id] = array(
        'price' => 0,
        'summ' => 0,
      );
      if ($ajax)
        $result[$value->id]['text'] = $value->name;
      if (isset($value->cityDeliveries[0])) {
        $result[$value->id]['price'] = (float) $value->cityDeliveries[0]->price;
        $result[$value->id]['summ'] = (float) $value->cityDeliveries[0]->summ;
      }
    }
    return $result;
  }

  public function actionPayData($id) {
    $pay = Pay::model()->findByPk($id);
    /* @var $pay Pay */
    if (is_null($pay)) {
      echo json_encode(array('status' => false));
      Yii::app()->end();
    }

    $header = TbHtml::button('×', array('class' => 'close', 'style' => 'font-size:20px', 'data-dismiss' => 'modal'))
        . TbHtml::tag('h3', array(), 'Данные транзакции');
    $body = '';
    if (count($pay->data) > 0) {
      $body .= TbHtml::opentag('table');
      foreach ($pay->data as $item) {
        $value = $item->value;
        if ($item->name == 'IP адрес покупателя') {
          $int = sprintf("%u", ip2long(trim($value)));
          $country_data = Yii::app()->db->createCommand("select * from (select * from net_country_ip where begin_ip<=$int order by begin_ip desc limit 1) as t where end_ip>=$int")->query();
          if ($row = $country_data->read()) {
            $country_id = $row['country_id'];
            $country_code = Yii::app()->db->createCommand()->select('name_ru')->from('net_country')
                    ->where('id=:id', array(':id' => $country_id))->query();
            if ($c_code = $country_code->read())
              $value .= ' (' . $c_code['name_ru'] . ')';
          }
        }
        $body .= TbHtml::openTag('tr');
        $body .= TbHtml::tag('td', array(), $item->name);
        $body .= TbHtml::tag('td', array(), $value);
        $body .= TbHtml::closeTag('tr');
      }
      $body .= TbHtml::closeTag('table');
    }
    else {
      $body .= TbHtml::tag('div', array(), 'Нет данных');
    }
    $footer = TbHtml::button('Закрыть', array('data-dismiss' => 'modal', 'color' => TbHtml::BUTTON_COLOR_PRIMARY));

    echo json_encode(array('status' => true, 'header' => $header, 'body' => $body, 'footer' => $footer));
    Yii::app()->end();
  }

  public function actionPayCompleteDialog($id) {
    Yii::import('application.modules.payments.models.Currency');
    $pay = Pay::model()->findByPk($id);
    /* @var $pay Pay */
    if (is_null($pay)) {
      echo json_encode(array('status' => false));
      Yii::app()->end();
    }

    $header = TbHtml::button('×', array('class' => 'close', 'style' => 'font-size:20px', 'data-dismiss' => 'modal'))
        . TbHtml::tag('h3', array(), 'Завершение транзакции');
    $topay = $pay->order->getToPaySumm();
    $currency_class = $pay->order->currency->getCss();
    $trsumm = $pay->amount;
    $body = TbHtml::openTag('div', array('id' => 'modal-text', 'style' => 'height:60px'));
    if ($topay < 0) {
      $trsumm += $topay;
      $topay *= -1;
      $body .= TbHtml::openTag('p', array('class' => 'red'));
      $body .= "Сумма к оплате по заказу меньше суммы транзакции на ";
      $body .= TbHtml::tag('span', array('class' => $currency_class), $topay);
      $body .= TbHtml::closeTag('p');
      $body .= TbHtml::openTag('p');
      $body .= "Завершить транзакцию на сумму ";
      $body .= TbHtml::tag('span', array('class' => $currency_class), $trsumm) . "&nbsp;&nbsp;?";
      $body .= TbHtml::closeTag('p');
    }
    elseif ($topay > 0) {
      $body .= TbHtml::openTag('p', array('class' => 'red'));
      $body .= "Сумма к оплате по заказу больше суммы транзакции на ";
      $body .= TbHtml::tag('span', array('class' => $currency_class), $topay);
      $body .= TbHtml::closeTag('p');
      $body .= TbHtml::tag('p', array(), 'Завершить транзакцию?');
    }
    else
      $body .= TbHtml::tag('p', array('style' => 'text-align:center;line-height:60px'), 'Завершить транзакцию?');
    $body .= TbHtml::closeTag('div');
    $body .= TbHtml::openTag('div', array(
          'style' => 'display:none;text-align:center;line-height:60px',
          'id' => 'modal-process',
    ));
    $body .= TbHtml::tag('img', array('src' => '/images/process.gif'));
    $body .= TbHtml::closeTag('div');
    $body .= TbHtml::openTag('div', array('id' => 'modal-success',
          'style' => 'display:none;text-align:center;height:60px'));
    $body .= TbHtml::tag('p', array('style' => 'line-height:60px'), 'Транзакция успешно завершена');
    $body .= TbHtml::closeTag('div');
    $body .= TbHtml::openTag('div', array('id' => 'modal-error',
          'style' => 'display:none;text-align:center;height:60px'));
    $body .= TbHtml::tag('p', array('class' => 'red'), 'Ошибка завершения транзакции!');
    $body .= TbHtml::tag('p', array(), 'Попробовать еще раз?');
    $body .= TbHtml::closeTag('div');
    $body .= TbHtml::hiddenField('modal-pay-id', $id);

    $footer = TbHtml::button('Да', array('id' => 'modal-ok-bt', 'acturl' => '/admin/default/payComplete'))
        . TbHtml::button('Нет', array('data-dismiss' => 'modal', 'color' => TbHtml::BUTTON_COLOR_PRIMARY));

    echo json_encode(array('status' => true, 'header' => $header, 'body' => $body, 'footer' => $footer));
    Yii::app()->end();
  }

  public function actionPayComplete() {
    if (isset($_POST['id'])) {
      Yii::import('application.modules.payments.models.Payment');
      require_once Yii::app()->basePath . '/extensions/CNPMerchantWebServiceClient.php';
      $pay = Pay::model()->findByPk($_POST['id']);
      /* @var $pay Pay */

      try {
        $client = new CNPMerchantWebServiceClient();
        $params = new completeTransaction();
        $params->merchantId = $pay->order->payment->merchant_id;
        $params->customerReference = $pay->operation_id;
        $params->transactionSuccess = true;
        $topay = $pay->order->getToPaySumm();
        if ($topay < 0) {
          $params->overrideAmount = $pay->amount + $topay;
          $basket = PayController::getBasket($pay->order);
          $params->goodsList = $basket;
        }
        $responce = $client->completeTransaction($params);
        if ($responce) {
          $pay->status_id = Pay::PAID;
          if ($topay < 0) {
            $pay->amount += $topay;
            $pay->currency_amount = $pay->amount;
          }
          $pay->save();
          $body = $this->renderPartial('_payBody', array('model' => $pay->order), TRUE);
          $total = $this->renderPartial('_payTotal', array('model' => $pay->order), TRUE);
          echo json_encode(array('status' => true, 'body' => $body, 'total' => $total));
        }
        else
          echo json_encode(array('status' => FALSE));
      } catch (Exception $ex) {
        echo json_encode(array('status' => FALSE));
      }
    }
    else
      echo '';
    Yii::app()->end();
  }

  public function actionPayRefundDialog($id) {
    Yii::import('application.modules.payments.models.Currency');
    $pay = Pay::model()->findByPk($id);
    /* @var $pay Pay */
    if (is_null($pay)) {
      echo json_encode(array('status' => false));
      Yii::app()->end();
    }

    $header = TbHtml::button('×', array('class' => 'close', 'style' => 'font-size:20px', 'data-dismiss' => 'modal'))
        . TbHtml::tag('h3', array(), 'Возврат средств покупателю');

    $body = TbHtml::openTag('div', array('id' => 'modal-text',
          'style' => 'text-align:center;height:60px'));
    $body .= TbHtml::tag('p', array('style' => 'line-height:60px'), 'Вернуть средства покупателю?');
    $body .= TbHtml::closeTag('div');
    $body .= TbHtml::openTag('div', array(
          'style' => 'display:none;text-align:center;line-height:60px',
          'id' => 'modal-process',
    ));
    $body .= TbHtml::tag('img', array('src' => '/images/process.gif'));
    $body .= TbHtml::closeTag('div');
    $body .= TbHtml::openTag('div', array('id' => 'modal-success',
          'style' => 'display:none;text-align:center;height:60px'));
    $body .= TbHtml::tag('p', array('style' => 'line-height:60px'), 'Возврат средств успешно завершен');
    $body .= TbHtml::closeTag('div');
    $body .= TbHtml::openTag('div', array('id' => 'modal-error',
          'style' => 'display:none;text-align:center;height:60px'));
    $body .= TbHtml::tag('p', array('class' => 'red'), 'Ошибка выполнения опреации возврата средств!');
    $body .= TbHtml::tag('p', array(), 'Попробовать еще раз?');
    $body .= TbHtml::closeTag('div');
    $body .= TbHtml::hiddenField('modal-pay-id', $id);

    $footer = TbHtml::button('Да', array('id' => 'modal-ok-bt', 'acturl' => '/admin/default/payRefund'))
        . TbHtml::button('Нет', array('data-dismiss' => 'modal', 'color' => TbHtml::BUTTON_COLOR_PRIMARY));

    echo json_encode(array('status' => true, 'header' => $header, 'body' => $body, 'footer' => $footer));
    Yii::app()->end();
  }

  public function actionPayRefund() {
    if (isset($_POST['id'])) {
      Yii::import('ext.LiqPay');
      Yii::import('application.modules.payments.models.Payment');

      $pay = Pay::model()->findByPk($_POST['id']);
      /* @var $pay Pay */

      $serverUrl = Yii::app()->createAbsoluteUrl("/pay/liqPayNotify");
      $liqpay = new LiqPay($pay->order->payment->merchant_id, $pay->order->payment->sign_key);
      $responce = $liqpay->api('payment/refund', array(
        'order_id' => $pay->order_id,
        'server_url' => $serverUrl,
      ));
      if ($responce->result == 'ok') {
        $body = $this->renderPartial('_payBody', array('model' => $pay->order));
        $total = $this->renderPartial('_payTotal', array('model' => $pay->order));
        echo json_encode(array('status' => true, 'body' => $body, 'total' => $total));
      }
      else
        echo json_encode(array('status' => FALSE));
    }
    Yii::app()->end();
  }

  public function actionPayGetStatus($id) {
    $pay = Pay::model()->findByPk($id);
    /* @var $pay Pay */
    if (is_null($pay)) {
      echo json_encode(array('status' => false));
      Yii::app()->end();
    }
    Yii::import('ext.LiqPay');
    Yii::import('application.modules.payments.models.Payment');
    $liqpay = new LiqPay($pay->order->payment->merchant_id, $pay->order->payment->sign_key);
    $responce = $liqpay->api('payment/status', array(
      'order_id' => $pay->order_id,
    ));
    if ($responce->result == 'ok') {
      if ($responce->payment_id == $pay->operation_id) {
        $pay->amount = $responce->amount;
        $pay->currency = $responce->currency;
        $statuses = $pay->order->payment->getStatuses();
        $pay->status_id = constant("Pay::{$statuses[$responce->status]}");
        $pay->save();
        $body = $this->renderPartial('_payBody', array('model' => $pay->order));
        $total = $this->renderPartial('_payTotal', array('model' => $pay->order));
        echo json_encode(array('status' => true, 'body' => $body, 'total' => $total));
        Yii::app()->end();
      }
    }
    echo json_encode(array('status' => FALSE));
    Yii::app()->end();
  }

}
