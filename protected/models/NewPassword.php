<?php

/**
 * Description of NewPassword
 *
 * @author 
 */
class NewPassword extends CFormModel {

  public $passw1;
  public $passw2;

  public function rules() {
    return array(
//      array('passw1, passw2', 'required'),
      array('passw1, passw2', 'length', 'max' => 128, 'min' => 6, 'message' => "Минимальная длина пароля 6 символов"),
      array('passw2', 'compare', 'compareAttribute' => 'passw1', 'message' => "Пароли не совпадают"),
    );
  }

  public function attributeLabels() {
    return array(
      'passw1' => 'Пароль',
      'passw2' => 'Подтверждение пароля',
    );
  }

}
