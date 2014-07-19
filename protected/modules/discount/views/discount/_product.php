<?php
/* @var $this DiscountController */
/* @var $poduct Product */
?>
<label>Товар</label>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
  'id' => 'product-no-discount',
  'dataProvider' => $product->searchDiscount(),
  'filter' => $product,
  'columns' => array(
    'name',
    'article',
    array(
      'name' => 'brand_id',
      'value' => '$data->brand->name',
      'filter' => $product->getBrandOptions(),
    ),
    'remainder',
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
          'url' => 'Yii::app()->controller->createUrl("addProduct", array("id" => $data->id))',
          'options' => array('title' => 'Назначить скидку на товар'),
          'click' => "function(){
              $.fn.yiiGridView.update('product-no-discount', {
                type:'POST',
                url:$(this).attr('href'),
                success:function(data) {
                  $.fn.yiiGridView.update('product-no-discount');
                  $.fn.yiiGridView.update('product-discount');
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
<label>Выбранный товар</label>
<?php
$criteria = new CDbCriteria;
$criteria->addInCondition('id', $_SESSION['discount_product']);
$selectProduct = new CActiveDataProvider(Product::model(), array(
  'pagination' => array('pageSize' => 5),
  'criteria' => $criteria,
    ));
$this->widget('bootstrap.widgets.TbGridView', array(
  'id' => 'product-discount',
  'dataProvider' => $selectProduct,
  'columns' => array(
    'name',
    'article',
    array(
      'name' => 'brand_id',
      'value' => '$data->brand->name',
    ),
    'remainder',
    'price',
    array(
      'name' => 'show_me',
      'value' => '$data->show_me ? "Да" : "Нет"',
    ),
    array(
      'class' => 'bootstrap.widgets.TbButtonColumn',
      'template' => '{remove_product}',
      'buttons' => array('remove_product' => array(
          'label' => 'Удалить',
          'url' => 'Yii::app()->controller->createUrl("removeProduct", array("id" => $data->id))',
          'options' => array('title' => 'Удалить товар из списка ТОП 10'),
          'click' => "function(){
              $.fn.yiiGridView.update('product-discount', {
                type:'POST',
                url:$(this).attr('href'),
                success:function(data) {
                  $.fn.yiiGridView.update('product-no-discount');
                  $.fn.yiiGridView.update('product-discount');
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