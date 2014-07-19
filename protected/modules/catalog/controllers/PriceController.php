<?php

/**
 * Description of PriceController
 *
 * @author 
 */
class PriceController extends Controller {

  public function filters() {
    return array(
      array('auth.filters.AuthFilter'),
      'postOnly + delete',
    );
  }

  public function actionIndex() {
    $data = new CActiveDataProvider('Price');
    $this->render('index', array('data' => $data));
  }

  public function actionCreate() {
    $model = new Price;

    if (isset($_POST['Price'])) {
      $model->attributes = $_POST['Price'];
      if ($model->save())
        $this->redirect('index');
    }

    $this->render('create', array('model' => $model));
  }

  public function actionUpdate($id) {
    $model = $this->loadModel($id);
    
    if (isset($_POST['Price'])){
      $model->attributes=$_POST['Price'];
      if ($model->save())
        $this->redirect('index');
    }
    
    $this->render('update', array('model' => $model));
  }

  public function loadModel($id) {
    $model = Price::model()->findByPk($id);
    if ($model === null) {
      throw new CHttpException(404, 'The requested page does not exist.');
    }
    return $model;
  }

}
