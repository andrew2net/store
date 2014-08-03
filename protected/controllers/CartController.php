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

    if (isset($_POST['CustomerProfile']['city']))
      $city = $_POST['CustomerProfile']['city'];
    else
      $city = $customer_profile->city;

    $order = new Order;
    if (isset($_POST['Order']))
      $order->attributes = $_POST['Order'];

//    $delivery = Delivery::model()->getDeliveryList($country_code, $post_code, '', $cart, $order);
    $delivery = array();
    if (isset($_SESSION['storage']['deliveries'])) {
      $delivery = $_SESSION['storage']['deliveries'];
      unset($_SESSION['storage']['deliveries']);
    }

    $payment = Payment::model()->getPaymentList();

    if (isset($_POST['CustomerProfile'])) {
      $customer_profile->attributes = $_POST['CustomerProfile'];
      if ($customer_profile->save()) {
        if (Yii::app()->user->isGuest) {
          $u = User::model()->findByAttributes(array(
            'email' => $_POST['User']['email']));
          if (is_null($u)) {
            ProfileController::registerUser($customer_profile, $user);
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
                $this->saveOrderProducts($order, $customer_profile, $profile, $user, $coupon, $count_products);

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
              $this->sendConfirmOrderMessage($order, $customer_profile, $profile, $count_products['couponDisc']);
              $this->redirect('orderSent');
            }
          }
        }
      }
      else
        $has_err = 'prof';
    }else {
      if (is_array($delivery))
        $order->delivery_id = key($delivery);
      else
        $order->delivery_id = 0;

      if (is_array($payment))
        $order->payment_id = key($payment);
      else
        $order->payment_id = 0;
    }

    Yii::import('application.modules.payments.models.Currency');
    Yii::import('application.modules.catalog.models.Price');
    $currency = Currency::model()->findByAttributes(array(
      'country_code' => $country_code));

    $price_type = Price::getPrice();

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
      'price_type' => $price_type,
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

  private function saveOrderProducts(Order $order, CustomerProfile $customer_profile, Profile $profile, User $user, $coupon, $count_products) {
    Yii::import('application.modules.payments.models.Currency');

    $order->attributes = $_POST['Order'];
    $order->delivery_summ = $_SESSION['storage']['delivery'][$_POST['Order']['delivery_id']]['summ'];
    $order->profile_id = $customer_profile->id;

    if ($customer_profile->entity_id == 1) {
      $field = ProfileField::model()->findByAttributes(array('varname' => 'legal_form'));
      $legal_forms = Profile::range($field->range);
      $order->fio = $legal_forms[$profile->legal_form] . ' ' . $profile->entity_name;
    }
    else
      $order->fio = $profile->first_name . ' ' . $profile->last_name;

    $order->email = $user->email;
    $order->phone = $customer_profile->phone;
    $order->country_code = $customer_profile->country_code;
    $order->post_code = $customer_profile->post_code;
    $order->city = (empty($customer_profile->city) ? $customer_profile->city_l : $customer_profile->city);
    $order->address = $customer_profile->address;
    $price_country = Yii::app()->params['mcurrency'] ? $customer_profile->price_country : Yii::app()->params['country'];
    $order->currency_code = Currency::model()->findByCountry($price_country)->code;
    $order->status_id = Yii::app()->params['order_new_status'];
    $order->time = date('Y-m-d H:i:s');

    if ($coupon && $count_products['couponDisc'])
      $order->coupon_id = $coupon->id;

    if ($order->save()) {
      $price = Price::getPrice();
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

  public function actionChangeCart() {
    Yii::import('application.modules.catalog.models.Price');
    Yii::import('application.controllers.SiteController');
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.catalog.models.Brand');
    Yii::import('application.controllers.ProfileController');
    $old_price_type = Price::getPrice();
    SiteController::addToCart($_POST['id'], $_POST['quantity'], TRUE);
    $new_price_type = Price::getPrice();

    if ($old_price_type == $new_price_type)
      echo FALSE;
    else
      $this->renderCartBody();

    Yii::app()->end();
  }

  public function actionDelItem() {
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.catalog.models.Brand');
    Yii::import('application.controllers.ProfileController');
    Yii::import('application.modules.catalog.models.Price');

    $old_price_type = Price::getPrice();
    if (isset($_POST['id'])) {
      $carts = Cart::model()->cartItem(ProfileController::getSession(), $_POST['id'])->findAll();
      foreach ($carts as $item) {
        $item->delete();
      }
      $new_price_type = Price::getPrice();
      $this->renderCartBody($old_price_type != $new_price_type);
    }
    Yii::app()->end();
  }

  private function renderCartBody($refreshPriceName = TRUE) {
    $cart = Cart::model()->shoppingCart(ProfileController::getSession())
            ->with('product.brand')->findAll();
    $profile = ProfileController::getProfile();
    $price_type = Price::getPrice();
    $result = array(
      'html' => $this->renderPartial('_cartItems', array(
        'cart' => $cart,
        'customer_profile' => $profile,
        'price_type' => $price_type,
          ), TRUE));
    $result['price_name'] = $refreshPriceName ? $price_type->name : $refreshPriceName;
    echo json_encode($result);
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

  public function actionDelivery($ccode, $pcode, $city, $delivery_id) {
    Yii::import('application.modules.delivery.models.Delivery');
    Yii::import('application.controllers.ProfileController');
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.payments.models.Currency');

    $cart = Cart::model()->shoppingCart(ProfileController::getSession())->findAll();
    $order = new Order;
    $delivery = Delivery::model()->getDeliveryList($ccode, trim($pcode), $city, $cart, $order);
    $_SESSION['storage']['deliveries'] = $delivery;
    if (is_array($delivery))
      $order->delivery_id = key($delivery);
    else
      $order->delivery_id = 1;


    $profile = ProfileController::getProfile();
    if (Yii::app()->params['mcurrency'])
      $currency = Currency::model()->findByAttributes(array(
        'country_code' => $profile->price_country));
    else
      $currency = Currency::model()->findByAttributes(array(
        'country_code' => Yii::app()->params['country']));
    /* @var $currency Currency */
    echo $this->renderPartial('_delivery', array(
      'order' => $order,
      'delivery' => $delivery,
      'currency' => $currency,
        ), TRUE);
    Yii::app()->end();
  }

  private function sendConfirmOrderMessage(Order $order, $customer_profile, $profile, $coupon_discount = NULL) {
    $message = new YiiMailMessage('Ваш заказ');
    $message->view = 'confirmOrder';
    $params = array(
      'profile' => $customer_profile,
      'order' => $order,
    );
    if ($coupon_discount > 0)
      $params['coupon_discount'] = $coupon_discount;
    $message->setBody($params, 'text/html');
    $message->setFrom(Yii::app()->params['infoEmail']);
    $message->setTo(array($order->email => $customer_profile->fio));
    Yii::app()->mail->send($message);

    $message->setSubject('Оповещение о заказе');
    $message->view = 'notifyOrder';
    $message->setBody($params, 'text/html');
    $message->setTo(array(Yii::app()->params['adminEmail']));
    Yii::app()->mail->send($message);
  }

  public function actionCheckEmail() {
    if (isset($_POST['email'])) {
      $user = User::model()->findByAttributes(array('email' => $_POST['email']));
      if (Yii::app()->user->isGuest)
        if (is_null($user))  //new user
          echo 'ok';
        else                 //need sign up
          $this->renderPartial('_cart-dialog', array('email' => $_POST['email']));
      else {
        if (is_null($user)) { //new email
//          Yii::app()->user->update(array('email' => $_POST['email']));
          echo 'ok';
        }
        else if ($user->id != Yii::app()->user->id)  //there is user with same email
          echo '';
        else                //signed up
          echo 'ok';
      }
    }
    else
      echo '';
    Yii::app()->end();
  }

}
