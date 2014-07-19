<?php

class DiscountController extends Controller {

  /**
   * @return array action filters
   */
  public function filters() {
    return array(
      array('auth.filters.AuthFilter'), // perform access control for CRUD operations
      'postOnly + delete', // we only allow deletion via POST request
    );
  }

  public function init() {
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.catalog.models.Brand');
    parent::init();
  }

  public function actionIndex() {
    $discount = new CActiveDataProvider(Discount::model());
    $this->render('index', array('discount' => $discount));
  }

  public function actionCreate() {
    $product = new Product('search');
    $product->unsetAttributes();
    if (isset($_GET['Product']))
      $product->attributes = $_GET['Product'];

    $discount = new Discount();

//     $this->performAjaxValidation($discount);
    if (isset($_POST['Discount'])) {
      $this->saveDiscount($discount);
    }

    $this->iniDiscountProduct($discount);

    $this->render('create', array('model' => $discount, 'product' => $product));
  }

  public function actionUpdate($id) {
    $products = new Product('search');
    $products->unsetAttributes();
    if (isset($_GET['Product']))
      $products->attributes = $_GET['Product'];

    $discount = Discount::model()->findByPk($id);

    if (isset($_POST['Discount'])) {
      $this->saveDiscount($discount);
    }

    $this->iniDiscountProduct($discount);

    $this->render('update', array('model' => $discount, 'product' => $products));
  }

  private function iniDiscountProduct($discount) {
    if (!isset($_GET['ajax']) && !isset($_POST['Discount'])) {
      $_SESSION['discount_product'] = array();
      foreach ($discount->product as $product)
        $_SESSION['discount_product'][] = $product->id;
    }
  }

  private function saveDiscount(Discount $discount) {
    $discount->attributes = $_POST['Discount'];
    if ($discount->save()) {
      $db_command = Yii::app()->db->createCommand();
      $db_command->delete('store_discount_category', 'discount_id=:id'
          , array(':id' => $discount->id));
      $db_command->reset();
      $db_command->delete('store_discount_product', 'discount_id=:id'
          , array(':id' => $discount->id));
      switch ($_POST['Discount']['product_id']) {

        case 1:
          if (isset($_POST['Categories']))
            foreach ($_POST['Categories'] as $key => $category) {
              $db_command->reset();
              $db_command->insert('store_discount_category', array(
                'discount_id' => $discount->id,
                'category_id' => $key,
              ));
            }
          break;

        case 2:
          if (isset($_SESSION['discount_product']))
            foreach ($_SESSION['discount_product'] as $product) {
              $db_command->reset();
              $db_command->insert('store_discount_product', array(
                'discount_id' => $discount->id,
                'product_id' => $product,
              ));
            }
          break;
      }
      unset($_SESSION['discount_product']);
      $this->redirect('index');
    }
  }

  public function actionDelete($id) {
    $model = Discount::model()->findByPk($id);

    $db_command = Yii::app()->db->createCommand();
    $db_command->delete('store_discount_category', 'discount_id=:id'
        , array(':id' => $model->id));
    $db_command->delete('store_discount_product', 'discount_id=:id'
        , array(':id' => $model->id));

    $model->delete();

    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    if (!isset($_GET['ajax']))
      $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
  }

  public function actionAddProduct($id) {
    $_SESSION['discount_product'][] = $id;
  }

  public function actionRemoveProduct($id) {
    $key = array_search($id, $_SESSION['discount_product']);
    if ($key !== FALSE)
      unset($_SESSION['discount_product'][$key]);
  }

  /**
   * Performs the AJAX validation.
   * @param Product $model the model to be validated
   */
  protected function performAjaxValidation($model) {
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'discount-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }

}