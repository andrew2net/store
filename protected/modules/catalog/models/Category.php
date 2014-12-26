<?php

/**
 * This is the model class for table "store_categories".
 *
 * The followings are the available columns in table 'store_categories':
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property string $img
 * @property integer $lft
 * @property integer $rgt
 * @property integer $level
 * @property integer $root
 * @property string $seo
 * @property string $code 1C code id
 * 
 * @property Product $product 
 * @property Discount $discount 
 * @property Category $subcategories 
 * 
 * @property string $count_products 
 */
class Category extends CActiveRecord {

  public $count_products;

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_category';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('name', 'required'),
//      array('left_key, right_key, level', 'numerical', 'integerOnly' => true),
      array('name', 'length', 'max' => 30),
      array('code', 'length', 'max' => 11),
      array('url, img', 'length', 'max' => 255),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('name, url, img, seo', 'safe'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'product' => array(self::MANY_MANY, 'Product'
        , 'store_product_category(product_id, category_id)'),
      'discount' => array(
        self::MANY_MANY,
        'Discount',
        'store_discount_category(category_id, discount_id)'),
//      'subcategories' => array(self::HAS_MANY, 'Category', '',
//        'on' => 'subcategories.lft>t.lft AND subcategories.rgt<t.rgt AND subcategories.root=t.root'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'name' => 'Наименование',
      'url' => 'Url',
      'img' => 'Изображение',
      'level' => 'Уровень вложенности',
      'seo' => 'SEO текст'
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

    $criteria->compare('name', $this->name, true);
    $criteria->compare('url', $this->level, true);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Categories the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function behaviors() {
    return array(
      'NestedSetBehavior' => array(
        'class' => 'application.behaviors.NestedSetBehavior',
        'leftAttribute' => 'lft',
        'rightAttribute' => 'rgt',
        'levelAttribute' => 'level',
        'hasManyRoots' => true
      ),
    );
  }

  public function getUrlsForSitemap() {
    $sql = '
      set @cRank=0;
      set @cPage=0;
      set @cGroup=0;';
    Yii::app()->db->createCommand($sql)->execute();
    $sql = 'select id, page, max(update_time) time from
      (select 
    	@cRank:=IF(@cRank>:psize or @cGroup<>cat.id, 1, @cRank+1),
      @cPage:=if(@cRank=1, if(@cGroup<>cat.id, 1, @cPage+1), @cPage) page,
      @cGroup:=cat.id, cat.id, cat.product_id, greatest(cat.update_time, p.update_time) update_time
      from (select c.id id, c.update_time, pc.product_id product_id from store_category c
      left join store_category dc on dc.lft>c.lft and dc.rgt<c.rgt and dc.level<3
      left join store_product_category pc on c.id=pc.category_id or dc.id=pc.category_id 
      where product_id is not null  and c.level<3
      group by id, product_id
      order by c.id) cat
      left join store_product p on p.id=cat.product_id) p
      group by id, page;
  ';
    $psizes = Yii::app()->params['page_sizes'];
    $psize = current($psizes) - 1;
    $pages = Yii::app()->db->createCommand($sql)->bindParam(':psize', $psize)->queryAll();
    $urls = array();
    foreach ($pages as $value) {
      $urls[Yii::app()
              ->createUrl('group', array(
                'id' => $value['id'],
                'Product_page' => $value['page'],
              ))] = strtotime($value['time']);
    }
    return $urls;
  }

  public function beforeSave() {
    $this->update_time = \date('Y-m-d H:i:s');
    return parent::beforeSave();
  }

  public function hasProducts($root = null, $level = 1) {

    $this->getDbCriteria()->mergeWith(array(
//      'with' => array('subcategories' => array(
//          'select' => 'subcategories.id',
      'join' => 'LEFT JOIN store_category subcat ON subcat.lft>=t.lft AND subcat.rgt<=t.rgt AND subcat.root=t.root ' .
      'LEFT JOIN store_product_category pc ON pc.category_id=subcat.id ' .
      'LEFT JOIN store_product prod on prod.id=pc.product_id',
//        )),
      'select' => 't.id, t.name, t.lft, t.rgt, t.root, COUNT(pc.product_id) AS count_products',
      'order' => 't.lft',
      'condition' => 't.level=:level AND (t.root=:root OR :root IS NULL) AND '
      . '(subcat.lft>:lft OR :lft IS NULL) AND (subcat.rgt<:rgt OR :rgt IS NULL) '
      . 'AND prod.show_me=1 AND prod.remainder>0',
      'group' => 't.id',
      'together' => 'true',
      'having' => 'count_products>0',
      'params' => array(':root' => $root, ':level' => $level, ':lft' => $this->lft, ':rgt' => $this->rgt),
    ));
    return $this;
  }

  public function getBrands(){
    $brands = Brand::model()->findAll(array(
      'select' => 't.id, t.name',
      'with' => array('products'),
      'condition' => 'products.id IN (SELECT product_id FROM store_product_category WHERE category_id IN '
      . '(SELECT id FROM store_category WHERE lft>=:lft AND rgt<=:rgt ORDER BY lft))',
      'group' => 't.id',
      'order' => 't.name',
      'params' => array(':lft' => $this->lft, ':rgt' => $this->rgt),
    ));
    return $brands;
  }
}
