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
//  if (isset($page))
//    $pagination['currentPage'] = $page;

  $trade_price = Price::getPrice();
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