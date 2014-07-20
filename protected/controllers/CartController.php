<?php

/**
 * Description of CartController
 *
 */
class CartController extends Controller {

  public function actionIndex() {
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.catalog.models.Brand');
    Yii::import('application.modules.delivery.models.Delivery');
    Yii::import('application.modules.payments.models.Payment');
    Yii::import('application.modules.discount.models.Coupon');
    Yii::import('application.controllers.ProfileController');

    $customer_profile = ProfileController::getProfile();
    $cart = Cart::model()->shoppingCart(ProfileController::getSession())->findAll();

    if ($customer_profile->user) {
      $user = $customer_profile->user;
      $profile = $user->profile;
    }
    else {
      $user = new User;
      $profile = new Profile;
    }

    $coupon_data = array('code' => '', 'type' => '', 'value' => '');
    if (isset($_POST['coupon'])) {
      $coupon = Coupon::model()->findByAttributes(array(
        'code' => $_POST['coupon'])
          , "used_id<>2 AND (date_limit>=:date OR date_limit IS NULL OR date_limit='0000-00-00')"
          , array(':date' => date('Y-m-d')));
      if ($coupon)
        $coupon_data = array(
          'code' => $coupon->code,
          'type' => $coupon->type_id,
          'value' => $coupon->value
        );
    }
    else
      $coupon = NULL;

    $has_err = '';
    $order = new Order;

    $order->payment_id = 1;

    if (!Yii::app()->params['country'])
      if (isset($_POST['CustomerProfile']['post_code']))
        $country_code = $_POST['CustomerProfile']['country_code'];
      else
        $country_code = $customer_profile->country_code;
    else
      $country_code = Yii::app()->params['country'];

    if (Yii::app()->params['post_code'])
      if (isset($_POST['CustomerProfile']['post_code']))
        $post_code = $_POST['CustomerProfile']['post_code'];
      else
        $post_code = $customer_profile->post_code;
    else
      $post_code = '';

    $delivery = Delivery::model()->getDeliveryList($country_code, $post_code, $cart);
    if (isset($_POST['Order']))
      $order->delivery_id = $_POST['Order']['delivery_id'];
    elseif (is_array($delivery))
      $order->delivery_id = key($delivery);
    else
      $order->delivery_id = 1;

    $payment = Payment::model()->getPaymentList();
    if (is_array($payment))
      $order->payment_id = key($payment);
    else
      $order->payment_id = 1;

    if (isset($_POST['CustomerProfile'])) {
      $customer_profile->attributes = $_POST['CustomerProfile'];
      if (isset($_POST['Order']))
        $order->attributes = $_POST['Order'];
      if ($customer_profile->save()) {
        if (Yii::app()->user->isGuest) {
          $user = User::model()->findByAttributes(array(
            'email' => $customer_profile->email));
          if (is_null($user)) {
            ProfileController::registerUser($customer_profile);
          }
        }
        if (isset($_POST['Cart'])) {
          $count_products = $this->countProducts($coupon);
          $count_products['summ'] -= $count_products['couponDisc'];

          $fl = FALSE;
          if ($count_products['summ'] >= 700) {
            $tr = $order->dbConnection->beginTransaction();
            try {
              if (count($cart) > 0) {
                $this->saveOrderProducts($order, $customer_profile, $coupon, $count_products);

                foreach ($cart as $item)
                  $item->delete();
                $fl = TRUE;
              }
              $tr->commit();
            } catch (Exception $e) {
              $tr->rollback();
              throw $e;
            }
            if ($fl) {
              $this->sendConfirmOrderMessage($order, $customer_profile, $count_products['couponDisc']);
              $this->redirect('orderSent');
            }
          }
        }
      }
      else
        $has_err = 'prof';
    }

    Yii::import('application.modules.payments.models.Currency');
    $currency = Currency::model()->findByAttributes(array(
      'country_code' => $country_code));
    /* @var $currency Currency */

//    $delivery_hint =
    $this->render('shoppingCart', array(
      'cart' => $cart,
      'customer_profile' => $customer_profile,
      'user' => $user,
      'profile' => $profile,
      'order' => $order,
      'delivery' => $delivery,
      'payment' => $payment,
      'coupon' => $coupon_data,
      'has_err' => $has_err,
      'currency' => $currency,
    ));
  }

  private function countProducts($coupon) {
    $result = array('count' => 0,
      'summ' => 0,
      'noDiscount' => 0,
      'discount' => 0,
      'couponDisc' => 0
    );
    foreach ($_POST['Cart'] as $k => $q) {
      $quantity = $q['quantity'] > 0 ? $q['quantity'] : 0;
      $result['count'] += $quantity;
      $product = Product::model()->findByPk($k);
      $discount = $product->getActualDiscount();
      if (is_array($discount)) {
        $price = $discount['price'];
        $result['discount'] += ($product->price - $price) * $quantity;
      }
      else {
        $price = $product->price;
        $result['noDiscount'] += $product->price * $quantity;
        if ($coupon) {
          if ($coupon->type_id)
            $result['couponDisc'] += $price * $quantity * $coupon->value / 100;
          else
            $result['couponDisc'] += $price * $quantity;
        }
      }
      $result['summ'] += $quantity * $price;
    }
    if ($coupon && !$coupon->type_id)
      if ($result['summ'] < 1800 || $result['discount'] > $coupon->value)
        $result['couponDisc'] = 0;
      else if ($coupon->value < $result['couponDisc'])
        $result['couponDisc'] = $coupon->value;
    return $result;
  }

