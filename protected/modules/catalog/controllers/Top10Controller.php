<?php

class Top10Controller extends Controller {

  public function filters() {
    return array(
      array('auth.filters.AuthFilter'),
    );
  }

  public function actionIndex() {
    $product = new Product('search');
    $top10 = new CActiveDataProvider(Top10::model(), array(
      'pagination' => array('pageSize' => 5),
    ));
    $product->unsetAttributes();
    if (isset($_GET['Product']))
      $product->attributes = $_GET['Product'];
    $this->render('top10', array('product' => $product, 'top10' => $top10));
  }

  public function actionAddTop10($id) {
    $top10 = new Top10();
    $top10->product_id = $id;
      $top10->save();
  }

  public function actionRemoveTop10($id) {
    $top10 = Top10::model()->findByPk($id);
    $top10->delete();
  }

}

?>
