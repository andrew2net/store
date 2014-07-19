<?php

class RecoveryController extends Controller {

  public $defaultAction = 'recovery';
  public $layout = '//layouts/main';

  /**
   * Recovery password
   */
  public function actionRecovery() {
    $form = new UserRecoveryForm;
    if (Yii::app()->user->id) {
      $this->redirect(Yii::app()->controller->module->returnUrl);
    }
    else {
      $email = ((isset($_GET['email'])) ? $_GET['email'] : '');
      $activkey = ((isset($_GET['activkey'])) ? $_GET['activkey'] : '');
      if ($email && $activkey) {
        $form2 = new UserChangePassword;
        $find = User::model()->notsafe()->findByAttributes(array('email' => $email));
        if (isset($find) && $find->activkey == $activkey) {
          if (isset($_POST['UserChangePassword'])) {
            $form2->attributes = $_POST['UserChangePassword'];
            if ($form2->validate()) {
              $find->password = Yii::app()->controller->module->encrypting($form2->password);
              $find->activkey = Yii::app()->controller->module->encrypting(microtime() . $form2->password);
              if ($find->status == 0) {
                $find->status = 1;
              }
              $find->save();
//              $identity = new UserIdentity($find->username, $form2->password);
//              $identity->authenticate();
//              Yii::import('application.controllers.SiteController');
//              $session_id = SiteController::getSession();
//              Yii::app()->user->login($identity, 60 * 60 * 24 * 7);
//              $old_cart = Cart::model()->findAllByAttributes(array(
//                'session_id' => $session_id));
//              if (count($old_cart) > 0) {
//                $cart = Cart::model()->findAllByAttributes(array('user_id' => Yii::app()->user->id));
//                foreach ($cart as $item) {
//                  $item->delete();
//                }
//                foreach ($old_cart as $item) {
//                  $item->session_id = null;
//                  $item->user_id = Yii::app()->user->id;
//                  $item->update(array('session_id', 'user_id'));
//                }
//              }
              Yii::app()->user->setFlash('recoveryMessage', UserModule::t("New password is saved."));
              $this->redirect(Yii::app()->controller->module->recoveryUrl);
            }
          }
          $this->render('changepassword', array('form' => $form2));
        }
        else {
          Yii::app()->user->setFlash('recoveryMessage', UserModule::t("Incorrect recovery link."));
          $this->redirect(Yii::app()->controller->module->recoveryUrl);
        }
      }
      else {
        if (isset($_POST['UserRecoveryForm'])) {
          $form->attributes = $_POST['UserRecoveryForm'];
          if ($form->validate()) {
            $user = User::model()->notsafe()->findbyPk($form->user_id);
            $this->sendMail($user);
//            $activation_url = 'http://' . $_SERVER['HTTP_HOST'] . $this->createUrl(implode(Yii::app()->controller->module->recoveryUrl), array("activkey" => $user->activkey, "email" => $user->email));
//
//            $subject = UserModule::t("You have requested the password recovery site {site_name}", array(
//                  '{site_name}' => Yii::app()->name,
//            ));
//            $message = UserModule::t("You have requested the password recovery site {site_name}. To receive a new password, go to {activation_url}.", array(
//                  '{site_name}' => Yii::app()->name,
//                  '{activation_url}' => $activation_url,
//            ));
//
//            UserModule::sendMail($user->email, $subject, $message);

            Yii::app()->user->setFlash('recoveryMessage', UserModule::t("Please check your email. An instructions was sent to your email address."));
            $this->refresh();
          }
        }
        $this->render('recovery', array('form' => $form));
      }
    }
  }

  public function actionPasswRecover() {
    if (isset($_POST['email'])) {
      $user = User::model()->notsafe()->findByAttributes(array('email' => $_POST['email']));
      if (!is_null($user)) {
        $this->sendMail($user);
      }
      echo 'ok';
    }
    else if (isset($_POST['login'])) {
      $user = User::model()->notsafe()->findByAttributes(array('username' => $_POST['login']));
      if (is_null($user))
        $user = User::model()->notsafe()->findByAttributes(array('email' => $_POST['login']));
      if (!is_null($user)) {
        $this->sendMail($user);
        echo json_encode(array('result' => TRUE, 'email' => $user->email));
      }
      else
        echo json_encode(array('result' => FALSE));
    }
    else
      echo '';
    Yii::app()->end();
  }

  private function sendMail($user) {
    $profile = CustomerProfile::model()->findByAttributes(array('user_id' => $user->id));
    if (is_null($profile))
      $profile = CustomerProfile::model()->findByAttributes(array(
        'session_id' => SiteController::getSession()));
    $activation_url = 'http://' . $_SERVER['HTTP_HOST'] . $this->createUrl(implode(Yii::app()->controller->module->recoveryUrl), array("activkey" => $user->activkey, "email" => $user->email));
    $subject = UserModule::t("You have requested the password recovery site {site_name}", array(
          '{site_name}' => Yii::app()->name,
    ));
    $message = array(
      'site_name' => Yii::app()->name,
      'activation_url' => $activation_url,
    );
    $mail = new YiiMailMessage($subject);
    $mail->view = 'recoveryPassword';
    $params = array(
      'profile' => $profile,
      'message' => $message,
    );
    $mail->setBody($params, 'text/html');
    $mail->setFrom(Yii::app()->params['infoEmail']);
    $mail->setTo(array($user->email => is_null($profile) ? '' : $profile->fio));
    Yii::app()->mail->send($mail);
  }

}