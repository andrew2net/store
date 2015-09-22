<?php

/**
 * This is the model class for table "store_product".
 *
 * The followings are the available columns in table 'store_product':
 * @property string $id
 * @property string $name
 * @property string $img
 * @property string $small_img
 * @property string $article
 * @property integer $brand_id
 * @property integer $remainder_RU
  * @property integer $remainder_KZ
* @property string $description
 * @property string $price
 * @property string $price_tenge
 * @property boolean $show_me
 * @property float $weight 
 * @property integer $length 
 * @property integer $width 
 * @property integet $height
 * @property string $code 1C code
 * @property string $seo 
 *
 * The followings are the available model relations:
 * @property Brand $brand
 * @property Category[] $category 
 * @property Discount[] $discount
 * @property Top10 $top10 
 * @property ProductFeature[] $feature
 * @property ProductFeatureRange[] $feature_range
 * @property ProductFeatureValue[] $feature_value
 * @property ProductPrice[] $prices
 */
class Product extends CActiveRecord {

  public $w_end_date;

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_product';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('remainder_RU, remainder_KZ', 'numerical', 'integerOnly' => true, 'max' => 99999, 'min' => 0),
      array('weight', 'numerical', 'numberPattern' => '(^\d{1,3}$|^\d{1,3}\.\d{0,3}$)'),
      array('length, width, height', 'numerical', 'max' => 9999.9, 'min' => 0),
      array('name, article, brand_id, price', 'required'),
      array('price_tenge', 'required', 'on' => 'mcurrency'),
      array('name, img, small_img', 'length', 'max' => 255),
      array('article', 'length', 'max' => 25),
      array('brand_id, code', 'length', 'max' => 11),
      array('price, price_tenge', 'length', 'max' => 12),
      array('description, show_me, seo', 'safe'),
      array('img, small_img', 'unsafe'),
      array('article', 'unique'),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('name, article, brand_id, remainder_RU, remainder_KZ, price, price_tenge, show_me', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'brand' => array(self::BELONGS_TO, 'Brand', 'brand_id'),
      'category' => array(self::MANY_MANY, 'Category',
        'store_product_category(product_id, category_id)'),
      'discount' => array(self::MANY_MANY, 'Discount'
        , 'store_discount_product(discount_id, product_id)'),
      'top10' => array(self::HAS_ONE, 'Top10', 'product_id'),
      'feature' => array(self::HAS_MANY, 'ProductFeature', 'product_id'),
      'feature_range' => array(self::HAS_MANY, 'ProductFeatureRange', 'product_id'),
      'feature_value' => array(self::HAS_MANY, 'ProductFeatureValue', 'product_id'),
      'prices' => array(self::HAS_MANY, 'ProductPrice', 'product_id'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'id' => 'ID',
      'name' => 'Наименование',
      'img' => 'Изображение',
      'small_img' => 'Миниатюра',
      'article' => 'Артикул',
      'brand_id' => 'Бренд',
      'remainder_RU' => 'Ост-к RU',
      'remainder_KZ' => 'Ост-к KZ',
      'description' => 'Описание',
      'price' => 'Цена',
      'price_tenge' => 'Цена',
      'show_me' => 'Показывать',
      'weight' => 'Вес (кг)',
      'length' => 'Длина (см)',
      'width' => 'Ширина (см)',
      'height' => 'Высота (см)',
      'seo' => 'SEO'
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

    return new CActiveDataProvider($this, array(
      'criteria' => $this->searchCriteria(),
    ));
  }

  private function searchCriteria() {
    $criteria = new CDbCriteria;

    $criteria->compare('t.name', $this->name, true);
    $criteria->compare('article', $this->article, true);
    $criteria->compare('remainder_RU', $this->remainder_RU);
    $criteria->compare('remainder_KZ', $this->remainder_KZ);
    $criteria->compare('price', $this->price);
    $criteria->compare('price_tenge', $this->price);
    $criteria->compare('show_me', $this->show_me);
    $criteria->with = array('brand');
    $criteria->compare('brand_id', $this->brand_id, TRUE);

    return $criteria;
  }

  public function searchTop10() {
    $criteria = $this->searchCriteria();
    $criteria->with = array('top10');
    $criteria->addCondition('top10.product_id is null');
    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
      'pagination' => array('pageSize' => 5),
    ));
  }

  public function searchDiscount() {
    $criteria = $this->searchCriteria();
    $criteria->addNotInCondition('t.id', $_SESSION['discount_product']);
    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
      'pagination' => array('pageSize' => 5),
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Product the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function getBrandOptions() {
    $brands = Brand::model()->findAll(array('order' => 'name'));
    return $list = CHtml::listData($brands, 'id', 'name');
  }

  public function getCategoryTree($checked = NULL) {

    if (!$checked)
      $checked = CHtml::listData($this->category, 'id', 'name');

    $tree = '';
    $categories = Category::model()->findAll(array('order' => 'root,lft'));
    $level = 0;

    foreach ($categories as $n => $category) {

      if ($category->level == $level)
        $tree .= CHtml::closeTag('li');
      else if ($category->level > $level)
        $tree .= CHtml::openTag('ul');
      else {
        $tree .= CHtml::closeTag('li');

        for ($i = $level - $category->level; $i; $i--) {
          $tree .= CHtml::closeTag('ul');
          $tree .= CHtml::closeTag('li');
        }
      }
      $class = "";
      if (array_key_exists($category->primaryKey, $checked))
        $class .= "jstree-checked";

      $tree .= CHtml::openTag('li', array(
            'id' => 'node_' . $category->primaryKey,
            "class" => $class,
//        'rel' => $category->getAttribute('name')
      ));
      $tree .= CHtml::openTag('a', array('href' => '#'));
      $tree .= CHtml::encode($category->getAttribute('name'));
      $tree .= CHtml::closeTag('a');

      $level = $category->level;
    }

    for ($i = $level; $i; $i--) {
      $tree .= CHtml::closeTag('li');
      $tree .= CHtml::closeTag('ul');
    }

    return $tree;
  }

  protected function afterDelete() {
    if (strlen($this->img) > 0) {
      $file = Yii::getPathOfAlias('webroot') . $this->img;
      if (file_exists($file))
        unlink($file);
    }
    if (strlen($this->small_img) > 0) {
      $file = Yii::getPathOfAlias('webroot') . $this->small_img;
      if (file_exists($file))
        unlink($file);
    }
    parent::afterDelete();
  }

  /**
   * Return percentof discount on date
   * @param string $date the date on which you need to get the discount
   * @return int percent of discount
   */
  public function getActualDiscount($date = NULL) {
    if ($date)
      $date = Yii::app()->dateFormatter->format('yyyy-MM-dd', $date);
    else
      $date = date('Y-m-d');

    $categories = Yii::app()->db->createCommand()
            ->select('category_id')->from('store_product_category')
            ->where("product_id={$this->id}")->text;

    $discount_category = Yii::app()->db->createCommand()
            ->select('d.id')
            ->from('store_discount_category dc')
            ->join('store_discount d', 'd.id = dc.discount_id')
            ->join('store_category c', 'c.id = dc.category_id')
            ->join('store_category s', 's.lft >= c.lft AND s.rgt <= c.rgt AND s.root=c.root')
            ->where("s.id in ({$categories})")->text;

    $discount_id = Yii::app()->db->createCommand()
            ->select('discount_id')->from('store_discount_product')
            ->where("product_id={$this->id}")
            ->union($discount_category)->text;

    $percenr = Yii::app()->db->createCommand()
        ->select('MAX(percent) discount')->from('store_discount')
        ->where("(id in ({$discount_id}) OR product_id=0) AND (begin_date<=:date OR begin_date='0000-00-00')" .
            " AND (end_date>=:date OR end_date='0000-00-00') AND actual=1"
            , array(':date' => $date))
        ->queryRow();

    if ($percenr['discount']) {
      return $percenr['discount'];
    }
    return 0;
  }

  public function scopes() {
      Yii::import('application.controllers.ProfileController');
    $cp = ProfileController::getProfile();
    return array_merge(parent::scopes(), array(
      'top' => array(
        'with' => array(
          'top10' => array(
            'joinType' => 'INNER JOIN'),
        ),
        'condition' => "show_me=1",
      ),
      'availableOnly' => array('condition' => "remainder_$cp->price_country>0 AND remainder_$cp->price_country IS NOT NULL",),
    ));
  }

  /**
   * Add to the product criteria filter discounted products
   * @return \Product
   */
  public function discount() {
    $discount_all = Discount::model()
        ->count("actual=1 AND type_id IN (0,1) AND product_id=0 AND (begin_date<=:date OR begin_date='0000-00-00') AND (end_date>=:date OR end_date='0000-00-00')"
        , array(':date' => date('Y-m-d')));

    $discount_product = Yii::app()->db->createCommand()
        ->select('p.product_id')
        ->from('store_discount_product p')
        ->join('store_discount d', 'p.discount_id=d.id')
        ->where("actual=1 AND type_id IN (0,1) AND (begin_date<=:date OR begin_date='0000-00-00') AND (end_date>=:date OR end_date='0000-00-00')");

    $discount_category = Yii::app()->db->createCommand()
        ->select('s.id')
        ->from('store_discount_category dc')
        ->join('store_discount d', 'd.id = dc.discount_id')
        ->join('store_category c', 'c.id = dc.category_id')
        ->join('store_category s', 's.lft >= c.lft AND s.rgt <= c.rgt AND s.root=c.root')
        ->where("actual=1 AND type_id IN (0,1) AND (begin_date<=:date OR begin_date='0000-00-00') AND (end_date>=:date OR end_date='0000-00-00')");

    $criteria = new CDbCriteria;
    $criteria->condition = "$discount_all>0 OR category.id IN ($discount_category->text) OR t.id IN ($discount_product->text)";
    $criteria->params = array(':date' => date('Y-m-d'));
    $criteria->with = array('category');
    $criteria->together = TRUE;

    $this->getDbCriteria()->mergeWith($criteria);
    return $this;
  }

  /**
   * Add to the product criteria filter discounted products for current week
   * @return \Product
   */
  public function week() {
    $discount_category = Yii::app()->db->createCommand()
        ->select('d.percent, d.begin_date, d.end_date, d.type_id, d.actual, s.id')
        ->from('store_discount_category dc')
        ->join('store_discount d', 'd.id = dc.discount_id')
        ->join('store_category c', 'c.id = dc.category_id')
        ->join('store_category s', 's.lft >= c.lft AND s.rgt <= c.rgt AND s.root=c.root')
        ->where("actual=1 AND type_id IN (0,1) AND (begin_date<=:date OR begin_date='0000-00-00') AND (end_date>=:date OR end_date='0000-00-00')");

    $date = date('Y-m-d');
    $this->getDbCriteria()->mergeWith(array(
      'with' => array(
        'discount' => array(
          'select' => FALSE,
          'alias' => 'prod',
          'on' => "prod.actual=1 AND (prod.begin_date<=:date OR prod.begin_date='0000-00-00')" .
          " AND (prod.end_date>=:date OR prod.end_date='0000-00-00') AND prod.type_id=0",
        ),
        'category' => array(
          'select' => FALSE,
          'join' => "LEFT JOIN ($discount_category->text) cat ON category.id=cat.id",
        ),
      ),
      'together' => TRUE,
      'select' => array(
        't.*',
        'IFNULL(prod.begin_date, cat.begin_date) AS w_begin_date',
        'IFNULL(prod.end_date, cat.end_date) AS w_end_date',
        'IFNULL(prod.type_id, cat.type_id) AS w_type',
        'IFNULL(prod.actual, cat.actual) AS w_actual',
        'MAX(IFNULL(prod.percent, cat.percent)) AS percent',
      ),
      'condition' => "t.show_me=1",
      'having' => "w_type=0 AND w_actual=1 AND (w_begin_date<='" . $date .
      "' OR w_begin_date='0000-00-00') AND (w_end_date>='" . $date .
      "' OR w_end_date='0000-00-00')",
      'order' => 'percent DESC',
      'group' => 'prod.id, t.id',
      'params' => array(':date' => $date),
    ));
    return $this;
  }

  /**
   * Add to the product criteria the sorting by discounted products
   * @return \Product
   */
  public function discountOrder() {
    $discount_all = Yii::app()->db->createCommand()
            ->select("a.percent")
            ->from('store_discount a')
            ->where("actual=1 AND type_id IN (0,1) AND product_id=0 AND (begin_date<=:date OR begin_date='0000-00-00') AND (end_date>=:date OR end_date='0000-00-00')")->text;
    $discount_category = Yii::app()->db->createCommand()
            ->select('d.percent, s.id')
            ->from('store_discount_category dc')
            ->join('store_discount d', 'd.id = dc.discount_id')
            ->join('store_category c', 'c.id = dc.category_id')
            ->join('store_category s', 's.lft >= c.lft AND s.rgt <= c.rgt AND s.root=c.root')
            ->where("actual=1 AND type_id IN (0,1) AND (begin_date<=:date OR begin_date='0000-00-00') AND (end_date>=:date OR end_date='0000-00-00')")->text;
    $this->getDbCriteria()->mergeWith(array(
      'with' => array(
        'category' => array(
          'select' => FALSE,
          'join' => "LEFT JOIN ($discount_category) c ON category.id=c.id OR c.id='a'"
        ),
        'discount' => array(
          'select' => FALSE,
          'alias' => 'd',
          'on' => "actual=1 AND (d.begin_date<=:date OR d.begin_date='0000-00-00')" .
          " AND (d.end_date>=:date OR d.end_date='0000-00-00')",
        )
      ),
      'join' => "LEFT JOIN ($discount_all) a ON a.percent>0",
      'together' => TRUE,
      'select' => array(
        't.*',
        'MAX(IFNULL(d.percent, IFNULL(c.percent, IFNULL(a.percent, 0)))) AS percent',
        '(1-MAX(IFNULL(d.percent, IFNULL(c.percent, IFNULL(a.percent, 0))))/100)*t.price AS dprice',
      ),
      'condition' => "show_me=1",
      'params' => array(':date' => date('Y-m-d')),
      'order' => 'percent DESC',
      'group' => 't.id'
    ));
    return $this;
  }

  public function brandFilter($id) {
    $this->getDbCriteria()->mergeWith(array(
      'condition' => 'brand_id=:id',
      'params' => array(':id' => $id),
    ));
    return $this;
  }

  public function recommended() {
    $this->getDbCriteria()->mergeWith(array(
      'select' => array(
        't.*',
      ),
      'params' => array(
      ),
    ));

    return $this;
  }

  public function searchCategory($id) {
    if (!Yii::app()->params['showStockOut']){
      $this->availableOnly();
    }
    $this->subCategory($id)->discountOrder();
    return new CActiveDataProvider($this, array(
      'criteria' => $this->searchCriteria(),
    ));
  }

  public function subCategory($id) {
    $category = Category::model()->findByPk($id);
    $descendants = $category->descendants()->findAll(array('select' => 'id'));
    $cat = $id;
    foreach ($descendants as $value)
      $cat .= ',' . $value->id;

    $this->getDbCriteria()->mergeWith(
        array(
          'with' => array(
            'category' => array(
              'select' => FALSE,
            ),
          ),
          'together' => TRUE,
          'condition' => "category.id IN ({$cat})",
        )
    );
    return $this;
  }

  public function searchByName($text) {
    $text = strtr($text, array('%' => '\%', '_' => '\_'));
    $this->getDbCriteria()->mergeWith(
        array(
          'condition' => "t.name LIKE :text OR t.article=:art",
          'params' => array(
            ':text' => '%' . $text . '%',
            ':art' => $text,
          ),
        )
    );
    return $this;
  }

  public function price($min, $max) {
    $this->discountOrder();
    $this->getDbCriteria()->mergeWith(
        array(
          'having' => "dprice BETWEEN :min AND :max OR dprice>=:min AND :max=5000",
          'params' => array(':min' => $min, ':max' => $max),
    ));
    return $this;
  }

  public function sort($sort) {
//    if ($sort['gender'] != 0)
//      $this->gender($sort['gender']);

    if (!empty($sort['category']))
      $this->subCategory($sort['category']);

    if (isset($sort['availableOnly']) && $sort['availableOnly'])
      $this->availableOnly();

//    $this->age($sort['ageFrom'], $sort['ageTo']);

    $this->price($sort['priceFrom'], $sort['priceTo']);

    return $this;
  }

  public function afterFind() {
    if (Yii::app()->params['mcurrency'])
      $this->setScenario('mcurrency');
    parent::afterFind();
  }

  public function afterConstruct() {
    if (Yii::app()->params['mcurrency'])
      $this->setScenario('mcurrency');
    parent::afterConstruct();
  }

  /**
   * Return price of the product
   * @param Price $price_type type of wholsale price
   * @param string $currency_code currency code (RUR, KZT, ...)
   * @return float price of the product
   */
  public function getPrice($price_type, $currency_code) {
    if ($price_type)
      return $this->getTradePrice($price_type);
    else {
      if (Yii::app()->params['mcurrency'])
        switch ($currency_code) {
          case 'KZT':
            return $this->price_tenge;
            break;
          default :
            return $this->price;
        }
      else
        return $this->price;
    }
  }

  /**
   * Return wholesale price of the product
   * @param Price $price type of wholesale price
   * @return float wholesale price of the product
   */
  public function getTradePrice(Price $price) {
    Yii::import('application.modules.catalog.models.ProductPrice');
    $trade_price = ProductPrice::model()->findByAttributes(array('price_id' => $price->id, 'product_id' => $this->id));
    if ($trade_price && $trade_price->price > 0)
      return $trade_price->price;
    else {
      $prices = Price::model()->findAll('summ<:summ', array(':summ' => $price->summ));
      if ($prices) {
        foreach ($prices as $p) {
          /* @var $p Price */
          $trade_price = ProductPrice::model()->findByAttributes(array('price_id' => $p->id, 'product_id' => $this->id));
          if ($trade_price && $trade_price->price > 0)
            return $trade_price->price;
        }
      }
      return 0;
    }
  }

  /**
   * Create small a imge from the big one.
   */
  public function createThumbnail() {
    $img_storage = '/images/' . Yii::app()->params['img_storage'] . '/product/';
    $root_path = Yii::getPathOfAlias('webroot');
    $file_path = $root_path . $img_storage;
    $image = new Imagick($root_path . $this->img);
    $ext = strtolower($image->getimageformat());
    $small_img_name = $this->id . 's.' . $ext;
    if ($image->getimagewidth() > $image->getimageheight())
      $image->thumbnailimage(100, 0);
    else
      $image->thumbnailimage(0, 100);
    $image->writeimage($file_path . $small_img_name);
    $image->destroy();
    $this->small_img = $img_storage . basename($file_path . $small_img_name);
  }

  public function beforeSave() {
    $this->update_time = \date('Y-m-d H:i:s');
    return parent::beforeSave();
  }

  /**
   * Return alt attribute fo a small image
   * @return string alt attribute
   */
  public function getSmallImageAlt() {
    if (!empty($this->small_img) && file_exists(Yii::getPathOfAlias('webroot') .  $this->small_img))
      $img_alt = $this->name;
    else
      $img_alt = 'Нет фото';
    return $img_alt;
  }

}
