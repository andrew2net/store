<?php

/**
 * This is the model class for table "store_currency_rate".
 *
 * The followings are the available columns in table 'store_currency_rate':
 * @property string $date
 * @property string $from
 * @property integer $from_quantity
 * @property string $to
 * @property integer $to_quantity
 * @property double $rate
 *
 * The followings are the available model relations:
 * @property Currency $to0
 * @property Currency $from0
 */
class CurrencyRate extends CActiveRecord {

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_currency_rate';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('date, from, from_quantity, to, to_quantity, rate', 'required'),
      array('from_quantity, to_quantity', 'numerical', 'integerOnly' => true),
      array('rate', 'numerical'),
      array('from, to', 'length', 'max' => 3),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('date, from, from_quantity, to, to_quantity, rate', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'to0' => array(self::BELONGS_TO, 'Currency', 'to'),
      'from0' => array(self::BELONGS_TO, 'Currency', 'from'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'date' => 'Дата',
      'from' => 'Валюта',
      'from_quantity' => 'Кол-во',
      'to' => 'Валюта',
      'to_quantity' => 'Кол-во',
      'rate' => 'Курс',
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

    $criteria->compare('date', $this->date, true);
    $criteria->compare('from', $this->from, true);
    $criteria->compare('from_quantity', $this->from_quantity);
    $criteria->compare('to', $this->to, true);
    $criteria->compare('to_quantity', $this->to_quantity);
    $criteria->compare('rate', $this->rate);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return CurrencyRate the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function afterFind() {
    parent::afterFind();
    $this->date = Yii::app()->dateFormatter->format('dd.MM.yyyy', $this->date);
  }

  public function beforeSave() {
    $this->date = Yii::app()->dateFormatter->format('yyyy.MM.dd', $this->date);
    return parent::beforeSave();
  }

  public function getPrimaryKey() {
    $pk = parent::getPrimaryKey();
    if (is_array($pk) && isset($pk['date'])){
      $pk['date'] = Yii::app()->dateFormatter->format('yyyy.MM.dd', $pk['date']);
    }
    return $pk;
  }

}
