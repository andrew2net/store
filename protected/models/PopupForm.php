<?php

/**
 * Description of PopupForm
 *
 * @author 
 */
class PopupForm extends CFormModel {

  public $email;
  public $accept;

  public function rules() {
    return array_merge(parent::rules(), array(
      array('email', 'required'),
      array('email', 'email'),
      array('accept', 'boolean'),
      array('accept', 'compare', 'compareValue'=>1),
    ));
  }

}
