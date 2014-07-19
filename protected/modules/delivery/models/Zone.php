<?php

/**
 * This is the model class for table "store_zone".
 *
 * The followings are the available columns in table 'store_zone':
 * @property string $id
 * @property integer $type_id
 * @property string $country_code
 * @property string $post_code
 * @property integer $zone
 * 
 * @property Delivery $delivery
 */
class Zone extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'store_zone';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type_id, country_code, post_code', 'required'),
			array('id, type_id, zone', 'numerical', 'integerOnly'=>true),
			array('country_code', 'length', 'max'=>2),
			array('post_code', 'length', 'max'=>6),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('country_code, post_code, zone', 'safe', 'on'=>'search'),
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
      
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
      'type_id' => 'Тип зоны',
			'country_code' => 'Код страны',
			'post_code' => 'Шаблон почтового индекса',
			'zone' => 'Номер зоны',
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

		$criteria->compare('country_code',$this->country_code,true);
		$criteria->compare('post_code',$this->post_code,true);
		$criteria->compare('zone',$this->zone);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Zone the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
