<?php

/**
 * CalcDelivery is a class for making list of avalable deliveries 
 * and calc delivery fee.
 *
 * @author Andrew <android.2netg@gmail.com>
 */
class CalcDelivery {

  /**
   * Return list of deliveries avalable fo the location.
   * @param string $country_code Country code of the location
   * @param string $post_code Post code of the location
   * @param string $city City
   * @param array $products Frray of Catr, OrderProduct or String
   * @param Order $order Order
   * @param int $delivery_id Selected delivery
   * @return array list of deliveries
   */
  public static function getDeliveryList($country_code, $post_code, $city, $products, Order $order, $delivery_id = null) {

    Yii::import('application.modules.delivery.models.DeliveryRate');
    Yii::import('application.modules.delivery.models.Region');
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.payments.models.Currency');
    Yii::import('application.modules.payments.models.CurrencyRate');

    $items = $products;
    $type = self::getItemsType($items, $delivery_id);
    $product_sizes = self::getProductSizes($items);

    $list = array();
    if (count($product_sizes) == 0) {
      if ($type == 0) {//if model is Cart array and call not from save order function
        $output = CHtml::tag('span', array('class' => 'red'), 'Не выбран товар');
        return $output;
      }
      else {
        return $list;
      }
    }

    $city_from = trim(Yii::app()->params['enterprise']['city']);
    if (mb_strtolower(trim($city), 'UTF-8') == mb_strtolower(trim($city_from), 'UTF-8'))
      $city = ''; //if city and city_from are same exclude Energy delivery

    $models = Delivery::model()->region($country_code, $post_code, $city, $delivery_id)->findAll();

    $storage_delivery = array();
    $list_oversize = array();
    foreach ($models as $delivery) {
      /* @var $delivery Delivery */

      $parcels = self::checkSizes($product_sizes, $delivery);
      if (!$parcels['result']) {
        self::collectOversizeItems($parcels, $list_oversize);
        continue;
      }

      $price = 0;
      $nrjValue = FALSE;
      if (!self::calcPrice($parcels, $delivery, $city, $city_from, $price, $nrjValue))
        continue;

      $currency = self::getCurrency($items, $order->currency_code);

      if (!self::convertCurrency($delivery->currency_code, $currency->code, $price))
        continue;

      if ($type == 0) { //if model is carts array or call not from save order function
        $params = ['class' => 'bold', 'data-price' => $price];
        if ($delivery->zone_type_id == Delivery::ZONE_SELF)
          $params['data-self'] = true;
        $output = CHtml::tag('span', $params, $delivery->name);
        $storage_delivery[$delivery->id]['summ'] = $price; //save price for order edit
      }
      switch ($delivery->zone_type_id) {
        case Delivery::ZONE_NRJ: //it's Energy delivery company
          if ($type == 0) {
            $output .= ' (' . $delivery->transportType . " доставка {$nrjValue['term']}) "
                . CHtml::tag('span', array('class' => 'red delivery-price'), $price . $currency->class);
          }
          elseif ($type == 1) {
            $list['params'][$delivery->id]['price'] = $price;
            $list['options'][$delivery->id] = $delivery->name . ' (' . $delivery->transportType . ')';
            continue 2;
          }
          else {
            $list[$delivery->id] = array('price' => $price, 'text' => $delivery->name . ' (' . $delivery->transportType . ')');
            continue 2;
          }
          break;
        case Delivery::ZONE_CUSTOM: //it's customer delivery company
          if ($type == 0) {
            $html_options = array();
            if ($order->delivery_id != $delivery->id)
              $html_options['disabled'] = true;
            $output .= '<br>' . CHtml::activeTextField($order, 'customer_delivery', $html_options) .
                CHtml::error($order, 'customer_delivery', array('class' => 'red')) . '<div>(' . $delivery->description . ')</div>';
          }elseif ($type == 1) {
            $list['params'][$delivery->id]['price'] = $price;
            $list['options'][$delivery->id] = $delivery->zone_type . ' (' . $order->customer_delivery . ')';
            continue 2;
          }
          else {
            $list[$delivery->id] = array('price' => $price, 'text' => $delivery->zone_type . ' (' . $order->customer_delivery . ')');
            continue 2;
          }
          break;
        case Delivery::ZONE_COURIER:
        case Delivery::ZONE_SELF:
          if ($type == 0) {
            $output .= ' (' . $delivery->description . ') ';
            break;
          }
//          elseif ($type == 1) {
//            $list['params'][$delivery_id]['price'] = $price;
//            $list['options'][$delivery_id] = $delivery->name . ' ' . $delivery->description;
//          }
        default :
          if ($type == 0) {
            $output .= ' (' . $delivery->description . ') ' .
                CHtml::tag('span', array('class' => 'red delivery-price'), $price . $currency->class);
          }
      }
      if ($type == 0)
        $list[$delivery->id] = $output;
      elseif ($type == 1) {
        $list['params'][$delivery->id]['price'] = $price;
        if ($delivery->zone_type_id == Delivery::ZONE_SELF)
          $list['options'][$delivery->id] = $delivery->name. ' ' . $delivery->description;
        else
          $list['options'][$delivery->id] = $delivery->name;
      }
      else {
        $list[$delivery->id] = array('price' => $price, 'text' => $delivery->name);
      }
    }
    if ($storage_delivery)
      Yii::app()->user->setState('delivery', $storage_delivery);
    if ($type == 0) {//if model is carts array and call not from save order function
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
      return $output;
    }
    else {
      return $list;
    }
  }

