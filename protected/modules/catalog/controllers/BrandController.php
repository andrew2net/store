<?php

class BrandController extends Controller {

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
    $model = new Brand;

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if (isset($_POST['Brand'])) {
      $this->saveBrand($_POST['Brand'], $model);
    }

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

    if (isset($_POST['Brand'])) {
      $this->saveBrand($_POST['Brand'], $model);
    }

    $this->render('update', array(
      'model' => $model,
    ));
  }

  private function saveBrand($post_brand, Brand &$model) {

    $model->name = $post_brand['name'];
    $old_file = Yii::getPathOfAlias('webroot') . $model->img;
    if ($post_brand['img'] != $model->img) {
      if (strlen($post_brand['img']) > 0) {
        $uploaded_file = Yii::getPathOfAlias('webroot') . $post_brand['img'];
        if (file_exists($uploaded_file)) {
          if ($model->isNewRecord)
            $model->save();
          $ext = substr($post_brand['img'], strrpos($post_brand['img'], '.'));
          $file_name = Yii::getPathOfAlias('webroot') . '/images/logo/' . $model->id . $ext;
          rename(Yii::getPathOfAlias('webroot') . $post_brand['img'], $file_name);
        }
      }
//      if (strlen($model->img) > 0 && file_exists($old_file))
//        unlink($old_file);
      if (isset($file_name))
        $model->img = '/images/logo/' . basename($file_name);
      else
        $model->img = '';
    }
    else
      $model->attributes = $post_brand;
    if ($model->save())
      $this->redirect(array('index'));
  }

  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'admin' page.
   * @param integer $id the ID of the model to be deleted
   */
  public function actionDelete($id) {
    $model = $this->loadModel($id);

    $old_file = Yii::getPathOfAlias('webroot') . $model->img;
    if (strlen($model->img) > 0 && file_exists($old_file))
      unlink($old_file);

    $model->delete();

    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    if (!isset($_GET['ajax']))
      $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
  }

  /**
   * Lists all models.
   */
  public function actionIndex() {
    $dataProvider = new CActiveDataProvider('Brand');
    $this->render('index', array(
      'dataProvider' => $dataProvider,
    ));
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return Brand the loaded model
   * @throws CHttpException
   */
  public function loadModel($id) {
    $model = Brand::model()->findByPk($id);
    if ($model === null)
      throw new CHttpException(404, 'The requested page does not exist.');
    return $model;
  }

  /**
   * Performs the AJAX validation.
   * @param Brand $model the model to be validated
   */
  protected function performAjaxValidation($model) {
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'brand-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }

  public function actionUpload() {
    $uploaddir = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
    $uploadfile = $uploaddir . Yii::app()->user->id . basename($_FILES['file']['name']);

    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
      echo '/uploads/temp/' . Yii::app()->user->id . $_FILES['file']['name'];
    }
    else {
      echo "Possible file upload attack!\n";
    }
  }

}
