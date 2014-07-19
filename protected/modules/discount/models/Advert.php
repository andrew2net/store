<?php

/**
 * This is the model class for table "store_product_action".
 *
 * The followings are the available columns in table 'store_product_action':
 * @property string $product_id
 * @property string $action_id
 * @property string $text
 * @property string $date
 * @property float $price 
 *
 * The followings are the available model relations:
 * @property Product $product
 * @property Action $action
 */
class Advert extends CActiveRecord {

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_product_action';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('product_id, action_id', 'length', 'max' => 11),
      array('price', 'numerical'),
      array('product_id, text, date, price', 'required'),
      array('text, date', 'safe'),
      array('product_id', 'unsafe'),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('product_id, action_id, text, date', 'safe', 'on' => 'search'),
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
      'action' => array(self::BELONGS_TO, 'Action', 'action_id'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'product_id' => 'Товар',
      'action_id' => 'Акция',
      'text' => 'Текст',
      'date' => 'Дата',
      'price' => 'Цена',
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
    $criteria->compare('action_id', $this->action_id, true);
    $criteria->compare('text', $this->text, true);
    $criteria->compare('date', $this->date, true);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Advert the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function beforeSave() {

    if (!empty($this->date)) {
      $date = Yii::app()->dateFormatter->format('yyyy-MM-dd', $this->date);
      $this->date = $date;
    }

    parent::beforeSave();
    return TRUE;
  }

  public function afterFind() {

    if ($this->date == '0000-00-00')
      $date = '';
    else
      $date = Yii::app()->dateFormatter->format('dd.MM.yyyy', $this->date);
    $this->date = $date;

    parent::afterFind();
    return TRUE;
  }

}
