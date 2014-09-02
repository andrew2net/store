<?php
/* @var $this DefaultController */
/* @var $model Order */
/* @var $product OrderProduct[] */
/* @var $form CActiveForm */

$this->breadcrumbs = array(
  'Заказы' => array('index'),
  'Обработка заказа',
);
?>

<h3>Заказ № <span id="order-num"><?php echo $model->id; ?></span></h3>

<?php
$this->renderPartial('_form', array(
  'model' => $model,
  'product' => $product,
));
?>