<?php

class SiteController extends Controller {

  public function actionPage($url) {
    Yii::import('application.modules.admin.models.Page');
    $page = new Page();
    $model = $page->findByAttributes(array('url' => $url));
    Yii::import('application.modules.catalog.models.Category');
    if (!$model)
      throw new CHttpException(404, "Страница {$url} не найдена");
    $this->setPageTitle(Yii::app()->name . ' - ' . $model->title);
    $this->render('page', array(
      'model' => $model,
        )
    );
  }

  /**
   * This is the default 'index' action that is invoked
   * when an action is not explicitly requested by users.
   */
  public function actionIndex() {
    Yii::import('application.modules.discount.models.Discount');
    Yii::import('application.modules.catalog.models.Category');
    Yii::import('application.modules.catalog.models.Price');

    if (!Yii::app()->user->isGuest)
      Yii::app()->request->cookies['popup'] = new CHttpCookie('popup', '2', array(
        'expire' => time() + 2592000,
        'path' => '/',
      ));

//    $searc = new Search;
    $price_type = Price::getPrice();

    $this->render('index', array(
      'price_type' => $price_type,
    ));
  }

  public function actionPrice() {
    Yii::import('application.modules.catalog.models.Top10');
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.catalog.models.Price');
    Yii::import('application.modules.payments.models.Currency');

    $currecy = Currency::model()->findByAttributes(array('country_code' => 'RU'));
    /* @var $currecy Currency */

    $price_type = Price::getPrice();
    $top10 = Product::model()->availableOnly()->top()->findAll();
    
    $result = array('title' => 'Ваша цена "' . $price_type->name . '"');
    foreach ($top10 as $item) {
      /* @var $item Product */
      $discount = $item->getActualDiscount();
      $price = $item->getTradePrice($price_type);
      if ($discount) {
        $result['top10'][$item->id]['disc'] = '<span>' . number_format($price, 0, '.', ' ') . '</span>' . $currecy->class;
        $price = number_format(round($price * (1 - $discount / 100)), 0, '.', ' ');
      }
      else {
        $price = number_format($price, 0, '.', ' ');
      }
      $result['top10'][$item->id]['price'] = $price . $currecy->class;
    }
    echo json_encode($result);
    Yii::app()->end();
  }

  /**
   * This is the action to handle external exceptions.
   */
  public function actionError() {
    if ($error = Yii::app()->errorHandler->error) {
      if (Yii::app()->request->isAjaxRequest)
        echo $error['message'];
      else
        $this->render('error', $error);
    }
  }

  public function actionSort() {
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.catalog.models.Category');
    Yii::import('application.modules.discount.models.Discount');

    $search = new Search;
    $giftSelection = new GiftSelection;
    $groups = Category::model()->roots()->findAll();
    $product = Product::model();

    if (isset($_GET['GiftSelection'])) {
      $product->sort($_GET['GiftSelection']);
      $giftSelection->attributes = $_GET['GiftSelection'];
    }

    $product_data = new CActiveDataProvider($product
        , array('pagination' => array('pageSize' => 20),
    ));

    $this->render('sort', array(
      'search' => $search,
      'giftSelection' => $giftSelection,
      'groups' => $groups,
      'product' => $product_data,
    ));
  }

  public function actionBrand($id) {
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.catalog.models.Category');
    Yii::import('application.modules.catalog.models.Brand');
    Yii::import('application.modules.discount.models.Discount');

    $search = new Search;
    $barnd = Brand::model()->findByPk($id);
    if (is_null($barnd))
      throw new CHttpException(404, "Страница " . Yii::app()->request->url . " не найдена");

    $giftSelection = new GiftSelection;
    $groups = Category::model()->roots()->findAll();
    $product = Product::model();
    $product->brandFilter($id)->discountOrder();
    $product_data = new CActiveDataProvider('Product'
        , array('criteria' => $product->getDbCriteria(),
      'pagination' => array('pageSize' => 20),
    ));

    $this->render('search', array(
      'search' => $search,
      'brand' => $barnd,
      'giftSelection' => $giftSelection,
      'groups' => $groups,
      'product' => $product_data,
    ));
  }

  public function actionAddToCart() {
    Yii::import('application.modules.catalog.models.Price');

    $old_price_type = Price::getPrice();
    self::addToCart($_POST['id'], $_POST['quantity']);
    $new_price_type = Price::getPrice();
    $result = array('refresh' => $old_price_type != $new_price_type);
    $result['cart'] = $this->cartLabel();
    $result['price'] = 'Установлена цена "' . $new_price_type->name . '"';
    echo json_encode($result);
    Yii::app()->end();
  }

