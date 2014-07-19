<?php

class DefaultController extends Controller {

  /**
   * @return array action filters
   */
  public function filters() {
    return array(
      array('auth.filters.AuthFilter'), // perform access control for CRUD operations
      'postOnly + delete', // we only allow deletion via POST request
    );
  }

  public function actionIndex() {
    $payment = new CActiveDataProvider(Payment::model());
    $this->render('index', array('payment' => $payment));
  }

  public function actionUpdate($id) {
    $payment = Payment::model()->findByPk($id);

    if (isset($_POST['Payment'])) {
      $payment->attributes = $_POST['Payment'];
      if ($payment->save())
        $this->redirect('/admin/payments');
    }

    $this->render('update', array('payment' => $payment));
  }

}