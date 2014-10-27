<?php

/**
 * This is the model class for table "store_order".
 *
 * The followings are the available columns in table 'store_order':
 * @property string $id
 * @property string $profile_id
 * @property string $coupon_id
 * @property string $delivery_id
 * @property string $delivery_summ
 * @property string $payment_id
 * @property string $status_id
 * @property string $time
 * @property string $fio
 * @property string $email
 * @property string $phone
 * @property string $country_code
 * @property integer $post_code
 * @property string $city
 * @property string $address
 * @property integer $call_time_id
 * @property string $description
 * @property string $currency_code
 * @property string $customer_delivery 
 * @property strins $exchange if true order should be passed to 1C
 *
 * The followings are the available model relations:
 * @property Coupon $coupon
 * @property Delivery $delivery
 * @property Payment $payment
 * @property string $status
 * @property array $statuses
 * @property CustomerProfile $profile
 * @property OrderProduct[] $orderProducts
 * @property Pay[] $pay
 * @property float $paySumm
 * @property float $authSumm
 * @property float $productSumm
 * @property float $discountSumm 
 * @property float $notDiscountSumm summ of goods without discount 
 * @property Currency $currency
 */
class Order extends CActiveRecord {

  const STATUS_UNPROCESS = 1, STATUS_IN_PROCESS = 2, STATUS_GOODS_NOT_AVAILABLE = 3,
      STATUS_WAITING_FOR_PAY = 4, STATUS_PAID = 5, STATUS_SENT = 6, STATUS_CANCELED = 7,
      STATUS_SHIPED = 8;

  public $summ;
  private static $statuses = array(
    1 => 'Необработан',
    2 => 'В обработке',
    3 => 'Нет в наличии',
    4 => 'Ожидание оплаты',
    5 => 'Оплачен',
    6 => 'Отгружен',
    7 => 'Отменен',
    8 => 'Доставлен',
  );

  public static function getStatuses() {
    return self::$statuses;
  }

  public function getStatus() {
    return self::$statuses[$this->status_id];
  }

  public function getPaymentOptions() {
    Yii::import('application.modules.payments.models.Payment');
    $payment = Payment::model()->findAll('active=1');
    return CHtml::listData($payment, 'id', 'name');
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_order';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    $rules = array(
      array('profile_id, delivery_id, payment_id, status_id, country_code, currency_code', 'required'),
      array('profile_id, delivery_id, payment_id, coupon_id', 'length', 'max' => 11),
      array('status_id', 'length', 'max' => 1),
      array('exchange', 'boolean', 'allowEmpty' => TRUE),
      array('exchange', 'default', 'value' => TRUE),
      array('profile_id, delivery_id, payment_id, status_id, coupon_id',
        'numerical', 'integerOnly' => true),
      array('phone', 'length', 'max' => 20),
      array('delivery_summ', 'numerical'),
      array('email', 'email'),
      array('fio, email, customer_delivery', 'length', 'max' => 255),
      array('post_code', 'length', 'min' => 6, 'max' => 6),
      array('country_code', 'length', 'max' => 2),
      array('currency_code', 'length', 'max' => 3),
      array('city', 'length', 'max' => 100),
      array('time, address, description', 'safe'),
      array('fio, email, phone, address, description, post_code, city, customer_delivery',
        'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
      array('customer_delivery', 'customerDelivery'),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, email, fio, phone, delivery_id, payment_id, status_id, time', 'safe', 'on' => 'search'),
    );
    if (Yii::app()->params['post_code'])
      $rules = array_merge($rules, array(array('post_code', 'required')));
    if (Yii::app()->params['country'])
      $rules = array_merge($rules, array(array('country_code', 'default', 'value' => Yii::app()->params['country'])));
    return $rules;
  }

