<?php

/**
 * This is the model class for table "store_customer_profile".
 *
 * The followings are the available columns in table 'store_customer_profile':
 * @property string $id
 * @property string $session_id
 * @property integer $user_id
 * @property string $phone
 * @property string $city
 * @property string $address
 * @property string $country_code
 * @property integer $post_code
 * @property string $price_country
 * @property string $price_id 
 * @property integer $entity_id 
 *
 * The followings are the available model relations:
 * @property Order[] $orders
 * @property Country $country 
 * @property User $user 
 * @property Price $price 
 * @property string $entity 
 * 
 * @property string $city_l City from Enegy list
 */
class CustomerProfile extends CActiveRecord {

  public $city_l; //city from Energy list
  public $other_city;
  private static $entities = array(1 => 'Юридическое лицо / ИП', 2 => 'Частное лицо');

  public static function getEntities() {
    return self::$entities;
  }

  public function getEntity() {
    return self::$entities[$this->entity_id];
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_customer_profile';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    $rules = array(
      array('city, address', 'required'),
      array('user_id, price_id, entity_id', 'numerical', 'integerOnly' => true),
      array('entity_id', 'default', 'value' => Yii::app()->params['legal_entity']),
      array('session_id', 'length', 'max' => 32),
      array('post_code', 'postCodeValidate'),
      array('address', 'length', 'max' => 255),
      array('phone', 'length', 'max' => 20),
      array('other_city', 'boolean'),
      array('country_code, price_country', 'length', 'max' => 2),
      array('city, city_l', 'length', 'max' => 100),
      array('phone, city, post_code, address', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, session_id, user_id, fio, email, phone, city, address', 'safe', 'on' => 'search'),
    );
    if (Yii::app()->params['post_code'])
      $rules = array_merge($rules, array(array('post_code', 'required')));
    if (Yii::app()->params['country'])
      $rules = array_merge($rules, array(array('country_code', 'default', 'value' => Yii::app()->params['country'])));
    else
      array('country_code', 'required');
    return $rules;
  }

  public function postCodeValidate($attr, $param) {
    if (Yii::app()->params['post_code']) {
      if (!preg_match('/^\d{6}$/', $this->$attr)) {
        $this->addError($attr, 'Почтовый индекс должен содержать 6 цифр');
      }
    }
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'orders' => array(self::HAS_MANY, 'Order', 'profile_id'),
      'country' => array(self::HAS_ONE, 'Country', 'code'),
      'user' => array(self::HAS_ONE, 'User', '', 'on' => 't.user_id=user.id'),
      'price' => array(self::HAS_ONE, 'Price', '', 'on' => 'price_id=price.id')
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'id' => 'ID',
      'session_id' => 'Session',
      'user_id' => 'User',
      'phone' => 'Телефон',
      'country_code' => 'Страна',
      'post_code' => 'Почтовый индекс',
      'city' => 'Город / населенный пункт',
      'address' => 'Адрес',
      'price_country' => 'Валюта цен',
      'price_id' => 'Прайс',
      'entity_id' => 'Юр./физ. лицо',
      'other_city' => 'Другой населенный пункт',
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
    $criteria->compare('session_id', $this->session_id, true);
    $criteria->compare('user_id', $this->user_id);
    $criteria->compare('fio', $this->fio, true);
    $criteria->compare('email', $this->email, true);
    $criteria->compare('phone', $this->phone, true);
    $criteria->compare('city', $this->city, true);
    $criteria->compare('address', $this->address, true);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return CustomerProfile the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function afterConstruct() {
    parent::afterConstruct();
    $this->entity_id = Yii::app()->params['legal_entity'];
  }

  public function afterFind() {
    Yii::import('application.modules.delivery.models.NrjLocation');
    parent::afterFind();

    $this->other_city = FALSE;
    if ($this->city) {
      $pref = '^';
      $suff = '($|\\(|\\*|\\,|\\ )';
      $nrj = NrjLocation::model()->find('LOWER(name) REGEXP :name', array(':name' => $pref . mb_strtolower(quotemeta(trim($this->city)), 'UTF-8') . $suff));
      if ($nrj) {
        $this->city_l = $nrj->name;
        $this->city = '';
        $this->other_city = FALSE;
      }
      else
        $this->other_city = true;
    }
  }

  public function beforeSave() {
    if (!$this->other_city)
      $this->city = $this->city_l;
    return parent::beforeSave();
  }

  public function afterSave() {
    if (!$this->other_city)
      $this->city = '';
    parent::afterSave();
  }

  public function beforeValidate() {
    if (!$this->other_city)
      $this->city = $this->city_l;
    return parent::beforeValidate();
  }

  public function afterValidate() {
    if (!$this->other_city)
      $this->city = '';
    parent::afterValidate();
  }

}
