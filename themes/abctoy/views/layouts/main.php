<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />
    <!--<link rel="icon" href="<?php // echo Yii::app()->createAbsoluteUrl('');   ?>/favicon.png" type="image/png" />-->
    <link rel="shortcut icon" href="<?php echo Yii::app()->createAbsoluteUrl(''); ?>/favicon.png" type="image/png" />
    <!-- blueprint CSS framework -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/abctoy/css/screen.css" media="screen, projection" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
    <!--[if lt IE 8]>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
    <![endif]-->

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/abctoy/css/main.css" />
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
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/themes/abctoy/js/submenu.js'); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/themes/abctoy/js/cufon-yui.js'); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/themes/abctoy/js/RotondaC_400-RotondaC_700.font.js'); ?>
    <!--<script type="text/javascript" src="http://vk.com/js/api/share.js?90" charset="windows-1251"></script>-->
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
  </head>

  <body>
    <?php echo $content; ?>
  </body>
  <script type="text/javascript">
    $(document).ready(Cufon.replace(".cufon"));
    $(document).ready(function() {
      $(".fancybox").fancybox();
    });
  </script>
  <!-- Yandex.Metrika counter -->
  <script type="text/javascript">
    (function(d, w, c) {
      (w[c] = w[c] || []).push(function() {
        try {
          w.yaCounter23309737 = new Ya.Metrika({id: 23309737,
            webvisor: true,
            clickmap: true,
            trackLinks: true,
            accurateTrackBounce: true});
        } catch (e) {
        }
      });

      var n = d.getElementsByTagName("script")[0],
              s = d.createElement("script"),
              f = function() {
        n.parentNode.insertBefore(s, n);
      };
      s.type = "text/javascript";
      s.async = true;
      s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

      if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
      } else {
        f();
      }
    })(document, window, "yandex_metrika_callbacks");
  </script>
  <noscript><div><img src="//mc.yandex.ru/watch/23309737" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
  <!-- /Yandex.Metrika counter -->
</html>
