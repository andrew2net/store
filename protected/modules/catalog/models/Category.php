<?php

/**
 * This is the model class for table "store_categories".
 *
 * The followings are the available columns in table 'store_categories':
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property string $img
 * @property integer $lft
 * @property integer $rgt
 * @property integer $level
 * @property integer $root
 * @property string $seo
 * @property string $code 1C code id
 */
class Category extends CActiveRecord {

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_category';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('name', 'required'),
//      array('left_key, right_key, level', 'numerical', 'integerOnly' => true),
      array('name', 'length', 'max' => 30),
      array('code', 'length', 'max' => 11),
      array('url, img', 'length', 'max' => 255),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('name, url, img, seo', 'safe'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'product' => array(self::MANY_MANY, 'Product'
        , 'store_product_category(product_id, category_id)'),
      'discount' => array(
        self::MANY_MANY,
        'Discount',
        'store_discount_category(category_id, discount_id)')
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'name' => 'Наименование',
      'url' => 'Url',
      'img' => 'Изображение',
      'level' => 'Уровень вложенности',
      'seo' => 'SEO текст'
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

    $criteria->compare('name', $this->name, true);
    $criteria->compare('url', $this->level, true);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Categories the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function behaviors() {
    return array(
      'NestedSetBehavior' => array(
        'class' => 'application.behaviors.NestedSetBehavior',
        'leftAttribute' => 'lft',
        'rightAttribute' => 'rgt',
        'levelAttribute' => 'level',
        'hasManyRoots' => true
      ),
    );
  }

}
