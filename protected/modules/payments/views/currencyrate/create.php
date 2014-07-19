<?php
/* $this CurrencyrateController */
/* $model CurrencyRate */

$this->breadcrumbs = array(
  'Курсы валют' => 'index',
  'Новый'
    )
?>
<h3>Новый курс валюты</h3>

<? $this->renderPartial('_form', array('model' => $model));