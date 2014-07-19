<?php

/**
 * This is the model class for table "store_order_product".
 *
 * The followings are the available columns in table 'store_order_product':
 * @property string $order_id
 * @property string $product_id
 * @property integer $quantity
 * @property string $price
 * @property integer $discount
 *
 * The followings are the available model relations:
 * @property Product $product
 * @property Order $order
 */
class OrderProduct extends CActiveRecord {

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_order_product';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('order_id, product_id', 'required'),
      array('quantity', 'numerical', 'integerOnly' => true),
      array('order_id, product_id', 'length', 'max' => 11),
      array('price', 'length', 'max' => 12),
      array('discount', 'length', 'max' => 12),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('order_id, product_id, quantity, price', 'safe', 'on' => 'search'),
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
      'order' => array(self::BELONGS_TO, 'Order', 'order_id'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'order_id' => 'Заказ',
      'product_id' => 'Товар',
      'quantity' => 'Количество',
      'price' => 'Цена',
      'discount' => 'Скидка',
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

    $criteria->compare('order_id', $this->order_id, true);
    $criteria->compare('product_id', $this->product_id, true);
    $criteria->compare('quantity', $this->quantity);
    $criteria->compare('price', $this->price, true);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return OrderProduct the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

}
