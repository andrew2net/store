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
    if (isset($_GET['Product'])){
      $product->attributes = $_GET['Product'];
      Yii::app()->user->setState('Product', $_GET['Product']);
    }elseif (Yii::app()->user->hasState('Product')) {
      $product->attributes = Yii::app()->user->getState('Product');
    }

    if (isset($_GET['Product_page']))
      Yii::app()->user->setState('Product_page', $_GET['Product_page']);
    elseif (isset($_GET['ajax']))
      Yii::app()->user->setState('Product_page', NULL);
    elseif (Yii::app()->user->hasState('Product_page'))
      $_GET['Product_page'] = (int) Yii::app()->user->getState('Product_page');

    if (isset($_GET['Product_sort']))
      Yii::app()->user->setState('Product_sort', $_GET['Product_sort']);
    elseif (Yii::app()->user->hasState('Product_sort'))
      $_GET['Product_sort'] = Yii::app()->user->getState('Product_sort');

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
