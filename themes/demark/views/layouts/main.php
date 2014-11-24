<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="ru" />
    <link rel="icon" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/favicon.ico" type="image/ico" />
    <!--<link rel="shortcut icon" href="<?php // echo Yii::app()->createAbsoluteUrl('');       ?>/favicon.ico" type="image/x-icon" />-->
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
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/common.js'); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/cufon-yui.js'); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/RotondaC_400-RotondaC_700.font.js'); ?>
    <!--<script type="text/javascript" src="http://vk.com/js/api/share.js?90" charset="windows-1251"></script>-->
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <script type="text/javascript">
      (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
          (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
      })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

      ga('create', 'UA-54965120-1', 'auto');
      ga('send', 'pageview');

    </script>
  </head>

  <body>
    <!--<div style="font-size: 24pt; text-align: center" class="red">Сайт находится в разработке</div>-->
    <?php echo $content; ?>
    <!-- BEGIN JIVOSITE CODE {literal} -->
    <script type='text/javascript'>
      (function () {
        var widget_id = 'jYbyTAQCNV';
        var s = document.createElement('script');
        s.type = 'text/javascript';
        s.async = true;
        s.src = '//code.jivosite.com/script/widget/' + widget_id;
        var ss = document.getElementsByTagName('script')[0];
        ss.parentNode.insertBefore(s, ss);
      })();</script>
    <!-- {/literal} END JIVOSITE CODE --></body>
  <script type="text/javascript">
    $(document).ready(Cufon.replace(".cufon"));
    $(document).ready(function () {
      $(".fancybox").fancybox();
    });

    $('.selfem').click(function () {
      var em = 'office';
      em += String.fromCharCode(64);
      em += 'demark-tools.com';
      $(this).html('<a href="mailto:' + em + '?subject=Запрос информации">' + em + '</a>');
    });
  </script><!-- Yandex.Metrika counter -->
  <!-- Yandex.Metrika counter -->
  <!--<script type="text/javascript">
  var yaParams = {/*Здесь параметры визита*/};
  </script>-->

  <script type="text/javascript">
    (function (d, w, c) {
      (w[c] = w[c] || []).push(function () {
        try {
          w.yaCounter26247867 = new Ya.Metrika({id: 26247867,
            webvisor: true,
            clickmap: true,
            trackLinks: true,
            accurateTrackBounce: true, params: window.yaParams || {}});
        } catch (e) {
        }
      });

      var n = d.getElementsByTagName("script")[0],
              s = d.createElement("script"),
              f = function () {
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
  <noscript><div><img src="//mc.yandex.ru/watch/26247867" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
  <!-- /Yandex.Metrika counter -->
</html>
