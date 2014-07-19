<?php

/**
 * Description of GiftSelection
 *
 * @author Greg Bakos <greg@londonfreelancers.co.uk>
 */
class GiftSelection extends CFormModel {

  public $gender;
  public $ageFrom;
  public $ageTo;
  public $priceFrom;
  public $priceTo;
  public $category;
  public $availableOnly;

  public function init() {
    parent::init();
    $this->gender = 0;
    $this->ageFrom = 1;
    $this->ageTo = 9;
    $this->availableOnly = 1;
    $this->priceFrom = 500;
    $this->priceTo = 4500;

    if (!Yii::app()->user->isGuest) {
    }
  }

  public function rules() {
    return array(
      array('gender, ageFrom, ageTo, priceFrom, priceTo, category, availableOnly', 'safe'),
    );
  }

  public function attributeLabels() {
    return array(
      'gender' => 'Пол ребенка',
      'category' => 'Категория товара',
      'ageFrom' => 'Возраст ребенка',
      'availableOnly' => 'Только в наличии',
      'priceFrom' => 'Цена товара',
    );
  }

}

