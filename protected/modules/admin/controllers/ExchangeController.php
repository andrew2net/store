<?php

/**
 * Description of ExchangeController
 *
 */
class ExchangeController extends CController {

  public function init() {
    Yii::import('application.modules.catalog.models.Product');
    parent::init();
  }

  public function actions() {
    return array(
      'quote' => array(
        'class' => 'CWebServiceAction',
        'classMap' => array('Product'),
      )
    );
  }

  /**
   * @param Product $product 
   * @return boolean
   * @soap
   */
  public function setProduct($product) {
    return true;
  }

}
