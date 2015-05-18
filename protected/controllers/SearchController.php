<?php

/**
 * Description of SearchController
 *
 */
class SearchController extends Controller {

  public function actionIndex() {
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.catalog.models.Category');
    Yii::import('application.modules.discount.models.Discount');

    $search = new Search;
    $giftSelection = new GiftSelection;
    $groups = Category::model()->roots()->findAll();
    $product = Product::model();

    if (Yii::app()->params['category_default_view'] == 'table')
      $view = '//site/_items_inline';
    else
      $view = '//site/_items';

    if (isset($_GET['Search'])) {
      $search->text = $_GET['Search']['text'];
      $product->searchByName($_GET['Search']['text']);
    }

    if (!Yii::app()->params['showStockOut']){
      $product->availableOnly();
    }
    $product->discountOrder();
    $product_data = new CActiveDataProvider('Product'
        , array('criteria' => $product->getDbCriteria(),
      'pagination' => array('pageSize' => 20),
    ));

    $this->render('search', array(
      'search' => $search,
      'giftSelection' => $giftSelection,
      'groups' => $groups,
      'data' => $product_data,
      'isSearch' => true,
      'view' => $view,
    ));
  }

}
