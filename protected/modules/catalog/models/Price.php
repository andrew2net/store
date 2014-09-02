<?php

/**
 * This is the model class for table "store_price".
 *
 * The followings are the available columns in table 'store_price':
 * @property string $id
 * @property string $name
 * @property string $summ
 * @property string $code 1C code id
 *
 * The followings are the available model relations:
 * @property ProductPrice[] $prices
 */
class Price extends CActiveRecord {

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_price';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('name', 'required'),
      array('name', 'length', 'max' => 255),
      array('summ', 'length', 'max' => 12),
      array('code', 'length', 'max' => 9),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, name, summ', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'prices' => array(self::HAS_MANY, 'ProductPrice', 'price_id'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'id' => 'ID',
      'name' => 'Наименование',
      'summ' => 'Сумма заказа',
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
    $criteria->compare('summ', $this->summ, true);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Price the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  /**
   * 
   * @return Price price type object
   */
  public static function getPrice(&$products_table = NULL, $uid = NULL) {
    Yii::import('application.controllers.ProfileController');

    if (!$products_table) { //if it's cart products table
      $table = 'store_cart';
    }
    else {
      $table = 'temp_order_product_' . (Yii::app()->user->id ? Yii::app()->user->id : 'exchange');
      $query = "DROP TABLE IF EXISTS {$table};";
      $query .= "CREATE TEMPORARY TABLE {$table} (product_id int(11) unsigned, quantity smallint(5) unsigned);";
      foreach ($products_table as $item) {
        $product = Product::model()->findByAttributes(array('code' => (string) $item->code));
        if ($product) {
          $query .= "INSERT INTO {$table} VALUES ({$product->id}, {$item->quantity});";
        }
        else
          throw new Exception('Product not found. Product code: ' . $p->code);
      }
      Yii::app()->db->createCommand($query)->execute();
    }
    $query = Yii::app()->db->createCommand()
        ->select('SUM(c.quantity*round(prices.price*(1-greatest(ifnull(disc.percent,0),ifnull(disc1.percent,0),ifnull(disc2.percent,0))/100))) as c_summ, prices.price_id')
        ->from($table . ' c')
        ->join('store_product product', 'c.product_id=product.id')
        ->join('store_product_price prices', 'product.id=prices.product_id')
        ->join('store_price price', 'price.id=prices.price_id')
        ->leftJoin('store_discount disc', "disc.product_id=0 and disc.actual=1 and (disc.begin_date='0000-00-00' or disc.begin_date<=CURDATE()) and (disc.end_date='0000-00-00' or disc.end_date>=CURDATE())")
        ->leftJoin('store_product_category cat', 'cat.product_id=product.id')
        ->leftJoin('store_discount_category discat', 'discat.category_id=cat.category_id')
        ->leftJoin('store_discount disc1', "disc1.product_id=1 and disc1.id=discat.discount_id and disc1.actual=1 and (disc1.begin_date='0000-00-00' or disc1.begin_date<=CURDATE()) and (disc1.end_date='0000-00-00' or disc1.end_date>=CURDATE())")
        ->leftJoin('store_discount_product dispro', 'dispro.product_id=product.id')
        ->leftJoin('store_discount disc2', "disc2.product_id=2 and disc2.id=dispro.discount_id and disc2.actual=1 and (disc2.begin_date='0000-00-00' or disc2.begin_date<=CURDATE()) and (disc2.end_date='0000-00-00' or disc2.end_date>=CURDATE())")
        ->order('price.summ DESC')
        ->group('prices.price_id, price.summ')
        ->having('c_summ>price.summ');

    if (!$products_table)
      $query->where("(session_id=:sid AND :sid<>'') OR (user_id=:uid AND :sid='')", array(
        ':sid' => ProfileController::getSession(),
        ':uid' => Yii::app()->user->isGuest ? '' : Yii::app()->user->id,
      ));

//    $text = $query->getText();
    $row = $query->queryRow();
    if ($row)
      $price = self::model()->findByPk($row['price_id']);
    else
      $price = self::model()->find(array('order' => 'summ'));

    if ($products_table)
      Yii::app()->db->createCommand("DROP TABLE IF EXISTS {$table};")->execute();
      
    if ($uid)
      $profile = CustomerProfile::model()->with('price')->findByAttributes(array('user_id' => $uid));
    else
      $profile = ProfileController::getProfile();

    if ($profile && $profile->price_id && $profile->price->summ > $price->summ)
      return $profile->price;
    else
      return $price;
  }

  public static function getMinimalSumm() {
    return self::model()->find(array('order' => 'summ'))->summ;
  }

}
