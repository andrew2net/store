<?php
/* @var $group Category */
/* @var $limit int */

Yii::import('application.modules.catalog.models.Price');
Yii::import('application.modules.payments.models.Currency');

if (!isset($limit))
  $limit = 15;
?>
<div>
  <?php
  $pagination = array();
  if (isset($page))
    $pagination['currentPage'] = $page;

  $pagination['pageSize'] = Yii::app()->request->getQuery('size', 25);
  $sizes = Yii::app()->params['page_sizes'];// array(25, 50, 100);

  $trade_price = Price::getPrice();
  $currency = Currency::model()->findByAttributes(array('country_code' => 'RU'));

  $data = Product::model()->searchCategory($group->id);
  $data->setPagination($pagination);
  $widget = $this->widget('ListView', array(
    'id' => 'inline-product-list',
    'dataProvider' => $data,
    'emptyText' => 'Товар отсутствует',
    'headerView' => '//site/_items_inline_header',
    'itemView' => '//site/_item_inline',
    'cssFile' => Yii::app()->theme->baseUrl . '/css/listview.css',
    'template' => "{sizer}{sorter}{header}{items}{pager}",
    'sorterHeader' => 'Сортировать:',
    'sortableAttributes' => array('price'),
    'afterAjaxUpdate' => 'showTooltip',
    'viewData' => array('sizes' => $sizes, 'trade_price' => $trade_price, 'currency' => $currency),
      )
  );
  ?>
</div>