  /**
   * Add or update shopping cart
   * @param type $id
   * @param type $quantity
   * @param type $change true if it's update, not add new item
   * @return type
   */
  public static function addToCart($id, $quantity, $change = FALSE) {

    if (!is_numeric($quantity))
      return;

    if ($quantity < 0)
      return;
    Yii::import('application.controllers.ProfileController');
    $session_id = ProfileController::getSession();

    $carts = Cart::model()->cartItem($session_id, $id)->findAll();
    if (isset($carts[0]))
      $cart = $carts[0];
    else {
      $cart = new Cart;
      if (Yii::app()->user->isGuest)
        $cart->session_id = $session_id;
      else
        $cart->user_id = Yii::app()->user->id;
      $cart->product_id = $id;
    }

    if ($change)
      $cart->quantity = $quantity;
    else
      $cart->quantity += $quantity;

    $cart->time = date('Y-m-d H:i:s');
    $cart->save();
  }

  /**
   * Return text for shoppingcart link label
   * @return string
   */
  public static function cartLabel() {
    Yii::import('application.controllers.ProfileController');
    $quantity = Cart::model()->countProduct(ProfileController::getSession())->findAll();
    if (!$quantity[0]->quantity)
      return 'Козина пуста';

    $tovar = array(1, 21, 31, 41, 51, 61, 71, 81, 91);
    $tovara = array(2, 3, 4, 22, 23, 24, 32, 33, 34, 42, 43, 44, 52, 53, 54, 62, 63, 64, 72, 73, 74, 82, 83, 84, 92, 93, 94);
    $tovarSuffix = ' товаров';
    if (array_search($quantity[0]->quantity, $tovar) !== FALSE)
      $tovarSuffix = ' товар';
    elseif (array_search($quantity[0]->quantity, $tovara) !== FALSE)
      $tovarSuffix = ' товара';

    return 'В корзине <span class="red" style="text-shadow:none">' . $quantity[0]->quantity . $tovarSuffix . '</span>';
  }

