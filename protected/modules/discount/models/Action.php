<?php

/**
 * This is the model class for table "store_action".
 *
 * The followings are the available columns in table 'store_action':
 * @property string $id
 * @property integer $type_id
 * @property string $name
 * @property string $img
 * @property boolean $show
 * @property string $url
 *
 * The followings are the available model relations:
 * @property Product $product
 * @property Advert $advert
 * @property int $type 
 * @property Int[] $types 
 */
class Action extends CActiveRecord {

  private $types = array(0 => 'Новость', 1 => 'Акция');

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
    return 'store_action';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
//      array('type_id', 'required'),
      array('type_id', 'numerical', 'integerOnly' => true),
      array('name', 'length', 'max' => 30),
      array('img, url', 'length', 'max' => 255),
      array('show', 'safe'),
      array('img', 'unsafe'),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, type_id, name, img', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'advert' => array(self::HAS_ONE, 'Advert', 'action_id'),
      'product' => array(self::HAS_ONE, 'Product'
        , array('product_id' => 'id'), 'through' => 'advert'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'id' => 'ID',
      'type_id' => 'Вид',
      'name' => 'Наименование',
      'img' => 'Изображение',
      'show' => 'Показывать',
      'url' => 'Ссылка',
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
    $criteria->compare('name', $this->name, true);
    $criteria->compare('img', $this->img, true);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Action the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  protected function afterDelete() {
    if (strlen($this->img) > 0) {
      $file = Yii::getPathOfAlias('webroot') . $this->img;
      if (file_exists($file))
        unlink($file);
    }
    parent::afterDelete();
  }

  public function scopes() {
    return array_merge(
        array(
      'published' => array(
        'condition' => 't.show=1',
        'with' => array(
          'advert' => array(
            'condition' => "(date IS NULL OR date >='" . date('Y-m-d') . "')",
          ),
        ),
      ),
        ), parent::scopes()
    );
  }

}
