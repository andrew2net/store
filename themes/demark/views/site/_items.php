<?php
/* @var $group Category */
/* @var $limit int */
/* @var $data CActiveDataProvider */

Yii::import('application.modules.catalog.models.Price');
Yii::import('application.modules.payments.models.Currency');

//if (!isset($limit))
//  $limit = 15;
?>
<div>
  <?php
  $sizes = Yii::app()->params['page_sizes'];
  $pagination = array();
  if (isset($page))
    $pagination['currentPage'] = $page;
  $pagination['pageSize'] = Yii::app()->request->getQuery('size', current($sizes));
  $data = Product::model()->searchCategory($group->id);
  $data->setPagination($pagination);
  $data->setSort(array('defaultOrder' => array('name' => CSort::SORT_ASC)));

  if (isset($filter['brands']) && count($filter['brands'])) {
    $data->criteria->compare('brand_id', $filter['brands']);
  }

  $currency = Currency::model()->findByAttributes(array('country_code' => 'RU'));

  $widget = $this->widget('ListView', array(
    'id' => 'product-list',
    'dataProvider' => $data,
    'emptyText' => 'Товар отсутствует',
    'itemView' => '//site/_item',
    'cssFile' => Yii::app()->theme->baseUrl . '/css/listview.css',
    'template' => "{pager}{sizer}{sorter}{items}{pager}",
    'sorterHeader' => 'Сортировать:',
    'sortableAttributes' => array('name', 'price'),
    'viewData' => array('sizes' => $sizes, 'trade_price' => '', 'currency' => $currency),
      )
  );
  ?>
</div>