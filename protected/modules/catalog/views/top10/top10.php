<?php
/* @var $this ProductController */
/* @var $product Product 
 * @var $top10 Top10 */
?>

<?php
$this->breadcrumbs = array(
  'ТОП 10',
);
?>

<?php $this->beginContent('/catalog/menu'); ?>
<h3>Товары</h3>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
  'id' => 'product-grid',
  'dataProvider' => $product->searchTop10(),
  'filter' => $product,
  'columns' => array(
    'name',
    'article',
    array(
      'name' => 'brand_id',
      'value' => '$data->brand->name',
      'filter' => $product->getBrandOptions(),
    ),
    'remainder_RU',
    'price',
    array(
      'name' => 'show_me',
      'value' => '$data->show_me ? "Да" : "Нет"',
      'filter' => array(0 => 'Нет', 1 => 'Да'),
    ),
//    'top10.product_id'
    array(
      'class' => 'bootstrap.widgets.TbButtonColumn',
      'template' => '{add_product}',
      'buttons' => array('add_product' => array(
          'label' => 'Добавить',
          'url' => 'Yii::app()->controller->createUrl("addTop10", array("id" => $data->id))',
          'options' => array('title' => 'Добавить товар в список ТОП 10'),
          'click' => "function(){
              $.fn.yiiGridView.update('product-grid', {
                type:'POST',
                url:$(this).attr('href'),
                success:function(data) {
                  $.fn.yiiGridView.update('product-grid');
                  $.fn.yiiGridView.update('top10-grid');
                }
            })
            return false;
            }",
        ))
    ),
  )
    )
);
?>
<h3>ТОП 10</h3>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
  'id' => 'top10-grid',
  'dataProvider' => $top10,
  'columns' => array(
    'product.name',
    'product.article',
    array(
      'name' => 'product.brand_id',
      'value' => '$data->product->brand->name',
    ),
    'product.remainder_RU',
    'product.price',
    array(
      'name' => 'product.show_me',
      'value' => '$data->product->show_me ? "Да" : "Нет"',
    ),
    array(
      'class' => 'bootstrap.widgets.TbButtonColumn',
      'template' => '{remove_product}',
      'buttons' => array('remove_product' => array(
          'label' => 'Удалить',
          'url' => 'Yii::app()->controller->createUrl("removeTop10", array("id" => $data->product_id))',
          'options' => array('title' => 'Удалить товар из списка ТОП 10'),
          'click' => "function(){
              $.fn.yiiGridView.update('top10-grid', {
                type:'POST',
                url:$(this).attr('href'),
                success:function(data) {
                  $.fn.yiiGridView.update('product-grid');
                  $.fn.yiiGridView.update('top10-grid');
                }
            })
            return false;
            }",
        ))
    ),
  )
    )
);
?>
<?php $this->endContent(); ?>