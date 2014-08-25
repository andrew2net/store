<?php

/**
 * Description of ExchangeController
 *
 */
Yii::import('application.modules.catalog.models.Product');

class ExchangeController extends CController {

  const PASS = 'GlsDr5fgt4edaPe2eRtv';

  public function actions() {
    return array(
      'quote' => array(
        'class' => 'WebServiceAction',
      )
    );
  }

  /**
   * @param str $p 
   * @param str $hash 
   * @return bool
   * @soap
   */
  public function setProduct($p, $hash) {

    try {
//      Yii::trace('try ' . substr($p, 0, 255), 'exchange');
      $xml = new SimpleXMLElement($p);
      if (!$xml) {
        foreach (libxml_get_errors() as $error) {
          Yii::trace($error->message, 'exchange');
        }
        return FALSE;
      }
      Yii::trace('decode ' . json_last_error(), 'exchange');
      if (strtoupper(md5($xml->product[0]->code . self::PASS)) != $hash)
        return FALSE;

      Yii::import('application.modules.catalog.models.Product');
      Yii::import('application.modules.catalog.models.ProductPrice');
      Yii::import('application.modules.catalog.models.ProductCategory');
      Yii::import('application.modules.catalog.models.Category');
      Yii::import('application.modules.catalog.models.Brand');
      Yii::import('application.modules.catalog.models.Price');
//      Yii::trace('import', 'exchange');
      foreach ($xml->product as $item) {
        $model = Product::model()->findByAttributes(array('code' => $item->code));
        if (!$model) {
          $model = Product::model()->findByAttributes(array('article' => $item->article));
        }
        /* @var $model Product */
        if ($model) {
          if (!$item->name)
            return $model->delete();
        }else {
          $model = new Product;
          $model->show_me = TRUE;
        }
//        Yii::trace('model ' . $model->isNewRecord, 'exchange');
        $model->code = $item->code;
        $model->article = (string) $item->article;
        $model->name = (string) $item->name;

        $brand = Brand::model()->findByAttributes(array('code' => $item->brand));
        /* @var $brand Brand */
        if ($brand)
          $model->brand_id = (int) $brand->id;
//        Yii::trace('brand', 'exchange');

        $model->remainder = (int) $item->remainder;
        $model->price = (float) $item->price;
        $model->weight = (float) $item->weight;
        $model->length = (float) $item->length;
        $model->width = (float) $item->width;
        $model->height = (float) $item->height;

        if (!$model->save())
          return FALSE;
//        Yii::trace('save', 'exchange');

        $category = Category::model()->findByAttributes(array('code' => $item->category));
        /* @var $category Category */
        if ($category) {
          ProductCategory::model()->deleteAllByAttributes(array('product_id' => $model->id));
          $productCategory = new ProductCategory;
          $productCategory->product_id = $model->id;
          $productCategory->category_id = $category->id;
          $productCategory->save();
        }
//        Yii::trace('category', 'exchange');

        if (isset($item->image)) {
          $img_path = '/images/' . Yii::app()->params['img_storage'] . '/product/';
          $img = base64_decode($item->image);
          $imagick = new Imagick;
          $imagick->readimageblob($img);
          $ext = '.' . strtolower($imagick->getimageformat());
          $imagick->destroy();
          $file = fopen(Yii::getPathOfAlias('webroot') . $img_path . $model->id . $ext, 'w+');
          fwrite($file, $img);
          fclose($file);
          $model->img = $img_path . $model->id . $ext;
          $model->createThumbnail();
          $model->update(array('img', 'small_img'));
        }
//        Yii::trace('image', 'exchange');

        ProductPrice::model()->deleteAllByAttributes(array('product_id' => $model->id));
        foreach ($item->prices->price as $price) {
          $price_model = Price::model()->findByAttributes(array('code' => $price->code));
          if ($price_model) {
            $product_price = new ProductPrice;
            $product_price->product_id = $model->id;
            $product_price->price_id = $price_model->id;
            $product_price->price = (float) $price->value;
            $product_price->save();
          }
        }
//        Yii::trace('price', 'exchange');
        return TRUE;
      }
    } catch (Exception $exc) {
      Yii::trace($exc->getMessage() . $exc->getTraceAsString(), 'exchange');
    }

    return FALSE;
  }

