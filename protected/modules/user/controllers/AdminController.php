<?php

class AdminController extends Controller {

  public $defaultAction = 'admin';
//	public $layout='//layouts/column2';

  private $_model;

  /**
   * @return array action filters
   */
  public function filters() {
    return CMap::mergeArray(parent::filters(), array(
        array('auth.filters.AuthFilter'),
//			'accessControl', // perform access control for CRUD operations
    ));
  }

  /**
   * Specifies the access control rules.
   * This method is used by the 'accessControl' filter.
   * @return array access control rules
   */
//	public function accessRules()
//	{
//		return array(
//			array('allow', // allow admin user to perform 'admin' and 'delete' actions
//				'actions'=>array('admin','delete','create','update','view'),
//				'users'=>UserModule::getAdmins(),
//			),
//			array('deny',  // deny all users
//				'users'=>array('*'),
//			),
//		);
//	}
  /**
   * Manages all models.
   */
  public function actionAdmin() {
    $model = new User('search');
    $model->unsetAttributes();  // clear any default values
    if (isset($_GET['User']))
      $model->attributes = $_GET['User'];

    $this->render('index', array(
      'model' => $model,
    ));
    /* $dataProvider=new CActiveDataProvider('User', array(
      'pagination'=>array(
      'pageSize'=>Yii::app()->controller->module->user_page_size,
      ),
      ));

      $this->render('index',array(
      'dataProvider'=>$dataProvider,
      ));// */
  }

  /**
   * Displays a particular model.
   */
  public function actionView() {
    $model = $this->loadModel();
    $this->render('view', array(
      'model' => $model,
    ));
  }

  /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   */
  public function actionCreate() {
    $model = new User;
    $profile = new Profile;
    $customer_profile = new CustomerProfile;
    $this->performAjaxValidation(array($model, $profile));
    if (isset($_POST['User'])) {
      $model->attributes = $_POST['User'];
      $model->username = $_POST['User']['username'];
      $model->email = $_POST['User']['email'];
      $model->usernameGenerator();
      $model->activkey = Yii::app()->controller->module->encrypting(microtime() . $model->password);
      $profile->attributes = $_POST['Profile'];
      $profile->user_id = 0;
      if ($model->validate()) {
        $model->password = Yii::app()->controller->module->encrypting($model->password);
        if ($model->save()) {
          $profile->user_id = $model->id;
          $profile->save(FALSE);
          $customer_profile->save(false);
        }
        $this->redirect(array('/admin/user')); //,'id'=>$model->id));
      } //else
//        $profile->validate();
    }

    $this->render('create', array(
      'model' => $model,
      'profile' => $profile,
      'customer_profile' => $customer_profile,
    ));
  }

  /**
   * Updates a particular model.
   * If update is successful, the browser will be redirected to the 'view' page.
   */
  public function actionUpdate() {
    $model = $this->loadModel();
    /* @var $model User */
    
    /* @var $profile Profile */
    $profile = $model->profile;

    $customer_profile = CustomerProfile::model()->findByAttributes(array('user_id' => $model->id));
    /* @var $customer_profile CustomerProfile */
    if (!$customer_profile){
      $customer_profile = new CustomerProfile;
    }

    $this->performAjaxValidation(array($model, $profile));
    if (isset($_POST['User'])) {
      $model->attributes = $_POST['User'];
      $profile->attributes = $_POST['Profile'];
      $customer_profile->price_id = $_POST['CustomerProfile']['price_id'];

      if ($model->validate()) {
        $old_password = User::model()->notsafe()->findByPk($model->id);
        /* @var $old_password User */
        if ($old_password->password != $_POST['User']['password']) {
          $model->password = Yii::app()->controller->module->encrypting($_POST['User']['password']);
          $model->activkey = Yii::app()->controller->module->encrypting(microtime() . $_POST['User']['password']);
        }
        $model->save();
        $profile->save(FALSE);
        $customer_profile->save();
        $this->redirect(array('/admin/user')); //,'id'=>$model->id));
      } //else
//        $profile->validate();
    }

    $this->render('update', array(
      'model' => $model,
      'profile' => $profile,
      'customer_profile' => $customer_profile,
    ));
  }

  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'index' page.
   */
  public function actionDelete() {
    if (Yii::app()->request->isPostRequest) {
      // we only allow deletion via POST request
      $model = $this->loadModel();
      $profile = Profile::model()->findByPk($model->id);
      $customer_profile = CustomerProfile::model()->findByAttributes(array('user_id' => $model->id));
      $profile->delete();
      $model->delete();
      $customer_profile->delete();
      // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
      if (!isset($_POST['ajax']))
        $this->redirect(array('/user/admin'));
    } else
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
  }

  /**
   * Performs the AJAX validation.
   * @param CModel the model to be validated
   */
  protected function performAjaxValidation($validate) {
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
      echo CActiveForm::validate($validate);
      Yii::app()->end();
    }
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   */
  public function loadModel() {
    if ($this->_model === null) {
      if (isset($_GET['id']))
        $this->_model = User::model()->notsafe()->findbyPk($_GET['id']);
      if ($this->_model === null)
        throw new CHttpException(404, 'The requested page does not exist.');
    }
    return $this->_model;
  }

}
