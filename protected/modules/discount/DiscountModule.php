<?php

class DiscountModule extends CWebModule
{
  public $defaultController='discount';

  public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'discount.models.*',
			'discount.components.*',
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
      case Yii::app()->user->checkAccess('discount.*'):
        break;

      case Yii::app()->user->checkAccess('discount.discount.*'):
        break;

      case Yii::app()->user->checkAccess('discount.coupon.*'):
        $this->defaultController = 'coupon';
        break;
    }
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
