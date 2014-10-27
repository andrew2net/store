<?php

/**
 * This is the model class for table "store_pay_data".
 *
 * The followings are the available columns in table 'store_pay_data':
 * @property string $id
 * @property string $pay_id
 * @property string $name
 * @property string $value
 *
 * The followings are the available model relations:
 * @property Pay $pay
 */
class PayData extends CActiveRecord {

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_pay_data';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('pay_id, name, value', 'required'),
      array('pay_id', 'length', 'max' => 11),
      array('name, value', 'length', 'max' => 255),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, pay_id, name, value', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'pay' => array(self::BELONGS_TO, 'Pay', 'pay_id'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'id' => 'ID',
      'pay_id' => 'Pay',
      'name' => 'Name',
      'value' => 'Value',
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

    $criteria->compare('id', $this->id, true);
    $criteria->compare('pay_id', $this->pay_id, true);
    $criteria->compare('name', $this->name, true);
    $criteria->compare('value', $this->value, true);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return PayData the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

}
