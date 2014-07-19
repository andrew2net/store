<?php
/* @var $this AdmController */
/* @var $error array */

//$this->pageTitle=Yii::app()->name . ' - Error';
//$this->breadcrumbs=array(
//	'Error',
//);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="ru" />

  </head>

  <h2>Ошибка</h2>

  <div class="error">
    <?php echo CHtml::encode($this->_error['message']); ?>
  </div>
  <div>
    <?php
    $back_url = Yii::app()->request->urlReferrer;
    $url_host = parse_url($back_url, PHP_URL_HOST);
    $base_url = parse_url(Yii::app()->getBaseUrl(TRUE), PHP_URL_HOST);
    $link_text = 'Назад';
    if ($base_url != $url_host){
      $back_url = '/site';
      $link_text = 'На главную';
    }

    echo CHtml::link($link_text, $back_url);
    ?>
  </div>