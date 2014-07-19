<?php

class PaymentsModule extends CWebModule {

  public function init() {
    // this method is called when the module is being created
    // you may place code here to customize the module or the application
    // import the module-level models and components
    $this->setImport(array(
      'payments.models.*',
      'payments.components.*',
    ));
    Yii::app()->setComponents(array(
      'user' => array(
        'loginUrl' => Yii::app()->createUrl('/admin/login'),
//        'returnUrl' => Yii::app()->createAbsoluteUrl('/admin'),
        'class' => 'auth.components.AuthWebUser',
      )
        )
    );
  }

  public function beforeControllerAction($controller, $action) {
    if (parent::beforeControllerAction($controller, $action)) {
      // this method is called before any module controller action is performed
      // you may place customized code here
      return true;
    }
    else
      return false;
  }

}