  /**
   * 
   * @param string $p
   * @param str $hash 
   * @return boolean
   * @soap
   */
  public function setPrice($p, $hash) {
    Yii::import('application.modules.catalog.models.Price');
    try {
      $price = json_decode($p);
      if (strtoupper(md5($price[0][0] . self::PASS)) != $hash)
        return FALSE;
      $valid = TRUE;
      foreach ($price as $item) {
        $model = Price::model()->findByAttributes(array('code' => $item[0]));
        if (!$model) {
          $model = Price::model()->findByAttributes(array('name' => $item[2]));
        }
        /* @var $model Price */
        if ($model) {
          if (!$item[2]) {
            return $model->delete();
          }
        }
        else {
          $model = new Price;
        }
        $model->name = $item[2];
        $model->code = $item[0];
        $model->summ = $item[1];
        $valid = $valid && $model->save();
      }
      return $valid;
    } catch (Exception $exc) {
      Yii::trace($exc->getMessage() . $exc->getTraceAsString(), 'exchange');
      return false;
    }
    return FALSE;
  }

  /**
   * 
   * @param string $b
   * @param str $hash 
   * @return boolean
   * @soap
   */
  public function setBrand($b, $hash) {
    try {
      $brand = json_decode($b);
      if (strtoupper(md5($brand[0][0] . self::PASS)) != $hash)
        return FALSE;
      Yii::import('application.modules.catalog.models.Brand');
      $valid = TRUE;
      foreach ($brand as $item) {
        $model = Brand::model()->findByAttributes(array('code' => $item[0]));
        if (!$model) {
          $model = Brand::model()->findByAttributes(array('name' => $item[1]));
        }
        /* @var $model Brand */
        if ($model) {
          if (!$item[1]) {
            return $model->delete();
          }
        }
        else {
          $model = new Price;
        }
        $model->name = $item[1];
        $model->code = $item[0];
        $valid = $valid && $model->save();
      }
      return $valid;
    } catch (Exception $exc) {
      Yii::trace($exc->getMessage() . $exc->getTraceAsString(), 'exchange');
      return false;
    }
    return FALSE;
  }

  /**
   * 
   * @param str $c
   * @param str $hash 
   * @return bool
   * @soap
   */
  public function setCategory($c, $hash) {
    Yii::import('application.modules.catalog.models.Category');
    try {
      $category = json_decode($c);
      if (strtoupper(md5($category[0][0] . self::PASS)) != $hash)
        return FALSE;
      $valid = TRUE;
      while ($item = each($category)) {
//        Yii::trace($item['value'][2], 'exchange');
        $result = $this->saveCategory(array($item['key'] => $item['value']), $category);
        if (!$result) {
          $valid = FALSE;
        }
      }
      return $valid;
    } catch (Exception $e) {
      Yii::trace($e->getMessage() . $e->getTraceAsString(), 'exchange');
      return FALSE;
    }
    return FALSE;
  }

  private function saveCategory($item, &$category) {
    $key = key($item);
//    Yii::trace($item[$key][2], 'exchange');
    $category = array_diff_key($category, $item);
    $model = Category::model()->findByAttributes(array('code' => $item[$key][0]));
    if (!$model) {
      $model = Category::model()->findByAttributes(array('name' => $item[$key][2]));
    }
    if ($model) {
      if (!$item[$key][2]) {
        return $model->deleteNode();
      }
    }
    else {
      $model = new Category;
    }
    $model->name = $item[$key][2];
    $model->code = $item[$key][0];
    if ($this->saveParent($model, $item[$key], $category)) {
      if ($model->saveNode()) {
        return $model;
      }
    }
    return TRUE;
  }

  private function category_parent_search($code, $category) {
    foreach ($category as $key => $value) {
      if ($value[0] === $code) {
        return array($key => $value);
      }
    }
    return FALSE;
  }

