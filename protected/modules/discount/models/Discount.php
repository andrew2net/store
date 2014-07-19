<?php

/**
 * This is the model class for table "store_discount".
 *
 * The followings are the available columns in table 'store_discount':
 * @property string $id
 * @property integer $type_id
 * @property string $begin_date
 * @property string $end_date
 * @property integer $actual
 * @property integer $product_id
 * @property integer $percent
 *
 * The followings are the available model relations:
 * @property Category[] $category
 * @property Product[] $product
 */
class Discount extends CActiveRecord {

  private $types = array(0 => 'Скидка недели', 1 => 'На товар', 2 => 'На день рождения');
  private $products = array(0 => 'Весь товар', 1 => 'Категории товаров', 2 => 'Произвольный товар');

  public function getTypes() {
    return $this->types;
  }

  public function getType() {
    return $this->types[$this->type_id];
  }

  public function getProductTypes() {
    return $this->products;
  }

  public function getProductType() {
    return $this->products[$this->product_id];
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_discount';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('type_id, product_id, percent', 'required'),
      array('type_id, actual, product_id, percent', 'numerical', 'integerOnly' => true),
      array('begin_date, end_date', 'safe'),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, type_id, begin_date, end_date, actual, product_id, percent', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'category' => array(self::MANY_MANY, 'Category'
        , 'store_discount_category(discount_id, category_id)'),
      'product' => array(self::MANY_MANY, 'Product'
        , 'store_discount_product(discount_id, product_id)'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'id' => 'ID',
      'type_id' => 'Тип скидки',
      'begin_date' => 'Действует с',
      'end_date' => 'Действует по',
      'actual' => 'Активна',
      'product_id' => 'Товар',
      'percent' => 'Процент',
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
    $criteria->compare('type_id', $this->type_id);
    $criteria->compare('begin_date', $this->begin_date, true);
    $criteria->compare('end_date', $this->end_date, true);
    $criteria->compare('actual', $this->actual);
    $criteria->compare('product_id', $this->product_id);
    $criteria->compare('percent', $this->percent);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Discount the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function getCategoryTree() {

    Yii::import('application.modules.catalog.models.Category');

    $checked = CHtml::listData($this->category, 'id', 'name');

    $tree = '';
    $categories = Category::model()->findAll(array('order' => 'root,lft'));
    $level = 0;

    foreach ($categories as $n => $category) {

      if ($category->level == $level)
        $tree .= CHtml::closeTag('li');
      else if ($category->level > $level)
        $tree .= CHtml::openTag('ul');
      else {
        $tree .= CHtml::closeTag('li');

        for ($i = $level - $category->level; $i; $i--) {
          $tree .= CHtml::closeTag('ul');
          $tree .= CHtml::closeTag('li');
        }
      }
      $class = "";
      if (array_key_exists($category->primaryKey, $checked))
        $class .= "jstree-checked";

      $tree .= CHtml::openTag('li', array(
            'id' => 'node_' . $category->primaryKey,
            "class" => $class,
//        'rel' => $category->getAttribute('name')
      ));
      $tree .= CHtml::openTag('a', array('href' => '#'));
      $tree .= CHtml::encode($category->getAttribute('name'));
      $tree .= CHtml::closeTag('a');

      $level = $category->level;
    }

    for ($i = $level; $i; $i--) {
      $tree .= CHtml::closeTag('li');
      $tree .= CHtml::closeTag('ul');
    }

    return $tree;
  }

  public function beforeSave() {

    if (!empty($this->begin_date)) {
      $begin_date = Yii::app()->dateFormatter->format('yyyy-MM-dd', $this->begin_date);
      $this->begin_date = $begin_date;
    }

    if (!empty($this->end_date)) {
      $end_date = Yii::app()->dateFormatter->format('yyyy-MM-dd', $this->end_date);
      $this->end_date = $end_date;
    }

    parent::beforeSave();
    return TRUE;
  }

  public function afterFind() {

    if ($this->begin_date == '0000-00-00')
      $begin_date = '';
    else
      $begin_date = Yii::app()->dateFormatter->format('dd.MM.yyyy', $this->begin_date);
    $this->begin_date = $begin_date;

    if ($this->end_date == '0000-00-00')
      $end_date = '';
    else
      $end_date = Yii::app()->dateFormatter->format('dd.MM.yyyy', $this->end_date);
    $this->end_date = $end_date;

    parent::afterFind();
    return TRUE;
  }

  public function scopes() {
    $date = date('Y-m-d');
    $params = Child::model()->getSelectParams();
    return array_merge(parent::scopes(), array(
      'week' => array(
        'condition' => "type_id=0 AND actual=1 AND (begin_date<='" . $date .
        "' OR begin_date='0000-00-00') AND (end_date>='" . $date .
        "' OR end_date='0000-00-00')",
        'with' => array(
          'product' => array('alias' => 'p'),
        ),
        'with' => array(
          'category' => array(
            'with' => array(
              'product' => array('alias' => 'c')),
          ),
        ),
        'together' => TRUE,
        'select'=>array(
          't.*',
        ),
      ),
    ));
  }

}
