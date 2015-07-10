<?php

/**
 * This is the model class for table "store_payment".
 *
 * The followings are the available columns in table 'store_payment':
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $type_id
 * @property string $active
 * @property string $merchant_id
 * @property string $sign_key
 * @property string $currency_code
 *
 * The followings are the available model relations:
 * @property Order[] $orders
 * @property array $params
 * @property string $type
 */
class Payment extends CActiveRecord
{

    const TYPE_CAHSH = 0, TYPE_LIQPAY = 1, TYPE_PROCESSINGKZ = 2, TYPE_BANK = 3;

    private static $types = array('Наличными', 'LiqPay', 'Processing.kz', 'На расчетный счет'),
        $actionUrl = array(
        0 => '',
        1 => 'https://www.liqpay.com/api/pay',
        2 => '',
        3 => '',
    ),
        $signName = array(
        0 => '',
        1 => 'signature',
        2 => '',
        3 => '',
    ),
        $statuses = array(
        0 => array(),
        1 => array(
            'success' => 'PAID',
            'failure' => 'DECLINED',
            'wait_secure' => 'PENDING_AUTH_RESULT',
            'sandbox' => 'PAID',
        ),
        2 => array(),
        3 => array(),
    ),
        $params = array(
        0 => array(),
        1 => array(
            'public_key' => '$this->merchant_id',
            'amount' => '$order->getToPaySumm()',
            'currency' => '$this->currency_code',
            'description' => '"Оплата заказа"',
            'order_id' => '$order->id',
            'type' => '"buy"',
            'server_url' => 'Yii::app()->createAbsoluteUrl("/pay/liqPayNotify")',
            'result_url' => 'Yii::app()->createAbsoluteUrl("/pay/result")',
            'pay_way' => '"card"',
            'language' => '"ru"',
            'sandbox' => '"0"',
        ),
        2 => array(
            'processingkz' => '"1"',
        ),
        3 => array(),
    );

    public static function getTypes()
    {
        return self::$types;
    }

    public function getType()
    {
        return self::$types[$this->type_id];
    }

    public function getParams($order)
    {
        $params = array();
        ob_start();
        foreach (self::$params[$this->type_id] as $key => $param) {
            eval('echo ' . $param . ';');
            $value = ob_get_contents();
            ob_clean();
            if ($value)
                $params[$key] = $value;
        }
        ob_end_clean();
        return $params;
    }

    public function getSing($params)
    {
        $sign = '';
        switch ($this->type_id) {
            case 1:
                Yii::import('ext.LiqPay');
                $liqPay = new LiqPay($this->merchant_id, $this->sign_key);
                $sign = $liqPay->cnb_signature($params);
                break;
        }
        return $sign;
    }

    public function getSignName()
    {
        return self::$signName[$this->type_id];
    }

    public function getActionUrl()
    {
        return self::$actionUrl[$this->type_id];
    }

    public function getStatuses()
    {
        return self::$statuses[$this->type_id];
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'store_payment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, description, type_id, currency_code', 'required'),
            array('type_id', 'numerical', 'integerOnly' => true),
            array('name, merchant_id, sign_key', 'length', 'max' => 255),
            array('currency_code', 'length', 'max' => 3),
            array('active', 'boolean'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, description', 'safe', 'on' => 'search'),
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
            'orders' => array(self::HAS_MANY, 'Order', 'payment_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Наименование',
            'description' => 'Описание',
            'type_id' => 'Платежная система',
            'active' => 'Активный',
            'merchant_id' => 'ID магазина',
            'sign_key' => 'Ключ подписи',
            'currency_code' => 'Валюта',
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Payment the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function getPaymentList($currency_code = NULL)
    {
        $models = self::model()->findAll("active=1 AND (currency_code='' OR currency_code=:curr_cod OR :curr_cod IS NULL)"
            , array(':curr_cod' => $currency_code));
        $list = array();
        foreach ($models as $payment) {
            /* @var $payment Payment */
            $params = ['style' => 'display:table-cell;vertical-align:middle'];
            if ($payment->type_id == self::TYPE_CAHSH)
                $params['data-cash'] = true;
            $output = CHtml::openTag('span', $params);
            switch ($payment->type_id) {
                case 0:
                    $payment_class = 'cash';
                    break;
                case 3:
                    $payment_class = 'bank';
                    break;
                default;
                    $payment_class = 'cart';
            }
            $output .= CHtml::tag('span', array('class' => 'payment-' . $payment_class), '');
            $output .= CHtml::closeTag('span');
            $output .= CHtml::opentag('span', array('style' => 'display:inline-block;width:320px'));
            $output .= CHtml::tag('span', array(
                'class' => 'bold',
                'style' => 'margin-bottom:5px',
            ), $payment->name);
            $output .= '<br>';
            $output .= $payment->description;
            $output .= CHtml::closeTag('span');
            $list[$payment->id] = $output;
        }
        return $list;
    }

    public function getProcessingKzStatus($client, $rrn, $extended = false)
    {
        if ($extended)
            $params = new getExtendedTransactionStatus();
        else
            $params = new getTransactionStatus();
        $params->merchantId = $this->merchant_id;
        $params->referenceNr = $rrn;
        if ($extended)
            $transactionResult = $client->getExtendedTransactionStatus($params);
        else
            $transactionResult = $client->getTransactionStatus($params);
        return $transactionResult;
    }

}
