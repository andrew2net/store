<?php
/* @var $group Category */
/* @var $limit int */

if (!isset($limit))
  $limit = 15;
?>
<div>
  <?php
  $pagination = array();
  if (isset($page))
    $pagination['currentPage'] = $page;
  
  $pagination['pageSize'] = Yii::app()->request->getQuery('size', 25);
  $sizes = array(25, 50, 100);

  $data = Product::model()->searchCategory($group->id);
  $data->setPagination($pagination);
  $widget = $this->widget('ListView', array(
    'dataProvider' => $data,
    'emptyText' => 'Товар отсутствует',
    'headerView' => '//site/_items_inline_header',
    'itemView' => '//site/_item_inline',
    'cssFile' => '/themes/' . Yii::app()->theme->name . '/css/listview.css',
    'template' => "{sizer}{sorter}{header}{items}{pager}",
    'sorterHeader' => 'Сортировать:',
    'sortableAttributes' => array('price'),
    'viewData' => array('sizes' => $sizes),
      )
  );
  ?>
</div>