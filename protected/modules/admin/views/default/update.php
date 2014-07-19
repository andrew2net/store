<?php
/* @var $this DefaultController */
/* @var $model Order */
/* @var $order_product OrderProduct[] */
/* @var $product Product[] */
/* @var $form CActiveForm */

$this->breadcrumbs = array(
  'Заказы' => array('index'),
  'Обработка заказа',
);
?>

<h3>Заказ № <?php echo $model->id; ?></h3>

<?php
$this->renderPartial('_form', array(
  'model' => $model,
  'order_product' => $order_product,
  'product' => $product,
));
?>