  private function saveParent(Category $model, $item, &$category) {
    if ($item[1]) {
      $parent_key = $this->category_parent_search($item[1], $category);
      if ($parent_key) {
        $result = $this->saveCategory($parent_key, $category);
      }
      else {
        $result = Category::model()->findByAttributes(array('code' => $item[1]));
      }
      if ($result instanceof Category) {
        if ($model->isNewRecord) {
          return $result->append($model);
//          return $model;
        }
        $parent = $model->getParent();
        if (!$parent || $result->code != $parent->code) {
          return $model->moveAsLast($result);
//          return $model;
        }
      }
      return $result;
    }
    return TRUE;
  }

  /**
   * @param str $hash 
   * @return str xml data or empty if there is no any new orders
   * @soap
   */
  public function getOrder($hash) {
    Yii::import('application.models.Order');
    Yii::import('application.models.OrderProduct');
    Yii::import('application.models.CustomerProfile');
    Yii::import('application.modules.user.models.User');
    Yii::import('application.modules.user.models.Profile');
    Yii::import('application.modules.catalog.models.Product');
    Yii::import('application.modules.delivery.models.Delivery');
    Yii::import('application.modules.payments.models.Payment');
    $order = Order::model()->find(array(
      'alias' => 'order',
      'with' => array('profile' => array('alias' => 't', 'with' => 'user')),
      'condition' => 'exchange=1',
    ));
    /* @var $order Order */
    if ($order) {
      $sign = md5($order->time . $order->fio . $order->id);
      if (strtoupper(md5($sign . self::PASS)) != $hash)
        return $sign;
    }else {
      return '';
    }
    $domDoc = new DOMDocument;
    $domDoc->encoding = 'UTF-8';
    $orderEl = $domDoc->createElement('order');
    $orderNode = $domDoc->appendChild($orderEl);

    $orderNode->appendChild($domDoc->createElement('id', $order->id));
    $orderNode->appendChild($domDoc->createElement('date', Yii::app()->dateFormatter->format('yyyyMMddHHmmss', $order->time)));
    $orderNode->appendChild($domDoc->createElement('payment', $order->payment->name));
    $orderNode->appendChild($domDoc->createElement('description', $order->description));

    $deliveryEl = $domDoc->createElement('delivery');
    $deliveryNode = $orderNode->appendChild($deliveryEl);
    $deliveryName = $order->delivery->name;
    switch ($order->delivery->zone_type_id) {
      case 3:
        $deliveryName .= ' (' . $order->delivery->transportType . ')';
        break;
      case 4:
        $deliveryName .= ' (' . $order->customer_delivery . ')';
    }
    $deliveryNode->appendChild($domDoc->createElement('name', $deliveryName));
    $deliveryNode->appendChild($domDoc->createElement('price', $order->delivery_summ));

    $customerEl = $domDoc->createElement('customer');
    $customerNode = $orderNode->appendChild($customerEl);
    $customerNode->appendChild($domDoc->createElement('name', $order->fio));
    $customerNode->appendChild($domDoc->createElement('inn', $order->profile->user->profile->inn));
    $customerNode->appendChild($domDoc->createElement('email', $order->email));
    $customerNode->appendChild($domDoc->createElement('phone', $order->phone));
    $customerNode->appendChild($domDoc->createElement('city', $order->city));
    $customerNode->appendChild($domDoc->createElement('address', $order->address));

    $productsEl = $domDoc->createElement('products');
    $productsNode = $orderNode->appendChild($productsEl);
    foreach ($order->orderProducts as $item) {
      $productEl = $domDoc->createElement('product');
      $productNode = $productsNode->appendChild($productEl);
      $productNode->appendChild($domDoc->createElement('code', $item->product->code));
      $productNode->appendChild($domDoc->createElement('quantity', $item->quantity));
      $productNode->appendChild($domDoc->createElement('price', $item->price));
    }

    $xml = $domDoc->saveXML();

    if ($order->status_id == Yii::app()->params['order']['new_status'] &&
        $order->status_id != Yii::app()->params['order']['process_status']) {
      $order->status_id = Yii::app()->params['order']['process_status'];
    }
    $order->exchange = 0;
    $order->save();

    return $xml;
  }

