<?php
/* @var $group Category */
/* @var $limit int */

Yii::import('application.modules.catalog.models.Price');
Yii::import('application.modules.payments.models.Currency');

//if (!isset($limit))
//  $limit = 15;
?>
<div>
  <?php
  $pagination = array();
  if (isset($page))
    $pagination['currentPage'] = $page;

  $sizes = Yii::app()->params['page_sizes'];
  $pagination['pageSize'] = Yii::app()->request->getQuery('size', current($sizes));

  $trade_price = Price::getPrice();
  $currency = Currency::model()->findByAttributes(array('country_code' => 'RU'));

  $data = Product::model()->searchCategory($group->id);
  $data->setPagination($pagination);
  $data->setSort(array('defaultOrder' => array('name' => CSort::SORT_ASC)));
  $widget = $this->widget('ListView', array(
    'id' => 'product-list',
    'dataProvider' => $data,
    'emptyText' => 'Товар отсутствует',
    'itemView' => '//site/_item',
    'cssFile' => Yii::app()->theme->baseUrl . '/css/listview.css',
    'template' => "{sizer}{sorter}{items}{pager}",
    'sorterHeader' => 'Сортировать:',
    'sortableAttributes' => array('price'),
    'viewData' => array('sizes' => $sizes, 'trade_price' => '', 'currency' => $currency),
      )
  );
  ?>
</div>