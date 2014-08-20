<?php

/**
 * This is the model class for table "store_delivery".
 *
 * The followings are the available columns in table 'store_delivery':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $length
 * @property integer $width
 * @property integer $height
 * @property integer $oversize
 * @property integer $insurance
 * @property boolean $active 
 * @property string $currency_code
 * @property string $max_weight
 * @property string $size_method_id
 * @property integer $size_summ
 * @property string $zone_type_id
 * @property string $transport_type_id 
 *
 * The followings are the available model relations:
 * @property DeliveryRate[] $deliveryRates
 * @property RegionDelivery[] $regionDeliveries
 * @property Currency $currency 
 * @property array $size_methods
 * @property string $size_method
 * @property array $zone_types
 * @property string $zone_type
 * @property array $nrjTypes
 * @property string $nrjType 
 * @property array $transportTypes 
 * @property string $transportType 
 */
class Delivery extends CActiveRecord {

  private static $size_methods = array(1 => 'Сумма длины и окружности', 2 => 'Ограничение дл. шир. выс.');
  private static $zone_type = array(1 => 'КазПочта', 2 => 'EMS-Казахстан', 3 => 'т/к Энергия', 4 => 'т/к покупателя', 5 => 'Курьер', 6 => 'Самовывоз');
  private static $nrj_types = array(1 => 'avto', 2 => 'avia', 3 => 'rw');
  private static $transport_types = array(1 => 'авто', 2 => 'авиа', 3 => 'ж/д');

  public static function getSize_methods() {
    return self::$size_methods;
  }

  public function getSize_method() {
    return self::$size_methods[$this->size_method_id];
  }

  public static function getZone_types() {
    return self::$zone_type;
  }

  public function getZone_type() {
    return self::$zone_type[$this->zone_type_id];
  }

  public static function getNrjTypes() {
    return self::$nrj_types;
  }

  public function getNrjType() {
    return self::$nrj_types[$this->transport_type_id];
  }

  public static function getTransportTypes() {
    return self::$transport_types;
  }

  public function getTransportType() {
    return isset(self::$transport_types[$this->transport_type_id]) ? self::$transport_types[$this->transport_type_id] : '';
  }

