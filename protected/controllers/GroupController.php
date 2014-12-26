<?php

/**
 * Description of GroupController
 *
 */
class GroupController extends CController {

  public function actionIndex($id, array $filter = []) {
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

    $sizes = Yii::app()->params['page_sizes'];
    $pagination['pageSize'] = Yii::app()->request->getQuery('size', current($sizes));
    $data = Product::model()->searchCategory($group->id);
    $data->setPagination($pagination);
    $data->setSort(array('defaultOrder' => array('name' => CSort::SORT_ASC)));

    if (Yii::app()->params['category_default_view'] == 'table')
      $view = '//site/_items_inline';
    else
      $view = '//site/_items';

    if (Yii::app()->request->isAjaxRequest) {
      if (isset($filter['brands']) && count($filter['brands'])){
        $data->criteria->compare('brand_id', $filter['brands']);
      }
      $this->render($view, array(
        'group' => $group,
        'product' => $product,
        'data' => $data,
        'sizes' => $sizes,
        ));
    }
    else {
      $params = array(
        'product' => $product,
        'search' => $searc,
        'giftSelection' => $giftSelection,
        'groups' => $groups,
        'group' => $group,
        'view' => $view,
        'data' => $data,
        'sizes' => $sizes,
      );

      if (isset($_POST['currentPage']))
        $params['page'] = $_POST['currentPage'];

      $this->render('group', $params);
    }
  }

}
