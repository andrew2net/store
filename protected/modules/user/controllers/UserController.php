<?php

class UserController extends Controller {

  /**
   * @var CActiveRecord the currently loaded data model instance.
   */
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
  public function accessRules() {
    return array(
      array('allow', // allow all users to perform 'index' and 'view' actions
        'actions' => array('index', 'view'),
        'users' => array('*'),
      ),
      array('deny', // deny all users
        'users' => array('*'),
      ),
    );
  }

  /**
   * Displays a particular model.
   */
  public function actionView() {
    $model = $this->loadModel();
    $orders = new CActiveDataProvider('Order', [
      'criteria' => [
        'with' => ['profile'],
        'condition' => 'profile.user_id=:uid',
        'params' => [':uid' => $model->id],
      ]
    ]);
    $this->render('view', array(
      'model' => $model,
      'orders' => $orders
    ));
  }

  /**
   * Lists all models.
   */
  public function actionIndex() {
    $model = new User('search');
    $model->unsetAttributes();  // clear any default values
    if (isset($_GET['User'])) {
      $model->attributes = $_GET['User'];
      Yii::app()->user->setState('User', $_GET['User']);
    }
    elseif (Yii::app()->user->hasState('User')) {
      $model->attributes = Yii::app()->user->getState('User');
    }

    if (isset($_GET['User_page']))
      Yii::app()->user->setState('User_page', $_GET['User_page']);
    elseif (isset($_GET['ajax']))
      Yii::app()->user->setState('User_page', NULL);
    elseif (Yii::app()->user->hasState('User_page'))
      $_GET['User_page'] = (int) Yii::app()->user->getState('User_page');

    if (isset($_GET['User_sort']))
      Yii::app()->user->setState('User_sort', $_GET['User_sort']);
    elseif (Yii::app()->user->hasState('User_sort'))
      $_GET['User_sort'] = Yii::app()->user->getState('User_sort');

    $this->render('index', array(
      'model' => $model,
    ));
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   */
  public function loadModel() {
    if ($this->_model === null) {
      if (isset($_GET['id']))
        $this->_model = User::model()->findbyPk($_GET['id']);
      if ($this->_model === null)
        throw new CHttpException(404, 'The requested page does not exist.');
    }
    return $this->_model;
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
   */
  public function loadUser($id = null) {
    if ($this->_model === null) {
      if ($id !== null || isset($_GET['id']))
        $this->_model = User::model()->findbyPk($id !== null ? $id : $_GET['id']);
      if ($this->_model === null)
        throw new CHttpException(404, 'The requested page does not exist.');
    }
    return $this->_model;
  }

}
