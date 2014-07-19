<?php

/**
 * This is the model class for table "store_cart".
 *
 * The followings are the available columns in table 'store_cart':
 * @property string $session_id
 * @property integer $user_id
 * @property string $product_id
 * @property integer $quantity
 * @property string $time
 * @property Product $product
 */
class Cart extends CActiveRecord {

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_cart';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('product_id, time', 'required'),
      array('user_id, quantity', 'numerical', 'integerOnly' => true),
      array('session_id', 'length', 'max' => 32),
      array('product_id', 'length', 'max' => 11),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('session_id, user_id, product_id, quantity, time', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'session_id' => 'Session',
      'user_id' => 'User',
      'product_id' => 'Product',
      'quantity' => 'Quantity',
      'time' => 'Time',
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

    $criteria->compare('session_id', $this->session_id, true);
    $criteria->compare('user_id', $this->user_id);
    $criteria->compare('product_id', $this->product_id, true);
    $criteria->compare('quantity', $this->quantity);
    $criteria->compare('time', $this->time, true);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Cart the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function countProduct($session) {
    $this->shoppingCart($session);
    $this->getDbCriteria()->mergeWith(array(
        'select' => array('SUM(quantity) AS quantity'),
      ));
    return $this;
  }

  public function shoppingCart($session) {
    $this->getDbCriteria()->mergeWith(array(
      'condition' => "(session_id=:sid AND :sid<>'') OR (user_id=:uid AND :sid='')",
      'params' => array(
        ':sid' => $session,
        ':uid' => Yii::app()->user->isGuest ? '' : Yii::app()->user->id,
      ),
    ));
    return $this;
  }

  public function cartItem($session, $product) {
    $this->shoppingCart($session);
    $this->getDbCriteria()->mergeWith(array(
      'condition' => 'product_id=:id',
      'params' => array(':id' => $product),
    ));
    return $this;
  }

}
