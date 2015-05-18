<?php

/**
 * Description of GroupController
 *
 */
class GroupController extends Controller {

  public function actionIndex($id, array $filter = []) {
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.catalog.models.Category');
    Yii::import('application.modules.catalog.models.Brand');
    Yii::import('application.modules.discount.models.Discount');
      Yii::import('application.controllers.ProfileController');

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
      switch ($_GET['ajax']){
      case 'product-list':
      $this->renderPartial($view, array(
        'group' => $group,
        'product' => $product,
//        'data' => $data,
//        'sizes' => $sizes,
        'filter' => $filter,
      ));
      break;
      case 'news-list':
        $this->renderPartial('//site/_newsMini');
      }
    }
    else {
      $params = array(
        'product' => $product,
        'search' => $searc,
        'giftSelection' => $giftSelection,
        'groups' => $groups,
        'group' => $group,
        'view' => $view,
//        'data' => $data,
//        'sizes' => $sizes,
        'filter' => $filter,
      );

      if (isset($_POST['currentPage']))
        $params['page'] = $_POST['currentPage'];

      $this->render('group', $params);
    }
  }

}
