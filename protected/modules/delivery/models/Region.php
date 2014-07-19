<?php

/**
 * This is the model class for table "store_city".
 *
 * The followings are the available columns in table 'store_city':
 * @property string $id
 * @property integer $type_id
 * @property string $country_code
 * @property string $name
 *
 * The followings are the available model relations:
 * @property DeliveryRate[] $deliveryRates
 * @property array $types
 * @property string $type
 * @property Country $country
 */
class Region extends CActiveRecord {

  private $types = array('Страна', 'Населенный пункт');

  public function getTypes() {
    return $this->types;
  }

  public function getType() {
    return $this->types[$this->type_id];
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_region';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('name', 'length', 'max' => 100),
      array('type_id, country_code', 'required'),
      array('name', 'nameValidate'),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, name', 'safe', 'on' => 'search'),
    );
  }

  public function nameValidate($attribute, $params) {
    if ($this->type_id == 1 && empty($this->$attribute))
      $this->addError($attribute, 'Необдходимо заполнить наименование населеного пункта.');
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'deliveryRates' => array(self::HAS_MANY, 'DeliveryRate', 'region_id'),
      'country' => array(self::HAS_ONE, 'Country', '', 'on' => 't.country_code=country.code'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'id' => 'ID',
      'type_id' => 'Тип региона',
      'country_code' => 'Страна',
      'name' => 'Населенный пункт',
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

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return City the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

}
