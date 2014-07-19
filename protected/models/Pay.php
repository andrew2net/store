<?php

/**
 * This is the model class for table "store_pay".
 *
 * The followings are the available columns in table 'store_pay':
 * @property string $id
 * @property string $order_id
 * @property string $mnt_operation_id
 * @property string $mnt_amount
 * @property string $pay_system_id
 * @property string $mnt_corr_acc
 * @property string $time
 *
 * The followings are the available model relations:
 * @property Order $order
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
      array('mnt_operation_id, mnt_corr_acc', 'length', 'max' => 30),
      array('mnt_amount', 'length', 'max' => 12),
      array('pay_system_id', 'length', 'max' => 10),
      array('time', 'default', 'value' => date('Y-m-d H:i:s')),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, order_id, mnt_operation_id, mnt_amount, pay_system_id, mnt_corr_acc', 'safe', 'on' => 'search'),
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
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'id' => 'ID',
      'order_id' => 'Заказ',
      'mnt_operation_id' => 'Номер операции',
      'mnt_amount' => 'Сумма',
      'pay_system_id' => 'Платежная система',
      'mnt_corr_acc' => 'Корр.счет',
      'time' => 'Дата платежа'
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
    $criteria->compare('mnt_operation_id', $this->mnt_operation_id, true);
    $criteria->compare('mnt_amount', $this->mnt_amount, true);
    $criteria->compare('pay_system_id', $this->pay_system_id, true);
    $criteria->compare('mnt_corr_acc', $this->mnt_corr_acc, true);

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
