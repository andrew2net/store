<?php

class CatalogModule extends CWebModule {

  public $defaultController = 'product';

  public function init() {
    // this method is called when the module is being created
    // you may place code here to customize the module or the application
    // import the module-level models and components
    $this->setImport(array(
      'catalog.models.*',
      'catalog.components.*',
    ));
    Yii::app()->setComponents(array(
      'user' => array(
        'loginUrl' => Yii::app()->createUrl('/admin/login'),
//        'returnUrl' => Yii::app()->createAbsoluteUrl('/admin'),
        'class' => 'auth.components.AuthWebUser',
      )
        )
    );
    switch (TRUE) {
      case Yii::app()->user->checkAccess('catalog.*'):
        break;

      case Yii::app()->user->checkAccess('catalog.product.*'):
        break;

      case Yii::app()->user->checkAccess('catalog.top10.*'):
        $this->defaultController = 'top10';
        break;

      case Yii::app()->user->checkAccess('catalog.category.*'):
        $this->defaultController = 'category';
        break;

      case Yii::app()->user->checkAccess('catalog.brand.*'):
        $this->defaultController = 'brand';
        break;
    }
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
