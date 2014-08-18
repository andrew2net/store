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
//      $product = json_decode($p);
      $xml = new SimpleXMLElement($p);
      if (!$xml) {
        foreach (libxml_get_errors() as $error) {
          Yii::trace($error->message, 'exchange');
        }
      }
      Yii::trace('decode ' . json_last_error(), 'exchange');
      if (strtoupper(md5($xml->product[0]->code . self::PASS)) != $hash)
        return FALSE;
//      Yii::trace('password', 'exchange');
//      if (!xml_parse($xml_parser, $p, TRUE)) {
//        Yii::trace(sprintf('Ошибка XML: %s на строке %d', xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser)), 'exchange');
//      }
//      xml_parser_free($xml_parser);

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

}
