<?php

/**
 * Description of GroupController
 *
 */
class GroupController extends CController {

  public function actionIndex($id) {
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.catalog.models.Category');
    Yii::import('application.modules.catalog.models.Brand');
    Yii::import('application.modules.discount.models.Discount');

    $groups = Category::model()->roots()->findAll();
    $group = Category::model()->findByPk($id);
    $searc = new Search;
    $giftSelection = new GiftSelection;
    $product = Product::model();

    if (Yii::app()->request->isAjaxRequest) {
      $this->render('//site/_items_inline', array(
        'group' => $group,
        'product' => $product,));
    }
    else {
      $params = array(
        'product' => $product,
        'search' => $searc,
        'giftSelection' => $giftSelection,
        'groups' => $groups,
        'group' => $group,
      );

      if (isset($_POST['currentPage']))
        $params['page'] = $_POST['currentPage'];

      $this->render('group', $params);
    }
  }

}
