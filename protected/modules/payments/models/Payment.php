<?php

/**
 * This is the model class for table "store_payment".
 *
 * The followings are the available columns in table 'store_payment':
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $type_id
 * @property string $active
 * @property string $action_url 
 * @property string $sign_name 
 * @property string $sign_key
 *
 * The followings are the available model relations:
 * @property Order[] $orders
 * @property PaymentParams $params 
 * @property string $type 
 */
class Payment extends CActiveRecord {

  private static $types = array('Наличными', 'Монета', 'Wallet One', 'На расчетный счет');

  public static function getTypes() {
    return self::$types;
  }

  public function getType() {
    return self::$types[$this->type_id];
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_payment';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('name, description, type_id','required'),
      array('type_id', 'numerical', 'integerOnly' => true),
      array('name, action_url, sign_name, sign_key', 'length', 'max' => 255),
      array('active', 'boolean'),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, name, description', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'orders' => array(self::HAS_MANY, 'Order', 'payment_id'),
      'params' => array(self::HAS_MANY, 'PaymentParams', 'payment_id')
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'id' => 'ID',
      'name' => 'Наименование',
      'description' => 'Описание',
      'type' => 'Платежная система',
      'active' => 'Активный',
      'action_url' => 'URL страницы оплаты',
      'sign_name' => 'Наименование поля подписи',
      'sign_key' => 'Ключ подписи',
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
    $criteria->compare('name', $this->name, true);
    $criteria->compare('description', $this->description, true);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Payment the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public static function getPaymentList() {
    $models = self::model()->findAll('active=1');
    $list = array();
    foreach ($models as $payment) {
      /* @var $payment Payment */
      $output = CHtml::tag('div', array('class' => 'payment-' . ($payment->type_id > 0 ? 'cart' : 'cash')));
      $output .= CHtml::closeTag('div');
      $output .= CHtml::tag('div', array('style' => 'display:inline-block;width:320px;position:relative;bottom:8px'));
      $output .= CHtml::tag('div', array(
            'class' => 'bold',
            'style' => 'margin-bottom:5px',
              ), $payment->name);
      $output .= ' (' . $payment->description . ') ';
      $output .= CHtml::closeTag('div');
      $list[$payment->id] = $output;
    }
    return $list;
  }

}