  /**
   * Function return type of the deliveri list and if the items is an array of strings then them
   * will be converted to an object with fields like Cart or OrderProduct has.
   * @param array $items Array of OrderProducts or String
   * @return int type of return result: 0 - radio list options, 
   * 1 - selest list options, 2 - list options for ajax
   */
  private static function getItemsType(&$items, $delivery_id) {
    $type = 0;

    if ($delivery_id)
      $type = 1;

    $products = $items;
    if (!(is_array($products) || count($products))) {
      return $type;
    }

    $currentItem = current($products);
    if ($currentItem instanceof OrderProduct) {
      $type = 1;
    }
    elseif (is_string($currentItem)) {
      $type = 2;
      $items = array();
      foreach ($products as $id => $quantity) {
        $product = Product::model()->findByPk($id);
        if ($product) {
          $item = new stdClass;
          $item->product = $product;
          $item->product_id = $id;
          $item->quantity = $quantity;
          $items[] = $item;
        }
      }
    }
    return $type;
  }

  /**
   * Retur array of products data fo delivery fee calculation
   * @param array $items Array of Cart, OrderProduct or stdClass
   * @return array Array of arrays of product data (length, width, height, weight, product_id)
   */
  private static function getProductSizes($items) {
    $total_weight = 0;
    $product_weights = array();
    $product_sizes = array();
    $product_lengths = array();
    $product_widths = array();
    $product_heights = array();
    foreach ($items as $item) {

      /* @var $item Cart */
      /* @var $item OrderProduct */
      $length = (float) $item->product->length;
      $width = (float) $item->product->width;
      $height = (float) $item->product->height;
      $weight = (float) $item->product->weight;
      $product_id = (int) $item->product_id;
      $quantity = (int) $item->quantity;

      $total_weight += $weight * $quantity;
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
//    if ($product_weights)
//      $max_weight = max($product_weights);
//    else
//      $max_weight = 0;
    arsort($product_weights);
    array_multisort($product_lengths, SORT_DESC, $product_widths, SORT_DESC
        , $product_heights, SORT_DESC, $product_sizes);

    return $product_sizes;
  }

  /**
   * Calc price for the delivery
   * @param array $parcels parcels data
   * @param Delivery $delivery delivery for which price calc
   * @param string $city destination city
   * @param string $city_from city fron whicj delivery
   * @param float $price here will be stored price
   * @param array $nrjValue result data for Nrj delivery
   * @return boolean if true plase the delivery to list if false continue to calc next delivery
   */
  private static function calcPrice($parcels, $delivery, $city, $city_from, &$price, &$nrjValue) {
    $nrj_weight = 0;
    $nrj_places = count($parcels['parcels']);
    foreach ($parcels['parcels'] as $parcel) {

      $oversize = isset($parcel['oversize']) && $parcel['oversize'] ? 1 + $delivery->oversize / 100 : 1;

      switch ($delivery->zone_type_id) {
        case Delivery::ZONE_NRJ:
//          $nrj_places++;
          $nrj_weight += $parcel['weight'];
          break;
        case Delivery::ZONE_CUSTOM:
        case Delivery::ZONE_COURIER:
        case Delivery::ZONE_SELF:
          break;
        default :
          $deliveryRate = self::getDeliveryRate($delivery, $parcel['weight']);
          if ($deliveryRate)
            $price += $deliveryRate->price * $oversize * (1 + $delivery->insurance / 100);
          else {
            $deliveryMaxRate = DeliveryRate::model()->findByAttributes(array(
              'delivery_id' => $delivery->id,
              'region_id' => $delivery->regionDeliveries[0]->region_id,
                ), array(
              'order' => 'weight DESC',
            ));
            /* @var $deliveryMaxRate DeliveryRate */
            $addition_weight = ceil($parcel['weight'] - $deliveryMaxRate->weight);
            $price += ($deliveryMaxRate->price + $delivery->regionDeliveries[0]->weight_rate * $addition_weight) *
                $oversize * (1 + $delivery->insurance / 100);
          }
      }
    }

    if ($delivery->zone_type_id == Delivery::ZONE_NRJ) {
      if (!isset($nrj_deliveries)) {
        $nrj_deliveries = self::getNrjDeliveries($city, $city_from, $nrj_weight, $nrj_places);
      }
      if (is_null($nrj_deliveries))
        return false;

      if (!(isset($nrj_deliveries['rsp']['stat']) && $nrj_deliveries['rsp']['stat'] == 'ok')) {
        return false;
      }
      reset($nrj_deliveries['rsp']['values']);
      while ($v = each($nrj_deliveries['rsp']['values']))
        if ($v[1]['type'] == $delivery->nrjType) {
          $nrjValue = $v[1];
          break;
        }
      if ($nrjValue) {
        if ($nrjValue['type'] == 'avia')
          $price = ceil($nrjValue['price']) * (1 + $delivery->insurance / 100);
        else {
          if (new DateTime < new DateTime('2015/01/01') && isset($_SERVER['SERVER_NAME']) && !(strpos($_SERVER['SERVER_NAME'], 'tornado') === FALSE)) {
            $price = 0;
          }
          else {
            $price = ceil($nrjValue['price']) * (1 + $delivery->insurance / 100);
          }
        }
      }
      else
        return false;
    }
    return true;
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
   * Get avalable types of delivery and delivery price
   * @param string $city City to which the goods are delivered
   * @param string $city_from City from which the goods are delivered
   * @param float $nrj_weight weight of goods
   * @param int $nrj_places The number of packages
   * @return array Array delivery data. For example:
   * ['rsp' => ['stat' => 'ok', 'values' => [
   *   ['type' => 'avia', 'price' => '650', 'term' => '2-3'],
   *   ['type' => 'auto', 'price' => '650', 'term' => '5-8'],
   *   ['type' => 'rw', 'price' => '650', 'term' => '3-5'],
   * ]]]
   * Where 'type' is type of delivery, 'price' is delivery price, 'term' is term of delivery
   */
  private static function getNrjDeliveries($city, $city_from, $nrj_weight, $nrj_places) {
    Yii::import('application.modules.delivery.models.NrjLocation');
    $pref = '^';
    $suff = '($|\\(|\\*|\\,|\\ )';
    $location = NrjLocation::model()->find('LOWER(name) REGEXP :name', array(
      ':name' => $pref . mb_strtolower(quotemeta(trim($city)), 'UTF-8') . $suff));
    $location_from = NrjLocation::model()->find('LOWER(name) REGEXP :name', array(
      ':name' => $pref . mb_strtolower(quotemeta(trim($city_from)), 'UTF-8') . $suff));
    if ($location && $location != $location_from) {
      $nrj_ch = curl_init("http://api.nrg-tk.ru/api/rest/?method=nrg.calculate&from=$location_from->id&to=$location->id&weight=$nrj_weight&volume=0&place=$nrj_places");
      curl_setopt($nrj_ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($nrj_ch, CURLOPT_HEADER, FALSE);
      curl_setopt($nrj_ch, CURLOPT_CONNECTTIMEOUT, 30);
      $nrj_get = curl_exec($nrj_ch);
      curl_close($nrj_ch);
      $nrj_deliveries = json_decode($nrj_get, TRUE);
      return $nrj_deliveries;
    }
    return NULL;
  }

  /**
   * Check if there are items which sizes to big for the type of delivery. 
   * If not then plases them into boxes.
   * @param array $items quantity and sizes of items
   * @param array $volumes Tha amount of space for product placement
   * @param Delivery $delivery Type of delivery
   * @return array Data of parcels. 
   * 
   * Example when there are ovesize items:
   * 
   * ['result' => false, 'oversize_items' => [11,35,48]]
   * 
   * Example when all items can be placed into the boxes:
   * 
   * <samp>
   * ['result' => true, 'parcels' => [
   * ['items' => [], 'length' => 55, 'width' => 42, 'height' => 25, weight => 8.33]
   * ]]
   * </samp>
   */
  private static function checkSizes(array $items, Delivery &$delivery) {

    $volume = array($delivery->length, $delivery->width, $delivery->height, 0, 0, 0);

    //check if there are items that oversize for size method 0 or overweight for size method 1
    $oversize_items = array();
    switch ($delivery->size_method_id) {
      case Delivery::SIZE_LENGTH_CIRCLE_SUMM:
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
      case Delivery::SIZE_LENGTH_WIDTH_HEIGHT:
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
      case Delivery::SIZE_LENGTH_CIRCLE_SUMM:
        while (count($items) > 0) {
          $parcels['parcels'][] = array('weight' => self::makeParcel($items, $volume, $delivery));
        }
        $parcels['result'] = true;
        break;
      case Delivery::SIZE_LENGTH_WIDTH_HEIGHT:
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

  /**
   * Collect items which oversise for all type of deliveries
   * @param array $parcels array of parsels data
   * @param array $list_oversize list of oversize items
   */
  private static function collectOversizeItems($parcels, &$list_oversize) {
    if (isset($parcels['oversize_items']))
      if ($list_oversize)
        $list_oversize = array_intersect($parcels['oversize_items'], $list_oversize);
      else
        $list_oversize = $parcels['oversize_items'];
  }

  /**
   * Get currency
   * @param string $items array of products data
   * @param string $currencyCode 
   * @return Currency currency
   */
  private static function getCurrency($items, $currencyCode) {
    if (\Yii::app()->params['mcurrency'])
      if (current($items) instanceof \Cart)
        $currency = Currency::model()->findByCountry(ProfileController::getProfile()->price_country);
      else
        $currency = Currency::model()->findByPk($currencyCode);
    else
      $currency = Currency::model()->findByCountry(Yii::app()->params['country']);
    return $currency;
  }

  /**
   * Convert price to the order currency
   * @param string $deliveryCurrencyCode
   * @param string $currencyCode
   * @param float $price summa to converting
   * @return boolean return false if currency rate is not found
   */
  private static function convertCurrency($deliveryCurrencyCode, $currencyCode, &$price) {
    if ($deliveryCurrencyCode != $currencyCode) {
      $curency_rate = CurrencyRate::model()->getRate($deliveryCurrencyCode, $currencyCode)->find();
      /* @var $curency_rate CurrencyRate */
      if ($curency_rate)
        $price = round($price * $curency_rate->rate * $curency_rate->to_quantity / $curency_rate->from_quantity);
      else {
        $curency_rate = CurrencyRate::model()->getRate($currencyCode, $deliveryCurrencyCode)->find();
        if ($curency_rate)
          $price = round($price * $curency_rate->from_quantity / $curency_rate->rate / $curency_rate->to_quantity);
        else
          return false;
      }
    }
    else
      $price = round($price);
    return true;
  }

  private static function makeParcel(&$items, $volume, &$delivery) {

    $data = array(
      'items' => &$items,
      'length' => 0,
      'width' => 0,
      'height' => 0,
      'weight' => 0,
    );
    set_time_limit(180);
    self::placeItems($data, $volume, $delivery);
    return $data['weight'];
  }

  private static function placeItems(array &$data, array $volume, Delivery &$delivery) {
    static $orientations = array(
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
            case Delivery::SIZE_LENGTH_CIRCLE_SUMM:
              $size_summ = $length + ($width + $height) * 2;
              if ($size_summ > $delivery->size_summ)
                continue 2;
              break;
            case Delivery::SIZE_LENGTH_WIDTH_HEIGHT:
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
            if (!($v[0] > 0 && $v[1] > 0 && $v[2] > 0))
              continue;
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

}

/**
 * Parcel object
 * 
 * @property boolean $result True if all items can be placed into the boxes
 * @property array $items Array of the products
 * @property float $lenght Length of the parcel
 * @property float $width Width of the parcel
 * @property float $height Height of the parcel
 * @property float $weight Weightof the parcel
 */
class Parcel {

  public $result, $items, $length, $width, $height, $weight;

}