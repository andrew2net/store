<?php

/**
 * This is the model class for table "store_product_price".
 *
 * The followings are the available columns in table 'store_product_price':
 * @property string $product_id
 * @property string $price_id
 * @property string $price
 * 
 * relations
 * @property Product $product
 * @property Price $price_type 
 */
class ProductPrice extends CActiveRecord {

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_product_price';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('product_id, price_id', 'required'),
      array('product_id, price_id', 'length', 'max' => 11),
      array('price', 'length', 'max' => 12),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('product_id, price_id, price', 'safe', 'on' => 'search'),
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
      'price_type' => array(self::BELONGS_TO, 'Price', 'price_id'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'product_id' => 'Product',
      'price_id' => 'Price',
      'price' => 'Price',
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

    $criteria->compare('product_id', $this->product_id, true);
    $criteria->compare('price_id', $this->price_id, true);
    $criteria->compare('price', $this->price, true);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return ProductPrice the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

}
