<?php

/**
 * Description of CurrencyRateController
 *
 */
class CurrencyrateController extends Controller {

  public function filters() {
    return array(
      array('auth.filters.AuthFilter'),
      'postOnly + delete',
    );
  }

  public function actionIndex() {
    $model = new CActiveDataProvider(CurrencyRate::model());
    $this->render('index', array('model' => $model));
  }

  public function actionCreate() {
    $model = new CurrencyRate;

    if (isset($_POST['CurrencyRate'])) {
      $model->attributes = $_POST['CurrencyRate'];
      if ($model->save())
        $this->redirect('index');
    }

    $this->render('create', array('model' => $model));
  }

  public function actionUpdate(array $id) {
    $model = $this->loadModel($id);

    if (isset($_POST['CurrencyRate'])) {
      $model->attributes = $_POST['CurrencyRate'];
      if ($model->save())
        $this->redirect('index');
    }

    $this->render('update', array('model' => $model));
  }

  public function actionDelete(array $id) {
    if (Yii::app()->request->isPostRequest) {
      $this->loadModel($id)->delete();
      if (!isset($_GET['ajax'])){
        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
      }
    }
  }

  private function loadModel($id) {
    $id['date'] = Yii::app()->dateFormatter->format('yyyy.MM.dd', $id['date']);
    return CurrencyRate::model()->findByPk(array($id));
  }

}
