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
    /* @var $group Category */
    $group = Category::model()->findByPk($id);
    if (!$group)
      throw new CHttpException(404, "Страница не найдена");
   
    if ($group->seo)
      Yii::app()->clientScript->registerMetaTag($group->seo, 'description');
      
    $searc = new Search;
    $giftSelection = new GiftSelection;
    $product = Product::model();

    if (Yii::app()->params['category_default_view'] == 'table')
      $view = '//site/_items_inline';
    else
      $view = '//site/_items';

    if (Yii::app()->request->isAjaxRequest) {
      $this->render($view, array(
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
        'view' => $view,
      );

      if (isset($_POST['currentPage']))
        $params['page'] = $_POST['currentPage'];

      $this->render('group', $params);
    }
  }

}
