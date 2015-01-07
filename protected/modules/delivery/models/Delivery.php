<?php

/**
 * This is the model class for table "store_delivery".
 *
 * The followings are the available columns in table 'store_delivery':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $length
 * @property integer $width
 * @property integer $height
 * @property integer $oversize
 * @property integer $insurance
 * @property boolean $active 
 * @property string $currency_code
 * @property string $max_weight
 * @property string $size_method_id
 * @property integer $size_summ
 * @property string $zone_type_id
 * @property string $transport_type_id 
 *
 * The followings are the available model relations:
 * @property DeliveryRate[] $deliveryRates
 * @property RegionDelivery[] $regionDeliveries
 * @property Currency $currency 
 * @property array $size_methods
 * @property string $size_method
 * @property array $zone_types
 * @property string $zone_type
 * @property array $nrjTypes
 * @property string $nrjType 
 * @property array $transportTypes 
 * @property string $transportType 
 */
class Delivery extends CActiveRecord {

  const SIZE_LENGTH_CIRCLE_SUMM = 1, SIZE_LENGTH_WIDTH_HEIGHT = 2;
  const ZONE_KAZPOST = 1, ZONE_KAZEMS = 2, ZONE_NRJ = 3, ZONE_CUSTOM = 4, ZONE_COURIER = 5, ZONE_SELF = 6;
  const NRJ_AUTO = 1, NRJ_AVIA = 2, NRJ_RW = 3;

  private static $size_methods = array(1 => 'Сумма длины и окружности', 2 => 'Ограничение дл. шир. выс.');
  private static $zone_type = array(1 => 'КазПочта', 2 => 'EMS-Казахстан', 3 => 'т/к Энергия', 4 => 'т/к покупателя', 5 => 'Курьер', 6 => 'Самовывоз');
  private static $nrj_types = array(1 => 'avto', 2 => 'avia', 3 => 'rw');
  private static $transport_types = array(1 => 'авто', 2 => 'авиа', 3 => 'ж/д');

  public static function getSize_methods() {
    return self::$size_methods;
  }

  public function getSize_method() {
    return self::$size_methods[$this->size_method_id];
  }

  public static function getZone_types() {
    return self::$zone_type;
  }

  public function getZone_type() {
    return self::$zone_type[$this->zone_type_id];
  }

  public static function getNrjTypes() {
    return self::$nrj_types;
  }

  public function getNrjType() {
    return self::$nrj_types[$this->transport_type_id];
  }

  public static function getTransportTypes() {
    return self::$transport_types;
  }

  public function getTransportType() {
    return isset(self::$transport_types[$this->transport_type_id]) ? self::$transport_types[$this->transport_type_id] : '';
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_delivery';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('active, max_weight, insurance', 'default', 'value' => 0),
      array('name, insurance', 'required'),
      array('length, width, height, oversize, insurance, size_method_id, size_summ, zone_type_id', 'numerical', 'integerOnly' => true),
      array('max_weight', 'numerical', 'numberPattern' => '/\d{1,3}\.?\d{0,2}/'),
      array('transport_type_id', 'numerical', 'integerOnly' => true),
      array('transport_type_id', 'length', 'max' => 1),
      array('length, width, height, oversize, size_summ', 'length', 'max' => 4),
      array('oversize, currency_code', 'length', 'max' => 3),
      array('insurance, zone_type_id', 'length', 'max' => 2),
      array('name', 'length', 'max' => 30),
      array('description', 'safe'),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, name, description', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'deliveryRates' => array(self::HAS_MANY, 'DeliveryRate', 'delivery_id'),
      'currency' => array(self::BELONGS_TO, 'Currency', 'currency_code'),
      'regionDeliveries' => array(self::HAS_MANY, 'RegionDelivery', 'delivery_id'),
      'zones' => array(self::HAS_MANY, 'Zone', 'zone'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'id' => 'ID',
      'name' => 'Наименование',
      'description' => 'Описание',
      'length' => 'Длина',
      'width' => 'Ширина',
      'height' => 'Высота',
      'oversize' => 'Доплата за превышение (%)',
      'insurance' => 'Страховка (%)',
      'active' => 'Активный',
      'currency_code' => 'Валюта',
      'max_weight' => 'Максимальный вес (кг)',
      'size_method_id' => 'Способ расчета',
      'size_summ' => 'Сумма размеров',
      'zone_type_id' => 'Тарифные зоны',
      'transport_type_id' => 'Вид транспорта',
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
    $criteria->compare('description', $this->description, true);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Delivery the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function region($country_code, $post_code, $city, $delivery_id = NULL) {
    Yii::import('application.modules.delivery.models.RegionDelivery');
    Yii::import('application.modules.delivery.models.Zone');
    Yii::import('application.controllers.ProfileController');
    $customerProfile = ProfileController::getProfile();
    $this->getDbCriteria()->mergeWith(array(
      'with' => array(
        'regionDeliveries' => array(
          'with' => array(
            'zones',
            'region',
          ),
        ),
      ),
      'condition' => '(:pcode REGEXP zones.post_code AND region.country_code=:ccode AND zones.country_code=:ccode OR '
      . 't.zone_type_id=6 AND region.country_code=:price_country OR t.zone_type_id IN (4) OR t.zone_type_id=3 AND :city=0 OR '
      . 't.zone_type_id=5 AND :city=1) AND (:delivery_id IS NULL OR t.id=:delivery_id) AND t.active=1',
      'params' => array(
        ':ccode' => $country_code,
        ':pcode' => $post_code,
        ':city' => empty($city),
        ':delivery_id' => $delivery_id,
        ':price_country' => $customerProfile->price_country,
      ),
      'order' => 't.zone_type_id',
    ));
    return $this;
  }

  public static function getList() {
    $items = self::model()->findAll();
    /* @var $items Delivery[] */
    $options = array();
    foreach ($items as $item) {
      switch ($item->zone_type_id) {
        case Delivery::ZONE_NRJ:
          $options[$item->id] = $item->name . ' (' . $item->transportType . ')';
          break;
        case Delivery::ZONE_CUSTOM:
          $options[$item->id] = $item->zone_type;
          break;
        default :
          $options[$item->id] = $item->name;
      }
    }
    return $options;
  }

}
