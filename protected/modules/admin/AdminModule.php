<?php

class AdminModule extends CWebModule {

	/**
	 * @var int
	 * @desc items on page
	 */
	public $user_page_size = 10;
	
  /**
   * @var int
   * @desc Remember Me Time (seconds), defalt = 2592000 (30 days)
   */
  public $rememberMeTime = 2592000; // 30 days

  public function init() {
    // this method is called when the module is being created
    // you may place code here to customize the module or the application
    // import the module-level models and components
    $this->layout = 'main';
    $this->setImport(array(
      'admin.models.*',
      'admin.components.*',
        )
    );
    Yii::app()->setComponents(array(
      'user' => array(
        'loginUrl' => Yii::app()->createUrl('/admin/login'),
        'class' => 'auth.components.AuthWebUser',
      )
        )
    );
    Yii::app()->bootstrap->register();
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
