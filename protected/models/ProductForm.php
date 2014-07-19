<?php

/**
 * Description of ProductForm
 *
 */
class ProductForm extends CFormModel {

  public $quantity;

  public function init() {
    parent::init();
    $this->quantity = 1;
  }

}

?>
