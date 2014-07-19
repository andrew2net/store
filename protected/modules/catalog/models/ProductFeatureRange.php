<?php

/**
 * This is the model class for table "store_product_feature_range".
 *
 * The followings are the available columns in table 'store_product_feature_range':
 * @property string $product_id
 * @property string $feature_id
 * @property double $from
 * @property double $to
 *
 * The followings are the available model relations:
 * @property Feature $feature
 * @property Product $product
 */
class ProductFeatureRange extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'store_product_feature_range';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('feature_id', 'required'),
			array('from, to', 'numerical'),
			array('product_id, feature_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('product_id, feature_id, from, to', 'safe', 'on'=>'search'),
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
			'feature' => array(self::BELONGS_TO, 'Feature', 'feature_id'),
			'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'product_id' => 'Product',
			'feature_id' => 'Feature',
			'from' => 'From',
			'to' => 'To',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('feature_id',$this->feature_id,true);
		$criteria->compare('from',$this->from);
		$criteria->compare('to',$this->to);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProductFeatureRange the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
