<?php

/**
 * Description of DiscountController
 *
 */
class Discount_productController extends Controller {

  public function actionIndex() {
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.catalog.models.Category');
    Yii::import('application.modules.discount.models.Discount');

    if (Yii::app()->params['category_default_view'] == 'table')
      $view = '//site/_items_inline';
    else
      $view = '//site/_items';

    $search = new Search;
    $giftSelection = new GiftSelection;
    $groups = Category::model()->roots()->findAll();

    $criteria = new CDbCriteria;
    $criteria->scopes = array('discountOrder', 'discount');
    $params = array();
    if (isset($_GET['id'])) {
      $criteria->scopes['subCategory'] = array($_GET['id']);
      $params['group'] = Category::model()->findByPk($_GET['id']);
    }

    $product_data = new CActiveDataProvider('Product'
      , array('criteria' => $criteria,
      'pagination' => array('pageSize' => 20),
    ));
    $this->render('//search/search', array_merge(array(
      'search' => $search,
      'giftSelection' => $giftSelection,
      'groups' => $groups,
      'data' => $product_data,
      'view' => $view,
        ), $params)
    );
  }

}
