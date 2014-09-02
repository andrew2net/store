<?php

/**
 * Description of ProfileController
 *
 */
class ProfileController extends Controller {

  private static $countries = array('RU' => 'Россия', 'KZ' => 'Казахстан');

  public function actionIndex() {
    Yii::import('application.modules.discount.models.Coupon');
    Yii::import('application.modules.payments.models.Payment');
    if (Yii::app()->user->isGuest)
      $this->redirect('/login');

    $user = User::model()->findByPk(Yii::app()->user->id);
    /* @var $user User */
    $profile = $user->profile;
    /* @var $profile Profile */

    $customer_profile = CustomerProfile::model()->findByAttributes(
        array('user_id' => Yii::app()->user->id));
    if (is_null($customer_profile)) {
      $customer_profile = new CustomerProfile;
      if (!Yii::app()->user->isGuest) {
        $customer_profile->user_id = Yii::app()->user->id;
        $customer_profile->save(false);
      }
    }

    if (Yii::app()->params['country']) {
      $customer_profile->country_code = Yii::app()->params['country'];
      $customer_profile->price_country = Yii::app()->params['cpuntry'];
    }

    $new_passw = new NewPassword;

    if (isset($_POST['CustomerProfile'])) {
      $tr = Yii::app()->db->beginTransaction();
      try {
        $valid = true;
        $profile->attributes = $_POST['Profile'];
        $valid = $profile->save() && $valid;
        $user->email = $_POST['User']['email'];
        $valid = $user->save() && $valid;
        $customer_profile->attributes = $_POST['CustomerProfile'];
        $valid = $customer_profile->save() && $valid;
        if ($valid) {
          Yii::app()->user->setFlash('saveProfile', "Контактная информация обновлена");
          $tr->commit();
          $this->redirect('profile');
        }
        else
          $tr->rollback();
      } catch (Exception $e) {
        $tr->rollback();
      }
    }

    $order = new CActiveDataProvider('Order', array(
      'criteria' => array(
        'condition' => 'profile_id = :profile_id',
        'params' => array(':profile_id' => $customer_profile->id),
        'with' => array(
          'orderProducts' => array('alias' => 'p'),
          'coupon' => array('alias' => 'c'),
          'payment',
          'currency',
        ),
        'together' => TRUE,
        'select' => array(
          'id',
          'time',
          'delivery_summ',
          'SUM(p.price*p.quantity) AS summ',
          'status_id'
        ),
        'group' => 't.id, t.time, t.status_id',
        'order' => 't.time DESC'
      ),
      'pagination' => array(
        'pagesize' => 6
      )
    ));

    $this->render('profile', array(
      'customer_profile' => $customer_profile,
      'user' => $user,
      'profile' => $profile,
      'order' => $order,
      'new_passw' => $new_passw,
    ));
  }

  public function actionChangepassw() {
    if (isset($_POST['passw1']) && isset($_POST['passw1'])) {
      $new_passw = new NewPassword;
      $new_passw->passw1 = $_POST['passw1'];
      $new_passw->passw2 = $_POST['passw2'];
      if ($new_passw->validate()) {
        $new_password = User::model()->notsafe()->findbyPk(Yii::app()->user->id);
        $new_password->password = UserModule::encrypting($new_passw->passw1);
        $new_password->activkey = UserModule::encrypting(microtime() . $new_passw->passw1);
        if ($new_password->save())
          echo json_encode(array('result' => TRUE, 'msg' => 'Новый пароль сохранен'));
      }
      else
        echo json_encode(array('result' => false, 'msg' => $new_passw->getError('passw1')));
    }
    Yii::app()->end();
  }

