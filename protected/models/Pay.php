<?php

/**
 * This is the model class for table "store_pay".
 *
 * The followings are the available columns in table 'store_pay':
 * @property string $id
 * @property string $order_id
 * @property string $operation_id
 * @property string $amount
 * @property string $time
 * @property string $currency_iso 
 * @property string $currency_amount 
 * @property string $status_id 
 *
 * The followings are the available model relations:
 * @property Order $order
 * @property Currency $currency 
 * @property PayData[] $data
 * @property string $status 
 */
class Pay extends CActiveRecord {

  const NO_SUCH_TRANSACTION = 1, PENDING_CUSTOMER_INPUT = 2, PENDING_AUTH_RESULT = 3,
      AUTHORISED = 4, DECLINED = 5, REVERSED = 6, PAID = 7, REFUNDED = 8, INVALID_MID = 9,
      MID_DISABLED = 10;

  private static $statuses = array(
    0 => 'инициирован',
    1 => 'не существует',
    2 => 'ожидание данных',
    3 => 'ожидание авторизации',
    4 => 'авторизован',
    5 => 'отклонен',
    6 => 'отменен',
    7 => 'оплачен',
    8 => 'возвращен',
    9 => 'неверный ID ТСП',
    10 => 'ID ТСП заблокирован',
  );

  public function getStatus() {
    return self::$statuses[$this->status_id];
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_pay';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('order_id', 'required'),
      array('order_id', 'length', 'max' => 11),
      array('status_id', 'numerical', 'integerOnly' => TRUE),
      array('status_id', 'default', 'value' => 0),
      array('operation_id', 'length', 'max' => 30),
      array('amount, currency_amount', 'length', 'max' => 12),
      array('currency_iso', 'length', 'max' => 3),
      array('time', 'default', 'value' => date('Y-m-d H:i:s')),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, order_id, operation_id, amount, currency_amount', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'order' => array(self::BELONGS_TO, 'Order', 'order_id'),
      'currency' => array(self::BELONGS_TO, 'Currency', 'currency_iso'),
      'data' => array(self::HAS_MANY, 'PayData', 'pay_id'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'id' => 'ID',
      'order_id' => 'Заказ',
      'operation_id' => 'Номер операции',
      'amount' => 'Сумма',
      'time' => 'Дата платежа',
      'currency_iso' => 'Код валюты ISO',
      'currency_amount' => 'Сумма в валюте платежа',
      'status_id' => 'Статус платежа',
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
    $criteria->compare('order_id', $this->order_id, true);
    $criteria->compare('operation_id', $this->operation_id, true);
    $criteria->compare('amount', $this->amount, true);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Pay the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function setData($name, $value) {
    $data = PayData::model()->findByAttributes(array('pay_id' => $this->id, 'name' => $name));
    if (is_null($data)) {
      $data = new PayData();
      $data->pay_id = $this->id;
      $data->name = $name;
    }
    $data->value = $value;
    $data->save();
  }

  public function renewStatus($client = null) {
    Yii::import('application.modules.payments.models.Payment');

    $status_id = false;

    switch ($this->order->payment->type_id) {
      case Payment::TYPE_LIQPAY:
        Yii::import('ext.LiqPay');
        $liqpay = new LiqPay($this->order->payment->merchant_id, $this->order->payment->sign_key);
        $responce = $liqpay->api('payment/status', array(
          'order_id' => $this->order_id,
        ));
        if ($responce->result == 'ok') {
          if ($responce->payment_id == $this->operation_id) {
            $this->amount = $responce->amount;
            $this->currency = $responce->currency;
            $statuses = $this->order->payment->getStatuses();
            $status_id = constant("Pay::{$statuses[$responce->status]}");
            $this->status_id = $status_id;
            $this->save();
          }
          break;
        }
      case Payment::TYPE_PROCESSINGKZ:
        if (is_null($client)) {
          require_once Yii::app()->basePath . '/extensions/CNPMerchantWebServiceClient.php';
          $client = new CNPMerchantWebServiceClient();
        }
        $transactionResult = $this->order->payment->getProcessingKzStatus($client, $this->operation_id);
        $status_id = constant("Pay::{$transactionResult->return->transactionStatus}");
        switch ($status_id) {
          case Pay::PAID:
            $this->amount = $transactionResult->return->amountSettled / 100;
            break;
          case Pay::REVERSED:
            $this->amount = $transactionResult->return->amountRequested / 100;
            break;
          default :
            $this->amount = $transactionResult->return->amountAuthorised / 100;
        }
        $this->currency_amount = $this->amount;
        $this->status_id = $status_id;
        $this->save();

        $extendedTranResult = $this->order->payment->getProcessingKzStatus($client, $this->operation_id, TRUE);
        $this->setData('Код авторизации', $transactionResult->return->authCode);
        $this->setData('Имя владельца карты', $transactionResult->return->purchaserName);
        $this->setData('Email покупателя', $transactionResult->return->purchaserEmail);
        $this->setData('Телефон покупателя', $transactionResult->return->purchaserPhone);
        if ($extendedTranResult) {
          $this->setData('Страна банка-эмитента', $extendedTranResult->return->cardIssuerCountry);
          $this->setData('Часть номера карты', $extendedTranResult->return->maskedCardNumber);
          $this->setData('Проверка 3D пароля', $extendedTranResult->return->verified3D);
          $this->setData('IP адрес покупателя', $extendedTranResult->return->purchaserIpAddress);
        }
        break;
    }
    $oldStatus = $this->order->status_id;
    switch ($status_id) {
      case Pay::AUTHORISED:
      case Pay::PAID:
        $this->order->status_id = Order::STATUS_PAID;
        $this->order->save();
        $this->order->changeStatusMessage($oldStatus);
        break;
      case Pay::REVERSED:
        $this->order->status_id = Order::STATUS_CANCELED;
        $this->order->save();
        $this->order->changeStatusMessage($oldStatus);
        break;
    }
    return $status_id;
  }

}
