<?php

/**
 * This is the model class for table "store_feature".
 *
 * The followings are the available columns in table 'store_feature':
 * @property string $id
 * @property string $name
 * @property integer $type_id
 * @property integer $search
 * @property string $unit
 *
 * The followings are the available model relations:
 * @property CategoryFeature[] $categoryFeatures
 * @property FeatureValue[] $featureValues
 * @property array $types
 */
class Feature extends CActiveRecord {

  private $types = array(0 => 'Значение', 1 => 'Значение из набора', 2 => 'Диапазон значенией');

  public function getTypes() {
    return $this->types;
  }

  public function getType() {
    return $this->types[$this->type_id];
  }

  public function getValues() {
    return CHtml::listData(FeatureValue::model()->findAll('feature_id=:id', array(':id' => $this->id)), 'id', 'value');
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_feature';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('name, type_id', 'required'),
      array('type_id, search', 'numerical', 'integerOnly' => true),
      array('name', 'length', 'max' => 255),
      array('unit', 'length', 'max' => 6),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, name, type_id, search, unit', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'categoryFeatures' => array(self::HAS_MANY, 'CategoryFeature', 'feature_id'),
      'featureValues' => array(self::HAS_MANY, 'FeatureValue', 'feature_id'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'id' => 'ID',
      'name' => 'Наименование',
      'type_id' => 'Тип',
      'search' => 'Поиск по характеристике',
      'unit' => 'Единица измерения',
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
    $criteria->compare('type_id', $this->type_id);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Feature the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

}
