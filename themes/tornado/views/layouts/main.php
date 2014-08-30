<?php /* @var $this CartController */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//RU" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="ru" />
    <link rel="icon" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/favicon.png" type="image/x-icon" />
    <!--<link rel="shortcut icon" href="<?php // echo Yii::app()->createAbsoluteUrl('');      ?>/favicon.ico" type="image/x-icon" />-->
    <!-- blueprint CSS framework -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->theme->name; ?>/css/screen.css" media="screen, projection" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
    <!--[if lt IE 8]>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
    <![endif]-->

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->theme->name; ?>/css/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

    <?php
    $cs = Yii::app()->clientScript;
    $cs->registerCssFile($cs->getCoreScriptUrl() . '/jui/css/base/jquery-ui.css');
    $cs->registerCssFile('/js/fancybox2/jquery.fancybox.css');
    $cs->registerScriptFile('/js/fancybox2/jquery.fancybox.js', CClientScript::POS_HEAD);
    ?>
    <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    <?php Yii::app()->clientScript->registerCoreScript('cookie'); ?>
    <?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/jquery.typing-0.3.0.js'); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/submenu.js'); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/cufon-yui.js'); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/RotondaC_400-RotondaC_700.font.js'); ?>
    <!--<script type="text/javascript" src="http://vk.com/js/api/share.js?90" charset="windows-1251"></script>-->
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
  </head>

  <body>
    <?php
    $this->renderPartial('//site/_topblock');
    $this->renderPartial('//site/_mainmenu');
    echo $content;
    $this->renderPartial('//site/_footer');
    ?>
  </body>
  <script type="text/javascript">
    $(document).ready(Cufon.replace(".cufon"));
    $(document).ready(function() {
      $(".fancybox").fancybox();
    });
  </script>
</html>
