<?php
/* @var $group Category */
/* @var $data CActiveDataProvider */
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

  if (!isset($data))
    $data = Product::model()->searchCategory($group->id);
  $data->setPagination($pagination);
  $data->sort->defaultOrder ='category.name, t.name';
  $widget = $this->widget('ListView', array(
    'id' => 'product-list',
    'dataProvider' => $data,
    'emptyText' => 'Товар отсутствует',
    'headerView' => '//site/_items_inline_header',
    'itemView' => '//site/_item_inline',
    'cssFile' => Yii::app()->theme->baseUrl . '/css/listview.css',
    'template' => "{pager}{sizer}{sorter}{header}{items}{pager}",
    'sorterHeader' => 'Сортировать:',
    'sortableAttributes' => array('name', 'price'),
    'beforeAjaxUpdate' => 'scrollUp',
    'viewData' => array('sizes' => $sizes, 'trade_price' => $trade_price, 'currency' => $currency),
      )
  );
  ?>
</div>