  public function actionLogin() {
    if (!Yii::app()->user->isGuest)
      $this->redirect('/profile');
    Yii::import('application.controllers.SiteController');
    $loginForm = new LoginForm;

    if ((isset($_POST['email']) || isset($_POST['login'])) && isset($_POST['passw']) ||
        isset($_POST['LoginForm'])) {
      if (isset($_POST['email']))
        $user = User::model()->findByAttributes(array('email' => $_POST['email']));
      else if (isset($_POST['login'])) {
        $user = User::model()->findByAttributes(array('username' => $_POST['login']));
        if (is_null($user))
          $user = User::model()->findByAttributes(array('email' => $_POST['login']));
      } elseif (isset($_POST['LoginForm'])) {
        $loginForm->attributes = $_POST['LoginForm'];
        if ($loginForm->validate()) {
          $user = User::model()->findByAttributes(array(
            'username' => $_POST['LoginForm']['username']
          ));
          if (is_null($user))
            $user = User::model()->findByAttributes(array(
              'email' => $_POST['LoginForm']['username']
            ));
        }
      }
      if (isset($user) && !is_null($user)) {
        if (isset($_POST['LoginForm']))
          $password = $_POST['LoginForm']['password'];
        else
          $password = $_POST['passw'];
        $identity = new UserIdentity($user->username, $password);
        if ($identity->authenticate()) {
          $user->lastvisit = time();
          $user->save();
          $session_id = self::getSession();
          Yii::app()->user->login($identity, 3600 * 24 * 7);
          if (isset($_POST['email'])) { //if login from shopping cart move items to profile
            $old_cart = Cart::model()->findAllByAttributes(array(
              'session_id' => $session_id));
            if (count($old_cart) > 0) {
              $cart = Cart::model()->findAllByAttributes(array('user_id' => $user->id));
              foreach ($cart as $item) {
                $item->delete();
              }
              foreach ($old_cart as $item) {
                $item->session_id = null;
                $item->user_id = $user->id;
                $item->update(array('session_id', 'user_id'));
              }
            }
            echo 'ok';
          }
          elseif (isset($_POST['login'])) {
            echo json_encode(array('result' => TRUE)); //, 'cart' => SiteController::cartLabel()));
          }
          else
            $this->redirect('profile');
          Yii::app()->end();
        }
      }
      //ajax return false if not login
      if (isset($_POST['login'])) { //if login was from main page
        echo json_encode(array('result' => FALSE));
        Yii::app()->end();
      }
      else if (isset($_POST['email'])) { //if login from shopping cart
        echo '';
        Yii::app()->end();
      }
    }

    $this->render('login', array('loginForm' => $loginForm));
  }

  public static function getSession() {
    if (!Yii::app()->user->isGuest)
      return '';

    if (isset(Yii::app()->request->cookies['cart']->value))
      $session_id = Yii::app()->request->cookies['cart']->value;
    else {
      $session_id = Yii::app()->session->sessionId;
    }
      $cookie = new CHttpCookie('cart', $session_id);
      $cookie->expire = time() + 60 * 60 * 24 * 30;
      $cookie->httpOnly = TRUE;
      Yii::app()->request->cookies['cart'] = $cookie;
    return $session_id;
  }

  /**
   * Logs out the current user and redirect to homepage.
   */
  public function actionLogout() {
    Yii::app()->user->logout();
    $this->redirect(Yii::app()->homeUrl);
  }

