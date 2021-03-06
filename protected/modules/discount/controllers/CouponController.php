<?php

class CouponController extends Controller {

  /**
   * @return array action filters
   */
  public function filters() {
    return array(
      array('auth.filters.AuthFilter'), // perform access control for CRUD operations
      'postOnly + delete', // we only allow deletion via POST request
    );
  }

  /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   */
  public function actionCreate() {
    $model = new Coupon;

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if (isset($_POST['Coupon'])) {
      $model->attributes = $_POST['Coupon'];
      if ($model->save()) {
        $this->redirect(array('index'));
      }
    }
    else
      $model->generateCode();

    $this->render('create', array(
      'model' => $model,
    ));
  }

  /**
   * Updates a particular model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id the ID of the model to be updated
   */
  public function actionUpdate($id) {
    $model = $this->loadModel($id);

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if (isset($_POST['Coupon'])) {
      $model->attributes = $_POST['Coupon'];
      if ($model->save()) {
        $this->redirect(array('index'));
      }
    }

    $this->render('update', array(
      'model' => $model,
    ));
  }

  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'admin' page.
   * @param integer $id the ID of the model to be deleted
   */
  public function actionDelete($id) {
    if (Yii::app()->request->isPostRequest) {
      // we only allow deletion via POST request
      $this->loadModel($id)->delete();

      // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
      if (!isset($_GET['ajax'])) {
        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
      }
    }
    else {
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }
  }

  /**
   * Lists all models.
   */
  public function actionIndex() {
    $model = new Coupon('search');
    $model->unsetAttributes();  // clear any default values
    if (isset($_GET['Coupon'])) {
      $model->attributes = $_GET['Coupon'];
      Yii::app()->user->setState('Coupon', $_GET['Coupon']);
    }
    elseif (Yii::app()->user->hasState('Coupon')) {
      $model->attributes = Yii::app()->user->getState('Coupon');
    }

    if (isset($_GET['Coupon_page']))
      Yii::app()->user->setState('Coupon_page', $_GET['Coupon_page']);
    elseif (isset($_GET['ajax']))
      Yii::app()->user->setState('Coupon_page', NULL);
    elseif (Yii::app()->user->hasState('Coupon_page'))
      $_GET['Coupon_page'] = (int) Yii::app()->user->getState('Coupon_page');

    if (isset($_GET['Coupon_sort']))
      Yii::app()->user->setState('Coupon_sort', $_GET['Coupon_sort']);
    elseif (Yii::app()->user->hasState('Coupon_sort'))
      $_GET['Coupon_sort'] = Yii::app()->user->getState('Coupon_sort');

    $this->render('index', array(
      'model' => $model,
    ));
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return Coupon the loaded model
   * @throws CHttpException
   */
  public function loadModel($id) {
    $model = Coupon::model()->findByPk($id);
    if ($model === null) {
      throw new CHttpException(404, 'The requested page does not exist.');
    }
    return $model;
  }

  /**
   * Performs the AJAX validation.
   * @param Coupon $model the model to be validated
   */
  protected function performAjaxValidation($model) {
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'coupon-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }

}