  /**
   * @return string the associated database table name
   */
  public function tableName() {
    return 'store_delivery';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('name', 'required'),
      array('length, width, height, oversize, insurance, size_method_id, size_summ, zone_type_id', 'numerical', 'integerOnly' => true),
      array('max_weight', 'numerical', 'numberPattern' => '/\d{1,2}\.?\d{0,2}/'),
      array('transport_type_id', 'numerical', 'integerOnly' => true),
      array('transport_type_id', 'length', 'max' => 1),
      array('length, width, height, oversize, size_summ', 'length', 'max' => 4),
      array('oversize, currency_code', 'length', 'max' => 3),
      array('insurance, zone_type_id', 'length', 'max' => 2),
      array('name', 'length', 'max' => 30),
      array('active, max_weight', 'default', 'value' => 0),
      array('description', 'safe'),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, name, description', 'safe', 'on' => 'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
      'deliveryRates' => array(self::HAS_MANY, 'DeliveryRate', 'delivery_id'),
      'currency' => array(self::BELONGS_TO, 'Currency', 'currency_code'),
      'regionDeliveries' => array(self::HAS_MANY, 'RegionDelivery', 'delivery_id'),
      'zones' => array(self::HAS_MANY, 'Zone', 'zone'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
      'id' => 'ID',
      'name' => 'Наименование',
      'description' => 'Описание',
      'length' => 'Длина',
      'width' => 'Ширина',
      'height' => 'Высота',
      'oversize' => 'Доплата за превышение (%)',
      'insurance' => 'Страховка (%)',
      'active' => 'Активный',
      'currency_code' => 'Валюта',
      'max_weight' => 'Максимальный вес (кг)',
      'size_method_id' => 'Способ расчета',
      'size_summ' => 'Сумма размеров',
      'zone_type_id' => 'Тарифные зоны',
      'transport_type_id' => 'Вид транспорта',
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
    $criteria->compare('description', $this->description, true);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Delivery the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function region($country_code, $post_code, $city, $delivery_id) {
    Yii::import('application.modules.delivery.models.RegionDelivery');
    Yii::import('application.modules.delivery.models.Zone');
    $this->getDbCriteria()->mergeWith(array(
      'with' => array(
        'regionDeliveries' => array(
          'with' => array(
            'zones',
            'region',
          ),
        ),
      ),
      'condition' => '(:pcode REGEXP zones.post_code AND region.country_code=:ccode AND zones.country_code=:ccode OR t.zone_type_id IN (4,6) OR t.zone_type_id=3 AND :city=0 OR t.zone_type_id=5 AND :city=1) AND (:delivery_id IS NULL OR t.id=:delivery_id) AND t.active=1',
      'params' => array(':ccode' => $country_code, ':pcode' => $post_code, ':city' => empty($city), ':delivery_id' => $delivery_id)
    ));
    return $this;
  }

  public static function getDeliveryList($country_code, $post_code, $city, $model, Order $order, $delivery_id = null) {

    Yii::import('application.modules.delivery.models.DeliveryRate');
    Yii::import('application.modules.delivery.models.Region');

    $items = is_array($model) ? $model : $model->orderProducts;

    $total_weight = 0;
    $product_weights = array();
    $product_sizes = array();
    $product_lengths = array();
    $product_widths = array();
    $product_heights = array();
    foreach ($items as $item) {

      /* @var $item Cart */
      /* @var $item OrderProduct */
      $length = (int) $item->product->length;
      $width = (int) $item->product->width;
      $height = (int) $item->product->height;
      $weight = (float) $item->product->weight;
      $product_id = (int) $item->product_id;
      $quantity = (int) $item->quantity;

      $total_weight += $weight * (int) $quantity;
      for ($i = 0; $i < $quantity; $i++) {
        $product_lengths[] = $length;
        $product_widths[] = $width;
        $product_heights[] = $height;
        $product_sizes[] = array(
          $length,
          $width,
          $height,
          $weight,
          $product_id,
        );
        $product_weights[] = $weight;
      }
    }
    if ($product_weights)
      $max_weight = max($product_weights);
    else
      $max_weight = 0;
    arsort($product_weights);
    array_multisort($product_lengths, SORT_DESC, $product_widths, SORT_DESC
        , $product_heights, SORT_DESC, $product_widths, SORT_DESC, $product_sizes);

    Yii::import('application.modules.delivery.models.NrjLocation');
    $pref = '^';
    $suff = '($|\\(|\\*|\\,|\\ )';
    $location = NrjLocation::model()->find('LOWER(name) REGEXP :name', array(':name' => $pref . mb_strtolower(quotemeta(trim($city)), 'UTF-8') . $suff));
    $location_from = NrjLocation::model()->find('LOWER(name) REGEXP :name', array(':name' => $pref . mb_strtolower(quotemeta(trim(Yii::app()->params['enterprise']['city'])), 'UTF-8') . $suff));
    if (!$location || $location == $location_from)
      $city = ''; //exclude Energy delivery

    $models = self::model()->region($country_code, $post_code, $city, $delivery_id)->findAll();

    $list = array();
    $list_oversize = array();
    if ($product_sizes){
      $storage_delivery = array();
      foreach ($models as $delivery) {
        /* @var $delivery Delivery */

        $parcels = self::checkSizes($product_sizes, $delivery);
        if (!$parcels['result']) {
          if (isset($parcels['oversize_items']))
            if ($list_oversize)
              $list_oversize = array_intersect($parcels['oversize_items'], $list_oversize);
            else
              $list_oversize = $parcels['oversize_items'];
          continue;
        }

        $price = 0;
        $nrj_weght = 0;
        $nrj_places = 0;
        foreach ($parcels['parcels'] as $parcel) {

          $oversize = isset($parcel['oversize']) && $parcel['oversize'] ? 1 + $delivery->oversize / 100 : 1;

          switch ($delivery->zone_type_id) {
            case 3:
              $nrj_places++;
              $nrj_weght += $parcel['weight'];
              break;
            case 4:
            case 5:
            case 6:
              break;
            default :
              $deliveryRate = self::getDeliveryRate($delivery, $parcel['weight']);
              if ($deliveryRate)
                $price += $deliveryRate->price * $oversize;
              else {
                $deliveryMaxRate = DeliveryRate::model()->findByAttributes(array(
                  'delivery_id' => $delivery->id,
                  'region_id' => $delivery->regionDeliveries[0]->region_id,
                    ), array(
                  'order' => 'weight DESC',
                ));
                /* @var $deliveryMaxRate DeliveryRate */
                $addition_weight = ceil($parcel['weight'] - $deliveryMaxRate->weight);
                $price += ($deliveryMaxRate->price + $delivery->regionDeliveries[0]->weight_rate * $addition_weight) * $oversize;
              }
          }
        }

        Yii::import('application.modules.payments.models.Currency');
        Yii::import('application.modules.payments.models.CurrencyRate');
        if (Yii::app()->params['mcurrency'])
          if ($item instanceof Cart)
            $currency = Currency::model()->findByCountry(ProfileController::getProfile()->price_country);
          else
            $currency = Currency::model()->findByPk($model->currency_code);
        else
          $currency = Currency::model()->findByCountry(Yii::app()->params['country']);
        /* @var $currency Currency */
        if ($delivery->currency_code != $currency->code) {
          $curency_rate = CurrencyRate::model()->findByAttributes(array(
            'from' => $delivery->currency_code,
            'to' => $currency->code
              ), array('order' => 'date DESC'));
          /* @var $curency_rate CurrencyRate */
          if ($curency_rate)
            $price = round($price * $curency_rate->rate * $curency_rate->to_quantity / $curency_rate->from_quantity);
          else
            $price = 'Стоимость не определена (курс валют не установлен)';
        }
        else
          $price = round($price);

        if (is_array($model) && !$delivery_id) { //if model is carts array or call not from save order function
          $output = CHtml::tag('span', array(
                'class' => 'bold',
                'price' => $price,
                  ), $delivery->name);
        }
        switch ($delivery->zone_type_id) {
          case 3: //it's Energy delivery company
            if (!isset($nrj_deliveries))
              if ($location && $location != $location_from) {
                $nrj_ch = curl_init("http://api.nrg-tk.ru/api/rest/?method=nrg.calculate&from=$location_from->id&to=$location->id&weight=$nrj_weght&volume=0&place=$nrj_places");
                curl_setopt($nrj_ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($nrj_ch, CURLOPT_HEADER, FALSE);
                $nrj_get = curl_exec($nrj_ch);
                curl_close($nrj_ch);
                $nrj_deliveries = json_decode($nrj_get, TRUE);
              }
              else
                continue 2;
            if (isset($nrj_deliveries['rsp']['stat']) && $nrj_deliveries['rsp']['stat'] == 'ok') {
              reset($nrj_deliveries['rsp']['values']);
              $value = FALSE;
              while ($v = each($nrj_deliveries['rsp']['values']))
                if ($v[1]['type'] == $delivery->nrjType) {
                  $value = $v[1];
                  break;
                }
              if ($value) {
                $price = ceil($value['price']);
                if (is_array($model) && !$delivery_id) {
                  $output = CHtml::tag('span', array(
                        'class' => 'bold',
                        'price' => $price,
                          ), $delivery->name);
                  $storage_delivery[$delivery->id]['summ'] = $price; //save price for order edit
                  $output .= ' (' . $delivery->transportType . " доставка {$value['term']}) " . CHtml::tag('span', array('class' => 'red delivery-price'), $price . $currency->class);
                }
                else {
                  $list['params'][$delivery->id]['price'] = $price;
                  $list['options'][$delivery->id] = $delivery->name . ' (' . $delivery->transportType . ')';
                  continue 2;
                }
                break;
              }
            }
            continue 2;
          case 4: //it's customer delivery company
            if (is_array($model) && !$delivery_id) {
              $storage_delivery[$delivery->id]['summ'] = $price; //save price for order
              $html_options = array();
              if ($order->delivery_id != $delivery->id)
                $html_options['disabled'] = true;
              $output .= '<br>' . CHtml::activeTextField($order, 'customer_delivery', $html_options) . CHtml::error($order, 'customer_delivery', array('class' => 'red')) . '<div>(' . $delivery->description . ')</div>';
            }else {
              $list['params'][$delivery->id]['price'] = $price;
              $list['options'][$delivery->id] = $delivery->zone_type . ' (' . $order->customer_delivery . ')';
              continue 2;
            }
            break;
          case 5:
          case 6:
            if (is_array($model) && !$delivery_id) {
              $storage_delivery[$delivery->id]['summ'] = $price; //save price for order
              $output .= ' (' . $delivery->description . ') ';
              break;
            }
          default :
            if (is_array($model) && !$delivery_id) {
              $storage_delivery[$delivery->id]['summ'] = $price; //save price for order
              $output .= ' (' . $delivery->description . ') ' . CHtml::tag('span', array('class' => 'red delivery-price'), $price . $currency->class);
            }
        }
        if (is_array($model) && !$delivery_id)
          $list[$delivery->id] = $output;
        else {
          $list['params'][$delivery->id]['price'] = $price;
          $list['options'][$delivery->id] = $delivery->name;
        }
      }
      if ($storage_delivery)
        Yii::app()->user->setState('delivery', $storage_delivery);
    }
    if (is_array($model) && !$delivery_id) {//if model is carts array or call not from save order function
      $output = '';
      if (count($list) > 0) {
        if (!isset($list[$order->delivery_id]))
          $order->delivery_id = 1;
        return $list;
      }
      elseif ($list_oversize) {
        $output = 'В настоящий момент отправка следующих крупногабаритных товаров в ваш регион не осуществляется:';
        $output .= CHtml::openTag('ul', array('style' => 'margin-top:5px'));
        foreach ($list_oversize as $item) {
          $product = Product::model()->findByPk($item);
          $output .= CHtml::tag('li', array(), $product->name);
        }
        $output .= CHtml::closeTag('ul');
      }
      elseif (count($product_weights) == 0) {
        $output = CHtml::tag('span', array('class' => 'red'), 'Не выбран товар');
      }
      return $output;
    }
    else {
      return $list;
    }
  }

  /**
   * Find apopriate delivery rate
   * @param Delivery $delivery
   * @param float $weight
   * @return DeliveryRate
   */
  private static function getDeliveryRate(Delivery $delivery, $weight) {
    return DeliveryRate::model()->findByAttributes(array(
          'delivery_id' => $delivery->id,
          'region_id' => $delivery->regionDeliveries[0]->region_id,
            ), array(
          'condition' => 'weight>=:total_weight',
          'order' => 'weight',
          'params' => array(':total_weight' => $weight)
    ));
  }

  /**
   * 
   * @param array $items quantity and sizes of items
   * @param array $volumes
   * @param type $delivery
   */
  private static function checkSizes(array $items, Delivery &$delivery) {

    $volume = array($delivery->length, $delivery->width, $delivery->height, 0, 0, 0);

    //check if there are items that oversize for size method 0 or overweight for size method 1
    $oversize_items = array();
    switch ($delivery->size_method_id) {
      case 1:
        foreach ($items as $item) {
          if ($item[0] > $volume[0] || $item[1] > $volume[1] || $item[2] > $volume[2] ||
              $item[0] > $volume[0] || $item[2] > $volume[1] || $item[1] > $volume[2] ||
              $item[1] > $volume[0] || $item[0] > $volume[1] || $item[2] > $volume[2] ||
              $item[1] > $volume[0] || $item[2] > $volume[1] || $item[0] > $volume[2] ||
              $item[2] > $volume[0] || $item[0] > $volume[1] || $item[1] > $volume[2] ||
              $item[2] > $volume[0] || $item[1] > $volume[1] || $item[0] > $volume[2]) {
            $oversize_items[] = $item[4];
          }
          else {
            $dim = array_slice($item, 0, 3);
            rsort($dim);
            $size = $dim[0] + ($dim[1] + $dim[2]) * 2;
            if ($size > $delivery->size_summ) {
              $oversize_items[] = $item[4];
            }
          }
        }
//        break;
      case 2:
        foreach ($items as $item) {
          if ($item[3] > $delivery->max_weight) {
            $oversize_items[] = $item[4];
          }
        }
        break;
    }
    if ($oversize_items)
      return array('result' => FALSE, 'oversize_items' => array_unique($oversize_items));

    $parcels = array();
    switch ($delivery->size_method_id) {
      case 1:
        while (count($items) > 0) {
          $parcels['parcels'][] = array('weight' => self::makeParcel($items, $volume, $delivery));
        }
        $parcels['result'] = true;
        break;
      case 2:
        while (count($items) > 0) {
          $parcel_items = array();
          $weight = 0;
          foreach ($items as $key => $item) {
            if ($weight + $item[3] > $delivery->max_weight) {
              continue;
            }
            else {
              $weight += $item[3];
              $parcel_items[$key] = $item;
            }
          }
          $items = array_diff_key($items, $parcel_items);
          self::makeParcel($parcel_items, $volume, $delivery);
          $parcels['parcels'][] = array(
            'weight' => $weight,
            'oversize' => count($parcel_items) > 0,
          );
        }
        $parcels['result'] = true;
        break;
    }

    return $parcels;
  }

  private static function makeParcel(&$items, $volume, &$delivery) {

    $data = array(
      'items' => &$items,
      'length' => 0,
      'width' => 0,
      'height' => 0,
      'weight' => 0,
    );
    self::placeItems($data, $volume, $delivery);
    return $data['weight'];
  }

  private static function placeItems(array &$data, array $volume, Delivery &$delivery) {
    static $orientations =
    array(
      array(0, 1, 2),
      array(0, 2, 1),
      array(1, 0, 2),
      array(1, 2, 0),
      array(2, 0, 1),
      array(2, 1, 0),
    );

    foreach ($data['items'] as $key => $item) {

      $weight = $data['weight'] + $item[3];
      if ($weight > $delivery->max_weight) {
        continue;
      }

      foreach ($orientations as $orientation) {

        if ($item[$orientation[0]] <= $volume[0] && $item[$orientation[1]] <= $volume[1] && $item[$orientation[2]] <= $volume[2]) {

          $length = max(array($data['length'], $volume[3] + $item[$orientation[0]]));
          $width = max(array($data['width'], $volume[4] + $item[$orientation[1]]));
          $height = max(array($data['height'], $volume[5] + $item[$orientation[2]]));

          switch ($delivery->size_method_id) {
            case 1:
              $size_summ = $length + ($width + $height) * 2;
              if ($size_summ > $delivery->size_summ)
                continue 2;
              break;
            case 2:
//              $data['oversize'] = ($length > $delivery->length) || $data['oversize'];
              break;
          }

          $data['length'] = $length;
          $data['width'] = $width;
          $data['height'] = $height;
          $data['weight'] += $item[3];

          $data['items'] = array_diff_key($data['items'], array($key => $item));
          if (count($data['items']) == 0)
            return;

          $new_volumes = array(
            array($item[$orientation[0]], $item[$orientation[1]], $volume[2] - $item[$orientation[2]],
              $volume[3], $volume[4], $volume[5] + $item[$orientation[2]]),
            array($item[$orientation[0]], $volume[1] - $item[$orientation[1]], $volume[2],
              $volume[3], $volume[4] + $item[$orientation[1]], $volume[5]),
            array($volume[0] - $item[$orientation[0]], $volume[1], $volume[2],
              $volume[3] + $item[$orientation[0]], $volume[4], $volume[5]),
          );

          foreach ($new_volumes as $v) {
            self::placeItems($data, $v, $delivery);
            if (count($data['items']) == 0)
              return;
          }
          return; //$rest_items;
        }
      }
    }
//    return $rest_items;
  }

  public static function getList() {
    $items = self::model()->findAll();
    /* @var $items Delivery[] */
    $options = array();
    foreach ($items as $item) {
      switch ($item->zone_type_id) {
        case 3:
          $options[$item->id] = $item->name . ' (' . $item->transportType . ')';
          break;
        case 4:
          $options[$item->id] = $item->zone_type;
          break;
        default :
          $options[$item->id] = $item->name;
      }
    }
    return $options;
  }

}