  /**
   * 
   * @param str $o
   * @param str $hash
   * @return bool
   * @soap
   */
  public function setOrder($o, $hash) {
    $xml = new SimpleXMLElement($o);
    if (!$xml) {
      foreach (libxml_get_errors() as $error) {
        Yii::trace($error->message, 'exchange');
      }
      return FALSE;
    }
    Yii::trace('hash: ' . $hash, 'exchange');
    Yii::trace('pass: ' . $xml->id . $xml->date . self::PASS, 'exchange');
    Yii::trace('check: ' . strtoupper(md5($xml->id . $xml->date . self::PASS)), 'exchange');
    if (strtoupper(md5($xml->id . $xml->date . self::PASS)) != $hash)
      return FALSE;
    Yii::trace('password', 'exchange');
    Yii::import('application.models.Order');
    Yii::import('application.models.OrderProduct');
    Yii::import('application.models.CustomerProfile');
    Yii::import('application.modules.admin.models.Mail');
    Yii::import('application.modules.admin.models.MailOrder');
    Yii::import('application.modules.catalog.models.Price');

    $order = Order::model()->findByPk((int) $xml->id);
    Yii::trace('Order '. is_null($order), 'exchange');
    /* @var $order Order */
    if (!$order)
      return FALSE;

    $old_status = $order->status_id;

    $tr = Yii::app()->db->beginTransaction();
    try {
      $order->time = (string) $xml->date;
      $order->status_id = (string) $xml->status;
      $order->description = (string) $xml->description;

      $order->save();

      $temp_table = 'temp_order_product_' . $order->id;
      $query = "DROP TABLE IF EXISTS {$temp_table};";
      $query .= "CREATE TEMPORARY TABLE {$temp_table} (product_id int(11) unsigned, quantity smallint(5) unsigned) TYPE=HEAP;";
      $product_ids = array();
      foreach ($xml->products->product as $p) {
        $product = Product::model()->findByAttributes(array('code' => (string) $p->code));
        if ($product) {
          $query .= "INSERT INTO {$temp_table} VALUES ({$product->id}, {$p->quantity});";
          $product_ids[] = $product->id;
        }
        else
          throw new Exception('Product not found. Product code: ' . $p->code);
      }
      Yii::app()->db->createCommand($query)->execute();
      $price_type = Price::getPrice($temp_table);
      Yii::app()->db->createCommand("DROP TABLE IF EXISTS {$temp_table};")->execute();

      $p_ids = implode(',', $product_ids);
      OrderProduct::model()->deleteAllByAttributes(array('order_id' => $order->id), 'product_id NOT IN (:p_ids)', array(':p_ids' => $p_ids));

      foreach ($xml->products->product as $p) {
        $product = Product::model()->findByAttributes(array('code' => (string) $p->code));
        /* @var $product Product */
        if ($product) {
          $orderProduct = OrderProduct::model()->findByPk(array('order_id' => $order->id, 'product_id' => $product->id));
          /* @var $orderProduct OrderProduct */
          if (!$orderProduct) {
            $orderProduct = new OrderProduct;
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $product->id;
            $orderProduct->quantity = (string) $p->quantity;
            $orderProduct->price = (string) $p->price;
            $price = $product->getPrice($price_type, $order->currency_code);
            $orderProduct->discount = $price - $orderProduct->price;
            $orderProduct->save();
          }
          else {
            $save = FALSE;
            if ($orderProduct->quantity != $p->quantity) {
              $orderProduct->quantity = (string) $p->quantity;
              $save = TRUE;
            }
            if ($orderProduct->price != $p->price) {
              $orderProduct->price = (string) $p->price;
              $price = $product->getPrice($price_type, $order->currency_code);
              $orderProduct->discount = $price - $orderProduct->price;
              $save = TRUE;
            }
            if ($save)
              $orderProduct->save();
          }
        }
        else
          throw new Exception('Product not found. Product code: ' . $p->code);
      }

      if ($old_status != $order->status_id) {
        $mail = new Mail;
        $mail->uid = $order->profile->user_id;
        $mail->type_id = 4;
        $mail->status_id = 1;
        if ($mail->save()) {
          $mailOrder = new MailOrder;
          $mailOrder->mail_id = $mail->id;
          $mailOrder->order_id = $xml->id;
          $mailOrder->save();
        }
      }

      $tr->commit();
    } catch (Exception $e) {
      $tr->rollback();
      Yii::trace($e->getMessage() . $e->getTraceAsString(), 'exchange');
      return FALSE;
    }

    return TRUE;
  }

}
