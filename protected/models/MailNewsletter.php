<?php

/**
 * This is the model class for table "store_mail_newsletter".
 *
 * The followings are the available columns in table 'store_mail_newsletter':
 * @property string $mail_id
 * @property string $newsletter_id
 *
 * The followings are the available model relations:
 * @property Mail $mail
 * @property Newsletter $newsletter
 */
class MailNewsletter extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'store_mail_newsletter';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mail_id, newsletter_id', 'required'),
			array('mail_id, newsletter_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('mail_id, newsletter_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'mail' => array(self::BELONGS_TO, 'Mail', 'mail_id'),
			'newsletter' => array(self::BELONGS_TO, 'Newsletter', 'newsletter_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'mail_id' => 'Mail',
			'newsletter_id' => 'Newsletter',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('mail_id',$this->mail_id,true);
		$criteria->compare('newsletter_id',$this->newsletter_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MailNewsletter the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
