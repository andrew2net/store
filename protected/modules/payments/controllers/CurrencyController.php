<?php

/**
 *
 * 
 */
class CurrencyController extends Controller {

  public function filters() {
    return array(
      array('auth.filters.AuthFilter'),
      'postOnly + delete',
    );
  }

  public function actionIndex() {
    $model = new CActiveDataProvider('Currency');
    $this->render('index', array('model' => $model));
  }
  
  public function actionCreate(){
    $model = new Currency;
    
    if (isset($_POST['Currency'])){
      $model->attributes = $_POST['Currency'];
      if ($model->save())
        $this->redirect('index');
    }
    
    $this->render('create', array('model' => $model));
  }

  public function actionUpdate($id) {
    $model = Currency::model()->findByPk($id);

    if (isset($_POST['Currency'])) {
      $model->attributes = $_POST['Currency'];
      if ($model->save())
        $this->redirect('/payments/currency');
    }
    
    $this->render('update', array('model' => $model));
  }

}