  public function customerDelivery($attribute, $params) {
    Yii::import('application.modules.delivery.models.Delivery');
    if ($this->delivery->zone_type_id == Delivery::ZONE_CUSTOM && empty($this->$attribute))
      $this->addError($attribute, 'Укажите наименование транспортной компании');
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'coupon' => array(self::BELONGS_TO, 'Coupon', 'coupon_id'),
      'delivery' => array(self::BELONGS_TO, 'Delivery', 'delivery_id'),
      'payment' => array(self::BELONGS_TO, 'Payment', 'payment_id'),
      'pay' => array(self::HAS_MANY, 'Pay', 'order_id'),
      'paySumm' => array(self::STAT, 'Pay', 'order_id', 'select' => 'SUM(amount)',
        'condition' => 'status_id=' . Pay::PAID),
      'authSumm' => array(self::STAT, 'Pay', 'order_id', 'select' => 'SUM(amount)',
        'condition' => 'status_id=' . Pay::AUTHORISED),
      'profile' => array(self::BELONGS_TO, 'CustomerProfile', 'profile_id'),
      'orderProducts' => array(self::HAS_MANY, 'OrderProduct', 'order_id'),
      'productSumm' => array(self::STAT, 'OrderProduct', 'order_id',
        'select' => 'SUM(quantity*price)'),
      'discountSumm' => array(self::STAT, 'OrderProduct', 'order_id',
        'select' => 'SUM(quantity*discount)'),
      'notDiscountSumm' => array(self::STAT, 'OrderProduct', 'order_id',
        'select' => 'SUM(quantity*price)', 'condition' => 'NOT discount > 0'),
      'currency' => array(self::BELONGS_TO, 'Currency', 'currency_code'),
    );
  }

  public function getToPaySumm() {
    $coupon_discount = $this->getCouponSumm();
    $total = $this->productSumm + $this->delivery_summ - $coupon_discount;
    $paied = $this->paySumm + $this->authSumm;
    $to_pay = $total - $paied;
    return $to_pay;
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'id' => 'Номер',
      'profile_id' => 'Профиль',
      'profile_fio' => 'ФИО',
      'profile_email' => 'E-mail',
      'profile_phone' => 'Телефон',
      'coupon_id' => 'Купон',
      'delivery_id' => 'Способ доставки',
      'delivery_summ' => 'Способ доставки',
      'payment_id' => 'Способ оплаты',
      'status_id' => 'Статус',
      'time' => 'Дата',
      'fio' => 'ФИО',
      'email' => 'E-mail',
      'phone' => 'Телефон',
      'country_code' => 'Страна',
      'post_code' => 'Почтовый индекс',
      'city' => 'Город',
      'address' => 'Адрес',
      'call_time_id' => 'Время звонка',
      'description' => 'Комментарий',
      'summ' => 'Сумма',
      'currency_code' => 'Валюта заказа',
      'customer_delivery' => 'Транспортная компания покупателя',
      'exchange' => 'Передать в 1С'
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
    $criteria->with = array('profile', 'delivery', 'payment');
//    $criteria->together = true;

    $criteria->compare('t.id', $this->id, true);
    $criteria->compare('delivery_id', $this->delivery_id, true);
    $criteria->compare('t.fio', $this->fio, true);
    $criteria->compare('email', $this->email, true);
    $criteria->compare('t.phone', $this->phone, true);
    $criteria->compare('payment_id', $this->payment_id, true);
    $criteria->compare('status_id', $this->status_id, true);
    $criteria->compare("DATE_FORMAT(time,'%d.%m.%Y %T')", $this->time, true);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  public function scopes() {
    return array_merge(parent::scopes(), array(
      'timeOrderDesc' => array('order' => 'time DESC')
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Order the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function afterFind() {

    $this->time = Yii::app()->dateFormatter->format('dd.MM.yyyy HH:mm:ss', $this->time);
    parent::afterFind();
  }

  public function beforeSave() {
    $this->time = Yii::app()->dateFormatter->format('yyyy-MM-dd HH:mm:ss', $this->time);
//    $this->coupon_id
    return parent::beforeSave();
  }

//  public function getCouponDiscount() {
//    Yii::import('application.modules.discount.models.Coupon');
//    $summa = 0;
//    $total = 0;
//    if ($this->coupon) {
//      foreach ($this->orderProducts as $product) {
//        $total += $product->price * $product->quantity;
//        if ($product->discount == 0)
//          if ($this->coupon->type_id)
//            $summa += $product->price * $product->quantity * $this->coupon->value / 100;
//          else
//            $summa += $product->price * $product->quantity;
//      }
//      if (!$this->coupon->type_id)
//        if ($this->coupon->value < $summa)
//          $summa = $this->coupon->value;
//    }
//    return $summa;
//  }

  public function getCouponSumm() {
    Yii::import('application.modules.discount.models.Coupon');

    $couponSumm = 0;

    if (is_null($this->coupon))
      return $couponSumm;

    switch ($this->coupon->type_id) {
      case 0:
        $couponSumm = $this->coupon->value < $this->notDiscountSumm ? $this->coupon->value : $this->notDiscountSumm;
        break;
      case 1:
        $couponSumm = $this->notDiscountSumm * $this->coupon->value / 100;
    }
    return $couponSumm;
  }

  public function changeStatusMessage($oldStatus) {
    if ($oldStatus != $this->status_id) {
      Yii::import('application.modules.admin.models.Mail');
      Yii::import('application.modules.admin.models.MailOrder');
      $mail = new Mail;
      $mail->uid = $this->profile->user_id;
      $mail->type_id = 4;//Mail::TYPE_CHANGE_ORDER_STATUS;
//              $mail->status_id = 1;
      if ($mail->save()) {
        $mailOrder = new MailOrder;
        $mailOrder->mail_id = $mail->id;
        $mailOrder->order_id = $this->id;
        $mailOrder->save();
      }
    }
  }

}
