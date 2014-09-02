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
      $old_status = $model->status_id;
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
            if ($model->coupon && $model->coupon->type_id == 0 &&
                $model->coupon->used_id != 1) {
              if ($model->notDiscountSumm < $model->coupon->value) {
                $model->coupon->used_id = 0;
                $model->coupon->time_used = '0000-00-00 00:00:00';
              }
              else {
                $model->coupon->used_id = 2;
                $model->coupon->time_used = Yii::app()->
                    dateFormatter->format('yyyy-MM-dd HH:mm:ss', $model->time);
              }
              $model->coupon->update(array('used_id', 'time_used'));
            }
            if ($old_status != $model->status_id) {
              $mail = new Mail;
              $mail->uid = $model->profile->user_id;
              $mail->type_id = 4;
              $mail->status_id = 1;
              if ($mail->save()) {
                $mailOrder = new MailOrder;
                $mailOrder->mail_id = $mail->id;
                $mailOrder->order_id = $model->id;
                $mailOrder->save();
              }
            }
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

  public function actionCitydeliveries() {
    if (isset($_POST['city'])) {
      echo json_encode($this->getCityDeliveries($_POST['city']));
    }
    Yii::app()->end();
  }

  public function getCityDeliveries($city, $ajax = TRUE) {
    Yii::import('application.modules.delivery.models.Delivery');
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

}
