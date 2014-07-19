<?php

/*
 * 
 * 
 */

/**
 * Description of ImportFile
 *
 * 
 */
class ImportFile extends CFormModel {

  public $productFile;

  public function rules() {
    return array(
      array('productFile', 'file', 'types' => 'csv'),
    );
  }

  public function attributeLabels() {
    return array('productFile' => 'Загрузка товаров из файла');
  }

}

?>
