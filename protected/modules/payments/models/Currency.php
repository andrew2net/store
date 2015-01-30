<?php

/**
 * This is the model class for table "store_currency".
 *
 * The followings are the available columns in table 'store_currency':
 * @property string $code
 * @property string $name
 * @property string $short
 * @property string $country_code
 * @property string $class
 * @property string $iso 
 *
 * The followings are the available model relations:
 * @property Delivery[] $deliveries
 */
class Currency extends CActiveRecord {

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_currency';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('code, country_code, iso', 'required'),
      array('country_code', 'length', 'max' => 3),
      array('code, iso', 'length', 'max' => 3),
      array('class', 'length', 'max' => 255),
      array('name', 'length', 'max' => 20),
      array('short', 'length', 'max' => 5),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('code, name, short', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'deliveries' => array(self::HAS_MANY, 'Delivery', 'currency_code'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'code' => 'Код',
      'name' => 'Наименование',
      'short' => 'Сокращенное',
      'country_code' => 'Страна',
      'class' => 'CSS class',
      'iso' => 'Идентификатор валюты (ISO 4217)',
    );
  }

  /**
   * Retrieves a list of models based on the current search/filter conditions.
   *
   * Typical usecase:
   * - Initialize the model fields with values from filter form.
   * - Execute this method to get CActiveDataProvider instance which will filter
   * models according to data in model fields.
   * - Pass data provider to CGridView, CListView or any similar widget.
   *
   * @return CActiveDataProvider the data provider that can return the models
   * based on the search/filter conditions.
   */
  public function search() {
    // @todo Please modify the following code to remove attributes that should not be searched.

    $criteria = new CDbCriteria;

    $criteria->compare('code', $this->code, true);
    $criteria->compare('name', $this->name, true);
    $criteria->compare('short', $this->short, true);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  public static function findByCountry($code) {
    return self::model()->findByAttributes(array(
        'country_code' => $code));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Currency the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function getCss() {
    switch ($this->code) {
      case 'RUB':
        return 'ruble';
        break;
      case 'KZT':
        return 'tenge';
        break;
    }
    return '';
  }
  /**
   * Convert summ to the order currency
   * @param string $fromCurrencyCode
   * @param float $summ summa to converting
   * @return boolean return false if currency rate is not found
   */
  public function convert($fromCurrencyCode, &$summ) {
    Yii::import('application.modules.payments.models.CurrencyRate');
    
    if ($fromCurrencyCode != $this->code) {
      $curency_rate = CurrencyRate::model()->getRate($fromCurrencyCode, $this->code)->find();
      /* @var $curency_rate CurrencyRate */
      if ($curency_rate)
        $summ = round($summ * $curency_rate->rate * $curency_rate->to_quantity / $curency_rate->from_quantity);
      else {
        $curency_rate = CurrencyRate::model()->getRate($this->code, $fromCurrencyCode)->find();
        if ($curency_rate)
          $summ = round($summ * $curency_rate->from_quantity / $curency_rate->rate / $curency_rate->to_quantity);
        else
          return false;
      }
    } else
      $summ = round($summ);
    return true;
  }
}
