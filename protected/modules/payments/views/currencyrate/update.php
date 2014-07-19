<?php
/* $this CurrencyrateController */
/* $model CurrencyRate */

$this->breadcrumbs = array('Курсы валют' => array('index'), 'Изменение');
?>
<h3>Изменение курса: <i><?php echo $model->from . ' к ' . $model->to . ' на ' . $model->date; ?></i></h3>

<?php $this->renderPartial('_form', array('model' => $model)); ?>