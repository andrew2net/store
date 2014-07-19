<?php

/* @var $this Controller */

$this->widget('ext.bootstrap.widgets.TbNav', array(
  'type' => TbHtml::NAV_TYPE_TABS,
  'items' => array(
    array('label' => 'Скидки',
      'url' => '/admin/discount/discount',
      'active' => $this instanceof DiscountController,
      'visible' => Yii::app()->user->checkAccess('discount.discount.*') ||
      Yii::app()->user->checkAccess('discount.*')
    ),
    array('label' => 'Купоны',
      'url' => '/admin/discount/coupon',
      'active' => $this instanceof CouponController,
      'visible' => Yii::app()->user->checkAccess('discount.coupon.*') ||
      Yii::app()->user->checkAccess('discount.*')
    ),
    array('label' => 'Акции',
      'url' => '/admin/discount/action',
      'active' => $this instanceof ActionController,
      'visible' => Yii::app()->user->checkAccess('discount.action.*') ||
      Yii::app()->user->checkAccess('discount.*')
    ),
  )
));
?>
<?php echo $content; ?>