  private function saveOrderProducts(Order $order, CustomerProfile $profile, $coupon, $count_products) {

    $order->attributes = $_POST['Order'];
    $order->delivery_summ = $_SESSION['storage']['delivery'][$_POST['Order']['delivery_id']]['summ'];
    $order->profile_id = $profile->id;
    $order->fio = $profile->fio;
    $order->email = $profile->email;
    $order->phone = $profile->phone;
    $order->country_code = $profile->country_code;
    $order->post_code = $profile->post_code;
    $order->city = $profile->city;
    $order->address = $profile->address;
    $order->currency_code = Currency::model()->findByCountry($profile->price_country)->code;
    $order->status_id = Yii::app()->params['order_new_status'];
    $order->time = date('Y-m-d H:i:s');

    if ($coupon && $count_products['couponDisc'])
      $order->coupon_id = $coupon->id;

    if ($order->save()) {
      foreach ($_POST['Cart'] as $key => $value) {
        if ($value['quantity'] > 0) {
          $order_product = new OrderProduct;
          $order_product->order_id = $order->id;
          $order_product->product_id = $key;
          $order_product->quantity = $value['quantity'];
          $product = Product::model()->findByPk($key);
          $discount = $product->getActualDiscount();
          if (is_array($discount)) {
            $order_product->price = $discount['price'];
            $order_product->discount = $product->price - $discount['price'];
          }
          else {
            $order_product->price = $product->price;
            $order_product->discount = 0;
          }
          $order_product->save();
        }
      }
      if ($coupon && $count_products['couponDisc']) {
        if ($coupon->used_id == 0) {
          $command = Yii::app()->db->createCommand();
          $command->update('store_coupon', array(
            'used_id' => 2,
            'time_used' => date('Y-m-d H:i:s'),
              ), 'id=:id', array(':id' => $coupon->id));
        }
      }
    }
    else
      throw new Exception('Ошибка записи заказа');
  }

  public function actionDelItem() {
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.catalog.models.Brand');
    Yii::import('application.controllers.ProfileController');

    if (isset($_POST['id'])) {
      $carts = Cart::model()->cartItem(ProfileController::getSession(), $_POST['id'])->findAll();
      foreach ($carts as $item) {
        $item->delete();
      }
      $cart = Cart::model()->shoppingCart(ProfileController::getSession())
              ->with('product.brand')->findAll();
      $profile = ProfileController::getProfile();
      echo $this->renderPartial('_cartItems', array('cart' => $cart, 'customer_profile' => $profile), TRUE);
    }
    Yii::app()->end();
  }

  public function actionCoupon() {
    if (isset($_GET['coupon'])) {
      Yii::import('application.modules.discount.models.Coupon');
      Yii::import('application.controllers.ProfileController');
      $coupon = Coupon::model()->findByAttributes(array(
        'code' => $_GET['coupon']), "used_id<>2 AND (date_limit>=:date OR date_limit IS NULL OR date_limit='0000-00-00')"
          , array(':date' => date('Y-m-d')));
      /* @var $coupon Coupon */
      if (is_null($coupon))
        $data = array('type' => 3, 'discount' => 0);
      else {
        if ($coupon->type_id == 0) {
          $profile = ProfileController::getProfile();
          switch ($profile->price_country) {
            case 'KZ':
              $value = $coupon->value_tenge;
              break;
            default :
              $value = $coupon->value;
          }
        }
        else
          $value = $coupon->value;
        $data = array('type' => $coupon->type_id, 'discount' => $value);
      }
      echo json_encode($data);
    }
    Yii::app()->end();
  }

  public function actionDelivery($ccode, $pcode) {
    Yii::import('application.modules.delivery.models.Delivery');
    Yii::import('application.controllers.ProfileController');
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.payments.models.Currency');

    $cart = Cart::model()->shoppingCart(ProfileController::getSession())->findAll();
    $order = new Order;
    $delivery = Delivery::model()->getDeliveryList($ccode, trim($pcode), $cart);
    if (is_array($delivery))
      $order->delivery_id = key($delivery);
    else
      $order->delivery_id = 1;


    $profile = ProfileController::getProfile();
    $currency = Currency::model()->findByAttributes(array(
      'country_code' => $profile->price_country));
    /* @var $currency Currency */
    echo $this->renderPartial('_delivery', array(
      'order' => $order,
      'delivery' => $delivery,
      'currency' => $currency,
        ), TRUE);
    Yii::app()->end();
  }

  private function sendConfirmOrderMessage($order, $profile, $coupon_discount = NULL) {
    $message = new YiiMailMessage('Ваш заказ');
    $message->view = 'confirmOrder';
    $params = array(
      'profile' => $profile,
      'order' => $order,
    );
    if ($coupon_discount > 0)
      $params['coupon_discount'] = $coupon_discount;
    $message->setBody($params, 'text/html');
    $message->setFrom(Yii::app()->params['infoEmail']);
    $message->setTo(array($profile->email => $profile->fio));
    Yii::app()->mail->send($message);

    $message->setSubject('Оповещение о заказе');
    $message->view = 'notifyOrder';
    $message->setBody($params, 'text/html');
    $message->setTo(array(Yii::app()->params['adminEmail']));
    Yii::app()->mail->send($message);
  }

}