  public static function getProfile() {
    Yii::import('application.modules.catalog.models.Price');
    if (Yii::app()->user->isGuest)
      $profile = CustomerProfile::model()->with('price', 'user')->findByAttributes(array(
        'session_id' => self::getSession()));
    else
      $profile = CustomerProfile::model()->with('price', 'user')->findByAttributes(array(
        'user_id' => Yii::app()->user->id));

    if (is_null($profile)) {
      $profile = new CustomerProfile;
      if (Yii::app()->user->isGuest)
        $profile->session_id = self::getSession();
      else
        $profile->user_id = Yii::app()->user->id;
    }

    if (empty($profile->city) && empty($profile->city_l) || empty($profile->price_country)) {
      $req = new CHttpRequest;
      if (isset(Yii::app()->params['ip']))
        $ip = Yii::app()->params['ip'];
      else
        $ip = $req->userHostAddress;

      $int = sprintf("%u", ip2long($ip));
      if (empty($profile->city) && empty($profile->city_l)) {
        $ru_data = Yii::app()->db->createCommand("select * from (select * from net_ru where begin_ip<=$int order by begin_ip desc limit 1) as t where end_ip>=$int")->query();
        if ($row = $ru_data->read()) {
          $city_id = $row['city_id'];
          $ru_city = Yii::app()->db->createCommand("select * from net_city where id='$city_id'")->query();
          if ($city = $ru_city->read())
            $profile->city = $city['name_ru'];
        }
        if (empty($profile->city) && empty($profile->city_l)) {
          $glob_data = Yii::app()->db->createCommand("select * from (select * from net_city_ip where begin_ip<=$int order by begin_ip desc limit 1) as t where end_ip>=$int")->query();
          if ($row = $glob_data->read()) {
            $city_id = $row['city_id'];
            $glob_city = Yii::app()->db->createCommand("select * from net_city where id='$city_id'")->query();
            if ($city = $glob_city->read())
              $profile->city = $city['name_ru'];
          }
        }
        if (empty($profile->price_country) && isset($city) && is_array($city)) {
          $country_code = Yii::app()->db->createCommand()->select('code')->from('net_country')
                  ->where('id=:id', array(':id' => $city['country_id']))->query();
          if ($c_code = $country_code->read())
            $profile->price_country = $c_code['code'];
        }
      }
      if (empty($profile->price_country)) {
        $country_data = Yii::app()->db->createCommand("select * from (select * from net_country_ip where begin_ip<=$int order by begin_ip desc limit 1) as t where end_ip>=$int")->query();
        if ($row = $country_data->read()) {
          $country_id = $row['country_id'];
          $country_code = Yii::app()->db->createCommand()->select('code')->from('net_country')
                  ->where('id=:id', array(':id' => $country_id))->query();
          if ($c_code = $country_code->read())
            $profile->price_country = $c_code['code'];
        }
      }
      if (empty($profile->country_code))
        $profile->country_code = $profile->price_country;
      $profile->save(FALSE);
    }

    if ($profile->isNewRecord)
      $profile->save(FALSE);

    return $profile;
  }

  public function actionSaveCity() {
    if (isset($_POST['city'])) {
      $profile = $this->getProfile();
      $profile->city = $_POST['city'];
      $profile->save(FALSE);
    }
    Yii::app()->end();
  }

  public function actionSavecountry() {
    if (isset($_POST['country'])) {
      $profile = $this->getProfile();
      $profile->price_country = $_POST['country'];
      $profile->update('price_country');
    }
    Yii::app()->end();
  }

  public static function getCountries() {
    return self::$countries;
  }

  public static function getCountryName($code) {
    return self::$countries[$code];
  }

  public static function registerUser(CustomerProfile $customer_profile, Profile $profile, User $user) {
//    $user = new User;
//    $user->email = $profile->email;
    $user->usernameGenerator();
    $sourcePassword = User::generate_password();
    $user->activkey = UserModule::encrypting(microtime() . $sourcePassword);
    $user->password = UserModule::encrypting($sourcePassword);
    $user->superuser = 0;
    $user->lastvisit = time();
    $user->status = User::STATUS_ACTIVE;
    if ($user->save()) {
//      $profile = new Profile;
      $profile->user_id = $user->id;
      $profile->save();
      $identity = new UserIdentity($user->username, $sourcePassword);
      if ($identity->authenticate()) {
        Yii::app()->user->login($identity, 3600 * 24 * 7);

        $cart = Cart::model()->findAllByAttributes(array('session_id' => $customer_profile->session_id));
        foreach ($cart as $item) {
          $item->session_id = NULL;
          $item->user_id = Yii::app()->user->id;
          $item->update(array('session_id', 'user_id'));
        }

        $customer_profile->session_id = null;
        $customer_profile->user_id = $user->id;
        $customer_profile->update(array(
          'session_id',
          'user_id',
        ));

        $params = array(
          'profile' => $customer_profile,
          'login' => $user->email,
          'passw' => $sourcePassword,
        );
        $message = new YiiMailMessage('Личный кабинет');
        $message->view = 'registrInfo';
        $message->setBody($params, 'text/html');
        $message->setFrom(Yii::app()->params['infoEmail']);
        $message->setTo(array($user->email => $user->profile->first_name . ' ' . $user->profile->last_name));
        Yii::app()->mail->send($message);
      }
    }
  }

}
