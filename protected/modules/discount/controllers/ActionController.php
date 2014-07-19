<?php

class ActionController extends Controller {

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
   * Displays a particular model.
   * @param integer $id the ID of the model to be displayed
   */
  public function actionView($id) {
    $this->render('view', array(
      'model' => $this->loadModel($id),
    ));
  }

  /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   */
  public function actionCreate() {
    $model = new Action;
    $advert = new Advert;
    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if (isset($_POST['Action'])) {
      $this->saveAction($model, $advert);
    }

    $this->render('create', array(
      'model' => $model,
      'advert' => $advert,
    ));
  }

  /**
   * Updates a particular model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id the ID of the model to be updated
   */
  public function actionUpdate($id) {
    $model = $this->loadModel($id);
    $advert = Advert::model()->findByPk($model->id);
    if (is_null($advert))
      $advert = new Advert;

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if (isset($_POST['Action'])) {
      $this->saveAction($model, $advert);
    }

    $this->render('update', array(
      'model' => $model,
      'advert' => $advert,
    ));
  }

  private function saveAction(Action &$model, Advert $advert) {

    $tr = $model->dbConnection->beginTransaction();
    try {
      $model->name = $_POST['Action']['name'];
      $old_file = Yii::getPathOfAlias('webroot') . $model->img;
      if ($_POST['Action']['img'] != $model->img) {
        if (strlen($_POST['Action']['img']) > 0) {
          $img_storage = '/images/' . Yii::app()->params['img_storage'] . '/action/';
          $uploaded_file = Yii::getPathOfAlias('webroot') . $_POST['Action']['img'];
          if (file_exists($uploaded_file)) {
            if ($model->isNewRecord)
              $model->save();
            $ext = substr($_POST['Action']['img'], strrpos($_POST['Action']['img'], '.'));
            $file_name = Yii::getPathOfAlias('webroot') . $img_storage . $model->id . $ext;
            rename(Yii::getPathOfAlias('webroot') . $_POST['Action']['img'], $file_name);
          }
        }
        if (strlen($model->img) > 0 &&
            basename($file_name) != basename($model->img) && file_exists($old_file))
          unlink($old_file);
        if (isset($file_name))
          $model->img = $img_storage . basename($file_name);
        else
          $model->img = '';
      }
      $model->attributes = $_POST['Action'];
      if ($model->save()) {
        if ($model->type_id == 1) {
          Yii::import('application.modules.catalog.models.Product');
          $pos = strpos($_POST['product'], ',');
          if ($pos)
            $article = substr($_POST['product'], 0, $pos);
          else
            $article = $_POST['product'];
          $product = Product::model()->findByAttributes(array('article' => $article));
          if ($product) {
            $advert->attributes = $_POST['Advert'];
            $advert->action_id = $model->id;
            $advert->product_id = $product->id;
            if ($advert->save()) {
              $tr->commit();
              $this->redirect(array('index'));
            }
            else
              $tr->rollback();
          }
        }
        else {
          $tr->commit();
          $this->redirect(array('index'));
        }
      }
      else
        $tr->rollback();
    } catch (Exception $e) {
      $tr->rollback();
      throw $e;
    }
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
        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
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
    $dataProvider = new CActiveDataProvider('Action');
    $this->render('index', array(
      'dataProvider' => $dataProvider,
    ));
  }

  /**
   * Manages all models.
   */
  public function actionAdmin() {
    $model = new Action('search');
    $model->unsetAttributes();  // clear any default values
    if (isset($_GET['Action'])) {
      $model->attributes = $_GET['Action'];
    }

    $this->render('admin', array(
      'model' => $model,
    ));
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return Action the loaded model
   * @throws CHttpException
   */
  public function loadModel($id) {
    $model = Action::model()->findByPk($id);
    if ($model === null) {
      throw new CHttpException(404, 'The requested page does not exist.');
    }
    return $model;
  }

  /**
   * Performs the AJAX validation.
   * @param Action $model the model to be validated
   */
  protected function performAjaxValidation($model) {
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'action-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }

  public function actionUpload() {
    $img_storage = DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR 
        . Yii::app()->params['img_storage']
        . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
    $uploaddir = Yii::getPathOfAlias('webroot') . $img_storage;
    $uploadfile = $uploaddir . Yii::app()->user->id . basename($_FILES['file']['name']);

    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
      echo $img_storage . Yii::app()->user->id . $_FILES['file']['name'];
    }
    else {
      echo "Possible file upload attack!\n";
    }
  }

  public function actionSuggestProduct($term) {
    $product = strtr($term, array('%' => '\%', '_' => '\_'));

    function formatArray($element) {
      return $element['article'] . ', ' . $element['name'];
    }

    $suggest_products = Yii::app()->db->createCommand()
        ->select('name, article')->from('store_product')
        ->where('name LIKE :data OR article LIKE :data'
            , array(':data' => '%' . $product . '%'))->limit(20)
        ->queryAll();
    if (is_array($suggest_products))
      $suggest = array_map('formatArray', $suggest_products);
    else
      $suggest = array();

    echo CJSON::encode($suggest);
    Yii::app()->end();
  }

}