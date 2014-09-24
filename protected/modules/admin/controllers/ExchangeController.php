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
   * @return str
   * @soap
   */
  public function setProduct($p, $hash) {

    try {
      $resultDOM = new DOMDocument;
      $resultDOM->encoding = 'UTF-8';
      $resultRootEl = $resultDOM->createElement('errors');
      $resultRootNode = $resultDOM->appendChild($resultRootEl);

      $xml = new SimpleXMLElement($p);
      if (!$xml) {
        foreach (libxml_get_errors() as $error) {
          $resultRootNode->appendChild($resultDOM->createElement('error', $error->message));
          Yii::trace($error->message, '1c_exchange');
        }
        return $resultDOM->saveXML();
      }
      if (strtoupper(md5(((string) $xml->product[0]->code) . self::PASS)) != $hash) {
        $resultRootNode->appendChild($resultDOM->createElement('error', 'Token is invalid'));
        return $resultDOM->saveXML();
      }

      Yii::import('application.modules.catalog.models.Product');
      Yii::import('application.modules.catalog.models.ProductPrice');
      Yii::import('application.modules.catalog.models.ProductCategory');
      Yii::import('application.modules.catalog.models.Category');
      Yii::import('application.modules.catalog.models.Brand');
      Yii::import('application.modules.catalog.models.Price');
      foreach ($xml->product as $item) {
        $code = (string) $item->code;
        $article = isset($item->article) ? (string) $item->article : '';
        $name = isset($item->name) ? (string) $item->name : '';
        $remainder = isset($item->remainder) ? (int) $item->remainder : FALSE;
        Yii::trace('product ' . $item->name, '1c_exchange');
        $model = Product::model()->findByAttributes(array('code' => $code));
        if (!$model && $article) {
          $model = Product::model()->findByAttributes(array('article' => $article));
        }
        /* @var $model Product */
        if ($model) {
          if (!$name && !$remainder) {
            $model->delete();
            continue;
          }
        }
        else {
          $model = new Product;
          $model->show_me = TRUE;
        }
        $model->code = $code;
        if ($article)
          $model->article = $article;
        if ($name)
          $model->name = $name;

        if (isset($item->brand)) {
          $brand = Brand::model()->findByAttributes(array('code' => (string) $item->brand));
          /* @var $brand Brand */
          if ($brand) {
            $model->brand_id = (int) $brand->id;
          }
        }

        if ($remainder !== FALSE)
          $model->remainder = (int) $item->remainder;
        if (isset($item->price))
          $model->price = (float) $item->price;
        if (isset($item->weight))
          $model->weight = (float) $item->weight;
        if (isset($item->length))
          $model->length = (float) $item->length;
        $model->width = (float) $item->width;
        if (isset($item->height))
          $model->height = (float) $item->height;
        $this->validate($code . ': ' . $name, $model, $resultDOM, $resultRootNode);

        if (!$model->save()) {
          Yii::trace('Product fail save', '1c_exchange');
        }

        if (isset($item->category)) {
          $category = Category::model()->findByAttributes(array('code' => (string) $item->category));
          /* @var $category Category */
          if ($category) {
//            Yii::trace('find category', '1c_exchange');
            ProductCategory::model()->deleteAllByAttributes(array('product_id' => $model->id));
            $productCategory = new ProductCategory;
            $productCategory->product_id = $model->id;
            $productCategory->category_id = $category->id;
            $this->validate($code . ': ' . $name, $productCategory, $resultDOM, $resultRootNode);
            if (!$productCategory->save()) {
              Yii::trace('Category fail save', '1c_exchange');
            }
          }
        }

        if (isset($item->images)) {
          if (isset($item->images->image[0])) {
            $imgPath = '/images/' . Yii::app()->params['img_storage'] . '/product/';
            $rootPath = Yii::getPathOfAlias('webroot');
            $img = base64_decode($item->images->image[0]->original);
            $imagick = new Imagick;
            $imagick->readimageblob($img);
            $ext = '.' . strtolower($imagick->getimageformat());
            $imagick->destroy();
            $fileName = $model->id . $ext;
            $file = fopen($rootPath . $imgPath . $fileName, 'w+');
            fwrite($file, $img);
            unset($img);
            fclose($file);
            $model->img = $imgPath . $fileName;

            if (isset($item->images->image[0]->small)) {
              $imgSmall = base64_decode($item->images->image[0]->small);
              $imagickSmall = new Imagick;
              $imagickSmall->readimageblob($imgSmall);
              $extSmall = '.' . strtolower($imagickSmall->getimageformat());
              $imagickSmall->destroy();
              $fileNameSmall = $model->id . 's' . $extSmall;
              $fileSmall = fopen($rootPath . $imgPath . $fileNameSmall, 'w+');
              fwrite($fileSmall, $imgSmall);
              unset($imgSmall);
              fclose($fileSmall);
              $model->small_img = $imgPath . $fileNameSmall;
            }
            else
              $model->createThumbnail();

            $this->validate($code . ': ' . $name, $model, $resultDOM, $resultRootNode, array('img', 'small_img'));
            if (!$model->update(array('img', 'small_img'))) {
              Yii::trace('Image fail save', '1c_exchange');
            }
          }
        }

        if (isset($item->prices)) {
          ProductPrice::model()->deleteAllByAttributes(array('product_id' => $model->id));
          foreach ($item->prices->price as $price) {
            $price_model = Price::model()->findByAttributes(array('code' => $price->code));
            if ($price_model) {
              Yii::trace('find price', '1c_exchange');
              $product_price = new ProductPrice;
              $product_price->product_id = $model->id;
              $product_price->price_id = $price_model->id;
              $product_price->price = (float) $price->value;
              $this->validate($code . ': ' . $name, $product_price, $resultDOM, $resultRootNode);
              if (!$product_price->save()) {
                Yii::trace('Price fail save', '1c_exchange');
              }
            }
          }
        }
      }
    } catch (Exception $exc) {
      Yii::trace($exc->getMessage() . $exc->getTraceAsString(), '1c_exchange');
    }

    return $resultDOM->saveXML();
  }

  private function validate($name, CActiveRecord $model, DOMDocument &$DOMdoc, DOMNode &$node, $attr = NULL) {
    if (!$model->validate($attr)) {
      $errors = $model->getErrors();
      foreach ($errors as $errs) {
        foreach ($errs as $msg) {
          $node->appendChild($DOMdoc->createElement('error', $name . ': ' . $msg));
          Yii::trace('error: ' . $name . ' ' . $msg, '1c_exchange');
        }
      }
    }
  }

  /**
   * 
   * @param string $p
   * @param str $hash 
   * @return str
   * @soap
   */
  public function setPrice($p, $hash) {
    try {
      $resultDOM = new DOMDocument;
      $resultDOM->encoding = 'UTF-8';
      $resultRootEl = $resultDOM->createElement('errors');
      $resultRootNode = $resultDOM->appendChild($resultRootEl);

      $xml = new SimpleXMLElement($p);
      if (!$xml) {
        foreach (libxml_get_errors() as $error) {
          $resultRootNode->appendChild($resultDOM->createElement('error', $error->message));
          Yii::trace($error->message, 'exchange');
        }
        return $resultDOM->saveXML();
      }
      if (strtoupper(md5(((string) $xml->price[0]->code) . self::PASS)) != $hash) {
        $resultRootNode->appendChild($resultDOM->createElement('error', 'Token is invalid'));
        return $resultDOM->saveXML();
      }

      Yii::import('application.modules.catalog.models.Price');

      foreach ($xml->prices as $item) {
        $code = (string) $item->code;
        $name = (string) $item->name;
        $model = Price::model()->findByAttributes(array('code' => $code));
        if (!$model) {
          $model = Price::model()->findByAttributes(array('name' => (string) $name));
        }
        /* @var $model Price */
        if ($model) {
          if (!$name) {
            $model->delete();
            continue;
          }
        }
        else {
          $model = new Price;
        }
        $model->name = $name;
        $model->code = $code;
        $model->summ = (float) $item->summ;
        $this->validate($model->name, $model, $resultDOM, $node);
        $model->save();
      }
    } catch (Exception $exc) {
      Yii::trace($exc->getMessage() . $exc->getTraceAsString(), '1c_exchange');
    }
    return $resultDOM->saveXML();
  }

  /**
   * 
   * @param string $b
   * @param str $hash 
   * @return str
   * @soap
   */
  public function setBrand($b, $hash) {
    try {
//      $brand = json_decode($b);
//      if (strtoupper(md5($brand[0][0] . self::PASS)) != $hash)
//        return FALSE;
      $resultDOM = new DOMDocument;
      $resultDOM->encoding = 'UTF-8';
      $resultRootEl = $resultDOM->createElement('errors');
      $resultRootNode = $resultDOM->appendChild($resultRootEl);

      $xml = new SimpleXMLElement($b);
      if (!$xml) {
        foreach (libxml_get_errors() as $error) {
          $resultRootNode->appendChild($resultDOM->createElement('error', $error->message));
          Yii::trace($error->message, '1c_exchange');
        }
        $reult = $resultDOM->saveXML();
        return $reult;
      }
      if (strtoupper(md5(((string) $xml->brand[0]->code) . self::PASS)) != $hash) {
        $resultRootNode->appendChild($resultDOM->createElement('error', 'Token is invalid'));
        return $resultDOM->saveXML();
      }

      Yii::import('application.modules.catalog.models.Brand');
      foreach ($xml->brand as $item) {
        $code = (string) $item->code;
        $name = (string) $item->name;
        $model = Brand::model()->findByAttributes(array('code' => $code));
        if (!$model) {
          $model = Brand::model()->findByAttributes(array('name' => $name));
        }
        /* @var $model Brand */
        if ($model) {
          if (!$name) {
            $model->delete();
            continue;
          }
        }
        else {
          $model = new Price;
        }
        $model->name = $name;
        $model->code = $code;
        $this->validate($name, $model, $resultDOM, $resultRootNode);
        if (!$model->save()) {
          Yii::trace('Brand save fail', '1c_exchange');
        }
      }
    } catch (Exception $exc) {
      Yii::trace($exc->getMessage() . $exc->getTraceAsString(), 'exchange');
    }
    return $resultDOM->saveXML();
  }

  /**
   * 
   * @param str $c
   * @param str $hash 
   * @return str
   * @soap
   */
  public function setCategory($c, $hash) {
    Yii::import('application.modules.catalog.models.Category');
    try {
      $resultDOM = new DOMDocument;
      $resultDOM->encoding = 'UTF-8';
      $resultRootEl = $resultDOM->createElement('errors');
      $resultRootNode = $resultDOM->appendChild($resultRootEl);

      $xml = new SimpleXMLElement($c);
      if (!$xml) {
        foreach (libxml_get_errors() as $error) {
          $resultRootNode->appendChild($resultDOM->createElement('error', $error->message));
          Yii::trace($error->message, '1c_exchange');
        }
        $reult = $resultDOM->saveXML();
        return $reult;
      }
      if (strtoupper(md5(((string) $xml->category[0]->code) . self::PASS)) != $hash) {
        $resultRootNode->appendChild($resultDOM->createElement('error', 'Token is invalid'));
        return $resultDOM->saveXML();
      }

      $category = array();
      foreach ($xml->category as $cat) {
        $category[] = array((string) $cat->code, (string) $cat->parent, (string) $cat->name);
      }

      while ($item = each($category)) {
        $this->saveCategory(array($item['key'] => $item['value']), $category, $resultDOM, $resultRootNode);
      }
    } catch (Exception $e) {
      Yii::trace($e->getMessage() . $e->getTraceAsString(), '1c_exchange');
    }
    return $resultDOM->saveXML();
  }

  private function saveCategory($item, &$category, DOMDocument &$DOMdoc, DOMNode &$node) {
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
    if ($this->saveParent($model, $item[$key], $category, $DOMdoc, $node)) {
      $this->validate($model->name, $model, $DOMdoc, $node);
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

  private function saveParent(Category $model, $item, &$category, DOMDocument &$DOMdoc, DOMNode &$node) {
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
          $this->validate($model->name, $model, $DOMdoc, $node);
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
        Yii::trace($error->message, '1c_exchange');
      }
      return FALSE;
    }
//    Yii::trace('hash: ' . $hash, 'exchange');
//    Yii::trace('pass: ' . $xml->id . $xml->date . self::PASS, '1c_exchange');
//    Yii::trace('check: ' . strtoupper(md5($xml->id . $xml->date . self::PASS)), '1c_exchange');
    if (strtoupper(md5($xml->id . $xml->date . self::PASS)) != $hash)
      return FALSE;
    Yii::trace('password', '1c_exchange');
    Yii::import('application.models.Order');
    Yii::import('application.models.OrderProduct');
    Yii::import('application.models.CustomerProfile');
    Yii::import('application.modules.admin.models.Mail');
    Yii::import('application.modules.admin.models.MailOrder');
    Yii::import('application.modules.catalog.models.Price');

    $order = Order::model()->findByPk((int) $xml->id);
    Yii::trace('Order ' . is_null($order), 'exchange');
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

      $price_type = Price::getPrice($xml->products->product, $order->profile->user_id);

      $product_ids = array();
      foreach ($xml->products->product as $p) {
        $product = Product::model()->findByAttributes(array('code' => (string) $p->code));
        /* @var $product Product */
        if ($product) {
          $product_ids[] = $product->id;
          $orderProduct = OrderProduct::model()->findByPk(array('order_id' => $order->id, 'product_id' => $product->id));
          /* @var $orderProduct OrderProduct */
          if (!$orderProduct) {
            $orderProduct = new OrderProduct;
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $product->id;
            $orderProduct->quantity = (string) $p->quantity;
            $orderProduct->price = (string) $p->price;
            $price = $product->getPrice($price_type, $order->currency_code);
            $orderProduct->discount = $price > $orderProduct->price ? $price - $orderProduct->price : 0;
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

      $p_ids = implode(',', $product_ids);
      OrderProduct::model()->deleteAllByAttributes(array('order_id' => $order->id), 'product_id NOT IN (:p_ids)', array(':p_ids' => $p_ids));

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