  public function actionRegistr() {
    if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $user = User::model()->findByAttributes(array('email' => $_POST['email']));
      if ($user)
        echo json_encode(array('result' => false, 'msg' => 'Пользователь с таким адресом уже зарегистрирован'));
      else {
        $session_id = '';
        if (!Yii::app()->user->isGuest)
          Yii::app()->user - logOut();
        else
          $session_id = ProfileController::getSession();

        $profile = CustomerProfile::model()->findByAttributes(array('email' => $_POST['email']));
        if (is_null($profile))
          $profile = new CustomerProfile;
        $profile->email = $_POST['email'];
        $profile->session_id = $session_id;
        $profile->save(FALSE);
        $this->registerUser($profile);
        echo json_encode(array('result' => true));
      }
    }
    else
      echo json_encode(array('result' => false, 'msg' => 'Адрес задан неверно'));
    Yii::app()->end();
  }

  public function actionSuggestCity($term) {

    function formatArray($element) {
      return $element['name_ru'];
    }

    $condition = 'net_city.name_ru LIKE :data';
    $city = strtr($term, array('%' => '\%', '_' => '\_'));
    $params = array(':data' => '%' . $city . '%');
    if (isset($_GET['country'])) {
      $condition .= ' AND net_country.code=:country';
      $params[':country'] = $_GET['country'];
    }elseif (Yii::app()->params['country']) {
      $condition .= ' AND net_country.code=:country';
      $params[':country'] = Yii::app()->params['country'];
    }

    $suggest_cities = Yii::app()->db->createCommand()
        ->select('net_city.name_ru')->from('net_city')
        ->leftJoin('net_country', 'net_country.id=net_city.country_id')
        ->where($condition, $params)->limit(10)
        ->group('net_city.name_ru')
        ->queryAll();
    if (is_array($suggest_cities))
      $suggest = array_map('formatArray', $suggest_cities);
    else
      $suggest = array();

    echo CJSON::encode($suggest);
    Yii::app()->end();
  }

  public function actionOrderSent() {
    $this->render('orderSent');
  }

  public function actionUpdateChild() {
    if (isset($_POST['id'])) {
      $profile = CustomerProfile::model()->findByAttributes(array(
        'user_id' => Yii::app()->user->id
      ));
      $child = Child::model()->findByPk($_POST['id'], 'profile_id=:id'
          , array(':id' => $profile->id));
      if (is_null($child))
        $child = new Child;
      $child->profile_id = $profile->id;
      if (isset($_POST['gender']))
        $child->gender_id = $_POST['gender'];
      $child->birthday = $_POST['birthday'];
      $child->name = $_POST['name'];
      if ($child->save()) {
        $child->afterFind();
        echo json_encode(array(
          'result' => TRUE,
          'html' => $this->renderPartial('_childUpdate', array('child' => $child), true)
        ));
      }
      else {
        $child->refresh();
        if ($_POST['id'] == 0)
          $output = $this->renderPartial('_childAdd', array('child' => $child), TRUE);
        else
          $output = $this->renderPartial('_childUpdate', array('child' => $child), TRUE);
        echo json_encode(array(
          'result' => FALSE,
          'html' => $output,
        ));
      }
    }
    Yii::app()->end();
  }

  public function actionDelChild() {
    if (isset($_POST['id'])) {
      $child = Child::model()->with('profile')->findByPk($_POST['id']
          , array(
        'condition' => 'profile.user_id=:uid',
        'params' => array(':uid' => Yii::app()->user->id)
          )
      );
      echo ($child && $child->delete());
    }
    Yii::app()->end();
  }

  public function actionPopupWindow() {
    $children = array();
    $popup_form = new PopupForm();

    if (isset($_POST['children'])) {
      if (!Yii::app()->user->isGuest) {
        echo json_encode(array(
          'result' => 'exist',
          'html' => $this->renderPartial('_popupIsLogin', array(), TRUE),
        ));
        Yii::app()->end();
      }
      $valid = TRUE;
      if (isset($_POST['PopupForm'])) {
        $user = User::model()->findByAttributes(array(
          'email' => $_POST['PopupForm']['email']
        ));
        if ($user) {
          echo json_encode(array(
            'result' => 'exist',
            'html' => $this->renderPartial('_popupEmailExist', array(
              'email' => $_POST['PopupForm']['email'],
                ), TRUE),
          ));
          Yii::app()->end();
        }
        $popup_form->attributes = $_POST['PopupForm'];
        $valid = $popup_form->validate() && $valid;
      }
      foreach ($_POST['children'] as $id => $value) {
        $child = new Child('popup');
        $child->attributes = $value;
        $valid = $child->validate() && $valid;
        $children[] = $child;
      }
      if ($valid) {
        $tr = Yii::app()->db->beginTransaction();
        try {
          $profile = CustomerProfile::model()->findByAttributes(array(
            'email' => $_POST['PopupForm']['email']
          ));
          if (is_null($profile)) {
            $profile = new CustomerProfile;
            $profile->email = $_POST['PopupForm']['email'];
            $profile->save(FALSE);
          }
          foreach ($_POST['children'] as $id => $value) {
            $child = new Child;
            $child->attributes = $value;
            $child->profile_id = $profile->id;
            $child->save();
          }
          $this->registerUser($profile);
          $tr->commit();
          Yii::import('application.modules.discount.models.Coupon');
          $coupon = new Coupon;
          $coupon->generateCode();
          $coupon->type_id = 0;
          $coupon->value = 400;
          $coupon->used_id = 0;
          $coupon->date_limit = date('d.m.Y', strtotime('+3 days'));
          if ($coupon->save()) {
            $message = new YiiMailMessage('Купон со скидкой');
            $message->view = 'coupon';
            $params = array(
              'profile' => $profile,
              'coupon' => $coupon,
            );
            $message->setBody($params, 'text/html');
            $message->setFrom(Yii::app()->params['infoEmail']);
            $message->setTo(array($profile->email => $profile->fio));
            Yii::app()->mail->send($message);
          }
          echo json_encode(array(
            'result' => 'register',
            'html' => $this->renderPartial('_popupRegister', NULL, TRUE),
          ));
          Yii::app()->end();
        } catch (Exception $e) {
          $tr->rollback();
        }
      }
      $par = array(
        'children' => $children,
        'popup_form' => $popup_form,
      );
      if (isset($_POST['suff']) && $_POST['suff'] != 0)
        $par['suff'] = $_POST['suff'];
      echo json_encode(array(
        'result' => 'error',
        'html' => $this->renderPartial('_popupForm', $par, TRUE)));
      Yii::app()->end();
    }
    else
      $children[] = new Child('popup');

    $this->renderPartial('_popupWindow', array(
      'children' => $children,
      'popup_form' => $popup_form,
    ));
  }

  public function actionCallback() {
    if (isset($_POST['phone'])) {
      $message = new YiiMailMessage('Заказ звонка');
      $message->view = 'callback';
      $p = new CHtmlPurifier;
      $params = array(
        'phone' => $p->purify($_POST['phone']),
        'name' => $p->purify($_POST['name']),
        'note' => $p->purify($_POST['note']),
      );
      $message->setBody($params, 'text/html');
      $message->setFrom(Yii::app()->params['infoEmail']);
      $message->setTo(array(Yii::app()->params['adminEmail']));
      Yii::app()->mail->send($message);
      echo 'ok';
    }
    Yii::app()->end();
  }

}