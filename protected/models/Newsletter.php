<?php

/**
 * This is the model class for table "store_newsletter".
 *
 * The followings are the available columns in table 'store_newsletter':
 * @property string $id
 * @property string $subject
 * @property integer $is_sent
 * @property string $time
 * @property string $send_price 
 *
 * The followings are the available model relations:
 * @property NewsletterBlock[] $newsletterBlocks
 * @property Mail[] $mail
 */
class Newsletter extends CActiveRecord {

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_newsletter';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('time', 'date', 'format' => 'dd-MM-yyyy HH:mm:ss'),
      array('is_sent', 'numerical', 'integerOnly' => true),
      ['is_sent', 'default', 'value' => 0],
      array('subject', 'length', 'max' => 255),
      ['send_price', 'boolean'],
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, subject, is_sent, time', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'newsletterBlocks' => array(self::HAS_MANY, 'NewsletterBlock', 'newsletter_id'),
      'mail' => array(self::MANY_MANY, 'Mail', 'store_newsletter_mail(newsletter_id, mail_id)'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'id' => 'ID',
      'subject' => 'Тема письма',
      'is_sent' => 'Отправлено',
      'time' => 'Время создания',
      'send_price' => 'Приложить прайс',
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
    $criteria->compare('subject', $this->subject, true);
    $criteria->compare('is_sent', $this->is_sent);
    $criteria->compare('time', $this->time, true);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Newsletter the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function afterFind() {
    $this->time = Yii::app()->dateFormatter->format('dd-MM-yyyy HH:mm:ss', $this->time);
  }
  
  public function beforeSave() {
    $this->time = Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss', $this->time);
    return parent::beforeSave();
  }

  public function afterConstruct() {
    $this->send_price = 1;
    parent::afterConstruct();
  }
}
