<?php

/**
 * This is the model class for table "store_region_delivery".
 *
 * The followings are the available columns in table 'store_region_delivery':
 * @property string $region_id
 * @property string $delivery_id
 * @property string $zone
 * @property string $weight_rate
 */
class RegionDelivery extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'store_region_delivery';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('region_id, delivery_id', 'required'),
			array('region_id, delivery_id, zone', 'length', 'max'=>11),
			array('weight_rate', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('region_id, delivery_id, zone, weight_rate', 'safe', 'on'=>'search'),
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
      'zones' => array(self::HAS_ONE, 'Zone', '', 'on' => 'zones.zone=regionDeliveries.zone AND zones.type_id=t.zone_type_id'),
      'region' => array(self::BELONGS_TO, 'Region', 'region_id'),
      'delivery' => array(self::BELONGS_TO, 'Delivery', 'delivery_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'region_id' => 'Region',
			'delivery_id' => 'Delivery',
			'zone' => 'Тарифная зона',
			'weight_rate' => 'Доп.тариф',
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

		$criteria->compare('region_id',$this->region_id,true);
		$criteria->compare('delivery_id',$this->delivery_id,true);
		$criteria->compare('zone',$this->zone,true);
		$criteria->compare('weight_rate',$this->weight_rate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RegionDelivery the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
