<?php

/**
 * This is the model class for table "store_coupon".
 *
 * The followings are the available columns in table 'store_coupon':
 * @property string $id
 * @property string $code
 * @property integer $type_id
 * @property string $value
 * @property string $value_tenge
 * @property integer $used_id
 * @property string $time_issue
 * @property string $time_used
 * @property string $date_limit
 * 
 * @property-read Order[] $order 
 * @property-read array $types 
 * @property-read string $type 0-summa, 1-percent
 * @property-read array $usedValues 
 * @property-read string $used 0-unused, 1-permanent, 2-used
 * @property-read boolean $isNotUsed 
 */
class Coupon extends CActiveRecord {

  const TYPE_SUMM = 0, TYPE_PERCENT = 1, STATUS_NOT_USED = 0, STATUS_PERMANENT = 1, STATUS_USED = 2;

  private $types = array(0 => 'Сумма', 1 => 'Процент');
  private $used = array(0 => 'Неиспользован', 1 => 'Многоразовый'
    , 2 => 'Использован');
  private $used_old;

  const DAYS_BEFORE_REUSE_CODE = 180;
  const CODE_LEGTH = 6;

  public function getTypes() {
    return $this->types;
  }

  public function getType() {
    return $this->types[$this->type_id];
  }

  public function getUsedValues() {
    if (!$this->isNotUsed &&
        ($this->used_id == 1 || $this->used_id == 2 && is_null($this->time_used))) {
      $used_values = $this->used;
      unset($used_values[0]);
      return $used_values;
    }
    return $this->used;
  }

  public function getUsed() {
    return $this->used[$this->used_id];
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_coupon';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('code, type_id, value, used_id', 'required'),
      array('value_tenge', 'valueTengeValidate'),
      array('type_id, used_id', 'numerical', 'integerOnly' => true),
      array('code', 'length', 'max' => 8),
      array('value', 'length', 'max' => 5),
      array('code', 'notUsedCode'),
      array('value', 'validValue'),
      array('date_limit', 'date', 'format' => 'dd.MM.yyyy'),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, code, type_id, value, used_id, time_issue, time_used', 'safe', 'on' => 'search'),
    );
  }

  public function valueTengeValidate($attribute) {
    if ($this->type_id == 0 && empty($this->$attribute))
      $this->addError($attribute, 'Значение не может быть пустым');
  }

  public function notUsedCode($attribute) {
    if (strlen($this->$attribute) != self::CODE_LEGTH)
      $this->addError($attribute, 'Код должен быть ' . self::CODE_LEGTH . ' символов');

    $exclude_codes = $this->getExcludeCodes();
    if (in_array($this->code, $exclude_codes))
      $this->addError($attribute, 'Код ' . $this->code . ' используется');
  }

  public function validValue($attribute) {
    if ($this->type_id == 1 && $this->value > 100)
      $this->addError($attribute, 'Скидка не может быть больше 100%');
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'order' => array(self::HAS_MANY, 'Order', 'coupon_id'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'id' => 'ID',
      'code' => 'Код',
      'type_id' => 'Тип',
      'value' => 'Скидка руб./%',
      'value_tenge' => 'Скидка тенге',
      'used_id' => 'Статус',
      'time_issue' => 'Время создания',
      'time_used' => 'Время использования',
      'date_limit' => 'Дата действия'
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
    $criteria->compare('code', $this->code, true);
    $criteria->compare('type_id', $this->type_id);
    $criteria->compare('value', $this->value, true);
    $criteria->compare('used_id', $this->used_id);
    $criteria->compare('time_issue', $this->time_issue, true);
    $criteria->compare('time_used', $this->time_used, true);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Coupon the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function generateCode() {

    $characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    $exclude_codes_array = $this->getExcludeCodes();

    $code = "";
    while (empty($code)) {
      for ($i = 0; $i < self::CODE_LEGTH; $i++) {
        $code .= $characters[mt_rand(0, strlen($characters) - 1)];
      }
      if (in_array($code, $exclude_codes_array))
        $code = '';
    }
    $this->code = $code;
  }

  private function getExcludeCodes() {

    $formatArray = function ($element) {
      return $element['code'];
    };

    $date = date('Y-m-d', strtotime('-' . self::DAYS_BEFORE_REUSE_CODE . ' day'));
    $sql = array(
      'select' => array('code'),
      'from' => $this->tableName(),
    );
    if ($this->isNewRecord) {
      $sql['where'] = 'used_id<>2 OR (used_id=2 AND time_used<:date)';
      $sql['params'] = array(':date' => $date);
    }
    else {
      $sql['where'] = '(used_id<>2 OR (used_id=2 AND time_used<:date)) and id<>:id';
      $sql['params'] = array(':date' => $date, ':id' => $this->id);
    }
    $command = Yii::app()->db->createCommand($sql);
    $result = $command->queryAll();
    if (is_array($result))
      $exclude_codes = array_map($formatArray, $result);
    else
      $exclude_codes = array();
    return $exclude_codes;
  }

  public function afterFind() {
    $this->used_old = $this->used_id;
    $date = Yii::app()->dateFormatter->formatDateTime($this->time_issue);
    $this->time_issue = $date;

    if ($this->time_used == '0000-00-00 00:00:00')
      $date = '';
    else
      $date = Yii::app()->dateFormatter->formatDateTime($this->time_used);
    $this->time_used = $date;

    if ($this->date_limit == '0000-00-00')
      $date = '';
    else
      $date = Yii::app()->dateFormatter->formatDateTime($this->date_limit, 'medium', NULL);
    $this->date_limit = $date;

    parent::afterFind();
    return TRUE;
  }

  public function beforeSave() {
    $date = Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss', $this->time_issue);
    $this->time_issue = $date;

    if (!empty($this->time_used)) {
      $date = Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss', $this->time_used);
      $this->time_used = $date;
    }

    if (!empty($this->date_limit)) {
      $date = Yii::app()->dateFormatter->format('yyyy-MM-dd', $this->date_limit);
      $this->date_limit = $date;
    }

    return parent::beforeSave();
  }

  public function deleteByPk($pk, $condition = '', $params = array()) {
    if ($this->isNotUsed)
      return parent::deleteByPk($pk, $condition, $params);
    else
      return FALSE;
  }

  public function getIsNotUsed() {
    return count($this->order) == 0;
  }

  public function getHasUsedTime() {
    return !is_null($this->time_used);
  }

  public function updateByPk($pk, $attributes, $condition = '', $params = array()) {
    if (!$this->isNotUsed) {
      if ($this->used_old == 1 && $this->used_id == 2 ||
          $this->used_old == 2 && $this->used_id == 1) {
        $attributes = array('used_id' => $this->used_id);
      }
      else
        return FALSE;
    }
    return parent::updateByPk($pk, $attributes, $condition, $params);
  }

}
