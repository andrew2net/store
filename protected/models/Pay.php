<?php

/**
 * This is the model class for table "store_pay".
 *
 * The followings are the available columns in table 'store_pay':
 * @property string $id
 * @property string $order_id
 * @property string $operation_id
 * @property string $amount
 * @property string $pay_system_id
 * @property string $corr_acc
 * @property string $time
 * @property string $currency_iso 
 * @property string $currency_amount 
 *
 * The followings are the available model relations:
 * @property Order $order
 * @property Currency $currency 
 */
class Pay extends CActiveRecord {

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_pay';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('order_id', 'required'),
      array('order_id', 'length', 'max' => 11),
      array('operation_id, corr_acc', 'length', 'max' => 30),
      array('amount, currency_amount', 'length', 'max' => 12),
      array('pay_system_id', 'length', 'max' => 30),
      array('currency_iso', 'length', 'max' => 3),
      array('time', 'default', 'value' => date('Y-m-d H:i:s')),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, order_id, operation_id, amount, currency_amount, pay_system_id, corr_acc', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'order' => array(self::BELONGS_TO, 'Order', 'order_id'),
      'currency' => array(self::BELONGS_TO, 'Currency', 'currency_iso'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'id' => 'ID',
      'order_id' => 'Заказ',
      'operation_id' => 'Номер операции',
      'amount' => 'Сумма',
      'pay_system_id' => 'Платежная система',
      'corr_acc' => 'Корр.счет',
      'time' => 'Дата платежа',
      'currency_iso' => 'Код валюты ISO',
      'currency_amount' => 'Сумма в валюте платежа',
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
    $criteria->compare('order_id', $this->order_id, true);
    $criteria->compare('operation_id', $this->operation_id, true);
    $criteria->compare('amount', $this->amount, true);
    $criteria->compare('pay_system_id', $this->pay_system_id, true);
    $criteria->compare('corr_acc', $this->corr_acc, true);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Pay the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

}
