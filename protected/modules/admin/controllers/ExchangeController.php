<?php

/**
 * Description of ExchangeController
 *
 */
class ExchangeController extends CController {

  public function actions() {
    return array(
      'quote' => array(
        'class' => 'CWebServiceAction',
      )
    );
  }
  
  public function setProduct(){
    return true;
  }

}
