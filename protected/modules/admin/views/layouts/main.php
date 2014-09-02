<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//RU" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />
    <link rel="icon" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/favicon.png" type="image/png" />

    <!-- blueprint CSS framework -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
    <!--[if lt IE 8]>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
    <![endif]-->

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
    <?php Yii::app()->bootstrap->register(); ?>

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
  </head>
  <body>

    <div id="topmenu">
      <?php
      $this->widget('bootstrap.widgets.TbNav', array(
        'type' => TbHtml::NAV_TYPE_TABS,
        'items' => array(
          array(
            'label' => 'Каталог',
            'url' => '/admin/catalog',
            'active' => $this->module instanceof CatalogModule,
            'visible' => Yii::app()->user->checkAccess('catalog.*') ||
            Yii::app()->user->checkAccess('catalog.product.*') ||
            Yii::app()->user->checkAccess('catalog.top10.*') ||
            Yii::app()->user->checkAccess('catalog.category.*') ||
            Yii::app()->user->checkAccess('catalog.brand.*') ||
            Yii::app()->user->checkAccess('catalog.feature.*') ||
            Yii::app()->user->checkAccess('catalog.price.*')
          ),
          array('label' => 'Скидки',
            'url' => '/admin/discount',
            'active' => $this->module instanceof DiscountModule,
            'visible' => Yii::app()->user->checkAccess('discount.*') ||
            Yii::app()->user->checkAccess('discount.discount.*') ||
            Yii::app()->user->checkAccess('discount.coupon.*') ||
            Yii::app()->user->checkAccess('discount.action.*')
          ),
          array('label' => 'Оплата',
            'url' => '/admin/payments',
            'active' => $this->module instanceof PaymentsModule,
            'visible' => Yii::app()->user->checkAccess('payments.*') ||
            Yii::app()->user->checkAccess('payments.default.*') ||
            Yii::app()->user->checkAccess('payments.currency.*') ||
            Yii::app()->user->checkAccess('payments.currencyrate.*')),
          array('label' => 'Доставка',
            'url' => '/admin/delivery',
            'active' => $this->module instanceof DeliveryModule,
            'visible' => Yii::app()->user->checkAccess('delivery.*') ||
            Yii::app()->user->checkAccess('delivery.delivery.*') ||
            Yii::app()->user->checkAccess('delivery.region.*')
          ),
          array(
            'label' => 'Пользователи',
            'url' => '/admin/user',
            'active' => $this->module instanceof UserModule,
            'visible' => Yii::app()->user->checkAccess('user.*')
          ),
          array(
            'label' => 'Права',
            'url' => '/admin/auth',
            'active' => $this->module instanceof AuthModule,
            'visible' => Yii::app()->user->checkAccess('auth.*')
          ),
          array(
            'label' => 'Страницы',
            'url' => '/admin/page',
            'active' => $this instanceof PageController,
            'visible' => Yii::app()->user->checkAccess('admin.page.*')
          ),
          array(
            'label' => 'Выход',
            'url' => '/admin/logout',
            'visible' => !Yii::app()->user->isGuest,
            'htmlOptions' => array('class' => 'pull-right'),
          ),
        )
          )
      );
      ?>
    </div>

    <div style="width: 95%; margin: 0 auto">
      <?php if (isset($this->breadcrumbs)): ?>
        <?php
        $this->widget('bootstrap.widgets.TbBreadcrumb', array(
          'homeUrl' => array('/admin'),
          'links' => $this->breadcrumbs,
        ));
        ?><!-- breadcrumbs -->
      <?php endif ?>
      <?php echo $content; ?>
    </div>

  </body>
</html>
