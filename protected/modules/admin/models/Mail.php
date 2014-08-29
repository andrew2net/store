<?php

/**
 * This is the model class for table "store_mail".
 *
 * The followings are the available columns in table 'store_mail':
 * @property string $id
 * @property integer $uid
 * @property string $type_id
 * @property string $status_id
 * @property string $made_time 
 * @property string $sent_time 
 *
 * The followings are the available model relations:
 * @property MailOrder[] $mailOrders
 * @property Order $order 
 * @property User $user 
 */
class Mail extends CActiveRecord {
//  protected static $types = array(
//    1 => 'Регистрация',
//    2 => 'Восстановление пароля',
//    3 => 'Подтверждение заказа',
//    4 => 'Изменение статуса заказа',
//    5 => 'Оповещение о новом заказе',
//    6 => 'Отправка купона',
//  );
//  protected static $statuses = array(
//    1 => 'Не отправлено',
//    2 => 'Отправлено',
//  );

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_mail';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('uid, type_id, status_id', 'required'),
      array('uid', 'numerical', 'integerOnly' => true),
      array('type_id, status_id', 'length', 'max' => 1),
      array('made_time, sent_time', 'date', 'format' => 'dd-MM-yyyy HH:mm:ss'),
      array('made_time', 'default', 'value' => Yii::app()->dateFormatter->format('dd-MM-yyyy HH:mm:ss', time())),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, uid, type_id, status_id', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'mailOrders' => array(self::HAS_MANY, 'MailOrder', 'mail_id'),
      'order' => array(self::MANY_MANY, 'Order', 'store_mail_order(mail_id, order_id)'),
      'user' => array(self::BELONGS_TO, 'User', 'uid'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'id' => 'ID',
      'uid' => 'Uid',
      'type_id' => 'Type',
      'status_id' => 'Status',
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
    $criteria->compare('uid', $this->uid);
    $criteria->compare('type_id', $this->type_id, true);
    $criteria->compare('status_id', $this->status_id, true);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Mail the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function afterFind() {
    parent::afterFind();
    $this->made_time = Yii::app()->dateFormatter->format('dd-MM-yyyy HH:mm:ss', $this->made_time);
    if ($this->sent_time)
      $this->sent_time = Yii::app()->dateFormatter->format('dd-MM-yyyy HH:mm:ss', $this->sent_time);
  }

  public function beforeSave() {
    if (parent::beforeSave()) {
      $this->made_time = Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss', $this->made_time);
      if ($this->sent_time)
        $this->sent_time = Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss', $this->sent_time);
      return TRUE;
    }
    return FALSE;
  }

}
