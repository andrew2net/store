<?php

class ProductController extends Controller {

  /**
   * @return array action filters
   */
  public function filters() {
    return array(
      array('auth.filters.AuthFilter'), // perform access control for CRUD operations
      'postOnly + delete', // we only allow deletion via POST request
    );
  }

  /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   */
  public function actionCreate() {
    $model = new Product;
    $feature_values = self::featureValues();
    $prices = self::prices();

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if (isset($_POST['Product'])) {
      $this->saveProduct($model, $feature_values, $prices);
    }

    $this->render('create', array(
      'model' => $model,
      'feature' => $feature_values,
      'prices' => $prices,
    ));
  }

  /**
   * Updates a particular model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id the ID of the model to be updated
   */
  public function actionUpdate($id) {
    $model = $this->loadModel($id);
    $feature_values = self::featureValues($id);
    $prices = self::prices($id);

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if (isset($_POST['Product'])) {
      $this->saveProduct($model, $feature_values, $prices);
    }

    $this->render('update', array(
      'model' => $model,
      'feature' => $feature_values,
      'prices' => $prices,
    ));
  }

  private static function featureValues($id = 'n') {
    $sql = Yii::app()->db->createCommand()->select('category_id')->from('store_product_category')
            ->where('product_id=:id')->getText();
    return self::features($id, $sql);
  }

  private static function features($id, $in) {
    $condition = 'categoryFeatures.category_id IS NOT NULL';
    $feature_ids = implode(',', self::getFeatureIds());
    if ($feature_ids) {
      $condition .= " OR t.id IN ($feature_ids)";
    }

    if (!$in)
      $in = "'n'";
    $feature = Feature::model()->with(array(
          'categoryFeatures' => array(
            'on' => "categoryFeatures.category_id in ($in)",
            'params' => array(':id' => $id),
          ),
          'featureValues',
          'together' => TRUE,
        ))->findAll(array('condition' => $condition, 'order' => 'name'));

    $feature_values = array();
    foreach ($feature as $key => $item) {
      /* @var $item Feature */
      switch ($item->type_id) {
        case 0:
          $value = ProductFeature::model()->findByPk(array('feature_id' => $item->id, 'product_id' => $id));
          if (!$value)
            $value = new ProductFeature;
          break;
        case 1:
          $value = ProductFeatureValue::model()->with('values.feature')
              ->find('product_id=:product_id AND feature.id=:feature_id', array(
            ':product_id' => $id, ':feature_id' => $item->id));
          if (!$value)
            $value = new ProductFeatureValue;
          break;
        case 2:
          if ($id) {
            $value = ProductFeatureRange::model()->findByPk(array('product_id' => $id, 'feature_id' => $item->id));
            if (!$value)
              $value = new ProductFeatureRange;
          }
          else
            $value = new ProductFeatureRange;
      }
      $feature_values[$item->id] = array('feature' => $item, 'value' => $value);
    }
    return $feature_values;
  }

  private static function getFeatureIds() {
    $features = array();
    if (isset($_POST['ProductFeature']))
      foreach ($_POST['ProductFeature'] as $key => $value)
        if (!empty($value['value']))
          $features[] = $key;
    if (isset($_POST['ProductFeatureRange']))
      foreach ($_POST['ProductFeatureRange'] as $key => $value)
        if (!(empty($value['from']) && empty($value['to'])))
          $features[] = $key;
    if (isset($_POST['ProductFeatureValue']))
      foreach ($_POST['ProductFeatureValue'] as $key => $value)
        if (!empty($value['value_id']))
          $features[] = $key;
    return $features;
  }

  private static function prices($id = 'n') {
    $prices = Price::model()->with(array('prices' => array(
            'on' => 'product_id=:id',
            'params' => array(':id' => $id)
      )))->findAll();
    /* @var $prices Price[] */

    $product_prices = array();
    foreach ($prices as $price) {
      $product_prices[$price->id]['name'] = $price->name;
      if ($price->prices)
        $product_prices[$price->id]['price'] = $price->prices[0];
      else
        $product_prices[$price->id]['price'] = new ProductPrice;
    }
    return $product_prices;
  }

  private function saveProduct(Product &$model, &$feature_values, &$prices) {

    $model->attributes = $_POST['Product'];
    $valid = $model->validate();

    if (isset($_POST['ProductFeature'])) {
      $feature_ids = self::getFeatureIds();
      foreach ($feature_ids as $key) {
        if ($feature_values[$key]['value'] instanceof ProductFeature && !empty($_POST['ProductFeature'][$key]['value'])) {
          $feature_values[$key]['value']->feature_id = $feature_values[$key]['feature']->id;
          $feature_values[$key]['value']->value = $_POST['ProductFeature'][$key]['value'];
          $valid = $feature_values[$key]['value']->validate() && $valid;
        }
        elseif ($feature_values[$key]['value'] instanceof ProductFeatureValue && !empty($_POST['ProductFeatureValue'][$key]['value_id'])) {
          $feature_values[$key]['value']->value_id = $_POST['ProductFeatureValue'][$key]['value_id'];
          $valid = $feature_values[$key]['value']->validate() && $valid;
        }
        elseif ($feature_values[$key]['value'] instanceof ProductFeatureRange &&
            !(empty($_POST['ProductFeatureRange'][$key]['from']) && empty($_POST['ProductFeatureRange'][$key]['to']))) {
          $feature_values[$key]['value']->feature_id = $feature_values[$key]['feature']->id;
          $feature_values[$key]['value']->from = $_POST['ProductFeatureRange'][$key]['from'];
          $feature_values[$key]['value']->to = $_POST['ProductFeatureRange'][$key]['to'];
          $valid = $feature_values[$key]['value']->validate() && $valid;
        }
      }
    }
    $price_to_del = array();
    if (isset($_POST['ProductPrice'])) {
      /* @var $prices ProductPrice[] */
      foreach ($prices as $key => $value) {
        if (isset($_POST['ProductPrice'][$key]['price']) && $_POST['ProductPrice'][$key]['price']) {
          $prices[$key]['price']->price = $_POST['ProductPrice'][$key]['price'];
          $valid = $prices[$key]['price']->validate(array('price')) && $valid;
        }
        else {
          $price_to_del[] = $key;
          $prices[$key]['price']->price = '';
        }
      }
    }
    if ($valid) {
      $tr = Yii::app()->db->beginTransaction();
      try {
        if ($model->save()) {
          //delete categories
          $category_ids = isset($_POST['Categories']) ? implode(',', array_keys($_POST['Categories'])) : '';
          $command = Yii::app()->db->createCommand();
          $condition = 'product_id=:id';
          $params = array(':id' => $model->id);
          if ($category_ids)
            $condition .= " AND category_id NOT IN ($category_ids)";

          $command->delete('store_product_category', $condition, $params);
          //add new categories
          if (isset($_POST['Categories']))
            foreach ($_POST['Categories'] as $key => $value) {
              $productCategory = ProductCategory::model()->findByAttributes(array('product_id' => $model->id, 'category_id' => $key));
              if (!$productCategory) {
                $productCategory = new ProductCategory;
                $productCategory->product_id = $model->id;
                $productCategory->category_id = $key;
                $productCategory->save();
              }
            }

          //delete features
          $condition = 'product_id=:id';
          $condition_val = $condition;
          if (isset($feature_ids)) {
            if ($feature_ids) {
              $command->reset();
              $f_ids = implode(',', $feature_ids);
              $value_ids = $command->select('id')
                  ->from('store_feature_value')->where("feature_id IN ($f_ids)")
                  ->getText();
              $condition_val .= " AND value_id NOT IN ($value_ids)";

              $condition .= " AND feature_id NOT IN ($f_ids)";
            }
            //add and update features
            foreach ($feature_ids as $key) {
              if ($feature_values[$key]['value'] instanceof ProductFeature && !empty($_POST['ProductFeature'][$key]['value'])) {
                $feature_values[$key]['value']->product_id = $model->id;
                $feature_values[$key]['value']->save();
              }
              elseif ($feature_values[$key]['value'] instanceof ProductFeatureValue && !empty($_POST['ProductFeatureValue'][$key]['value_id'])) {
                $feature_values[$key]['value']->product_id = $model->id;
                $feature_values[$key]['value']->save();
              }
              elseif ($feature_values[$key]['value'] instanceof ProductFeatureRange &&
                  !(empty($_POST['ProductFeatureRange'][$key]['from']) && empty($_POST['ProductFeatureRange'][$key]['to']))) {
                $feature_values[$key]['value']->product_id = $model->id;
                $feature_values[$key]['value']->save();
              }
            }
          }
          $command->reset();
          $command->delete('store_product_feature', $condition, $params);
          $command->reset();
          $command->delete('store_product_feature_range', $condition, $params);
          $command->reset();
          $command->delete('store_product_feature_value', $condition_val, $params);

          //delete prices
          ProductPrice::model()->deleteAll('product_id=:id AND price_id IN (:del)'
              , array(':id' => $model->id, ':del' => implode(',', $price_to_del)));
          //save prices
          foreach ($prices as $key => $value) {
            if ($value['price']->price) {
              $value['price']->product_id = $model->id;
              $value['price']->price_id = $key;
              $value['price']->attributes;
              $value['price']->validate();
              $err = $value['price']->getErrors();
              $value['price']->save();
            }
          }

          $this->moveImg($model, 'img');
          if ($_POST['Product']['small_img'])
            $this->moveImg($model, 'small_img');
          $model->save();
          $tr->commit();
          $this->redirect(array('index'));
        }
      } catch (Exception $e) {
        $tr->rollback();
        throw $e;
      }
    }
  }

  private function moveImg(Product $model, $img) {
    $old_img = $model->$img;
    $old_file = Yii::getPathOfAlias('webroot') . $old_img;
    $model->$img = $_POST['Product'][$img];
    $ext = substr($_POST['Product'][$img], strrpos($_POST['Product'][$img], '.'));
    $img_storage = '/images/' . Yii::app()->params['img_storage'] . '/product/';
    $file_path = Yii::getPathOfAlias('webroot') . $img_storage;
    $img_name = $model->id . ($img == 'img' ? '' : 's') . $ext;

    if ($_POST['Product'][$img] != $old_img) {
      if (strlen($_POST['Product'][$img]) > 0) {
        $uploaded_file = Yii::getPathOfAlias('webroot') . $_POST['Product'][$img];
        if (file_exists($uploaded_file)) {
          if ($model->isNewRecord)
            if (!$model->save())
              return;
          if (strlen($old_img) > 0 && file_exists($old_file))
            unlink($old_file);
          rename(Yii::getPathOfAlias('webroot') . $_POST['Product'][$img], $file_path . $img_name);
          $model->$img = $img_storage . basename($file_path . $img_name);
        }
      }else {
        unlink($old_file);
        $model->$img = '';
      }
    }

    if (!$_POST['Product']['small_img']) {
      if ($_POST['Product']['img']) {
        $model->createThumbnail();
      }
      else {
        if ($model->small_img) {
          $old_small_file = Yii::getPathOfAlias('webroot') . $model->small_img;
          unlink($old_small_file);
          $model->small_img = '';
        }
      }
    }
  }

  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'admin' page.
   * @param integer $id the ID of the model to be deleted
   */
  public function actionDelete($id) {
    if (Yii::app()->request->isPostRequest) {
      // we only allow deletion via POST request
      $this->loadModel($id)->delete();

      // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
      if (!isset($_GET['ajax'])) {
        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
      }
    }
    else {
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }
  }

  /**
   * Lists all models.
   */
  public function actionIndex() {
    $importData = new ImportFile;
    $model = new Product('search');
    $model->unsetAttributes();  // clear any default values
    if (isset($_GET['Product'])) {
      $model->attributes = $_GET['Product'];
      Yii::app()->user->setState('Product', $_GET['Product']);
    }
    elseif (Yii::app()->user->hasState('Product')) {
      $model->attributes = Yii::app()->user->getState('Product');
    }

    if (isset($_GET['Product_page']))
      Yii::app()->user->setState('Product_page', $_GET['Product_page']);
    elseif (isset($_GET['ajax']))
      Yii::app()->user->setState('Product_page', NULL);
    elseif (Yii::app()->user->hasState('Product_page'))
      $_GET['Product_page'] = (int) Yii::app()->user->getState('Product_page');

    if (isset($_GET['Product_sort']))
      Yii::app()->user->setState('Product_sort', $_GET['Product_sort']);
    elseif (Yii::app()->user->hasState('Product_sort'))
      $_GET['Product_sort'] = Yii::app()->user->getState('Product_sort');

    $this->render('index', array(
      'model' => $model,
      'importData' => $importData,
    ));
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return Product the loaded model
   * @throws CHttpException
   */
  public function loadModel($id) {
    $model = Product::model()->findByPk($id);
    if ($model === null) {
      throw new CHttpException(404, 'The requested page does not exist.');
    }
    return $model;
  }

  /**
   * Performs the AJAX validation.
   * @param Product $model the model to be validated
   */
  protected function performAjaxValidation($model) {
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'product-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }

  public function actionUpload() {
    if (isset($_FILES['file'])) {
      $file = $_FILES['file'];
      $prefix = Yii::app()->user->id;
    }
    elseif (isset($_FILES['fileMini'])) {
      $file = $_FILES['fileMini'];
      $prefix = Yii::app()->user->id . 's';
    }
    else {
      echo "Possible file upload attack!\n";
      Yii::app()->end();
    }
    $img_storage = Yii::app()->params['img_storage'];
    $uploaddir = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . 'uploads'
        . DIRECTORY_SEPARATOR . $img_storage . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
    $uploadfile = $uploaddir . $prefix . basename($file['name']);

    if (move_uploaded_file($file['tmp_name'], $uploadfile)) {
      echo '/uploads/' . $img_storage . '/temp/' . $prefix . $file['name'];
    }
    else {
      echo "Possible file upload attack!\n";
    }
    Yii::app()->end();
  }

  public function actionProductUpload() {

    if (isset($_POST['data'])) {
      $productImagePath = Yii::getPathOfAlias('webroot.productimages') .
          DIRECTORY_SEPARATOR;
      $quotes = array(
        "\xC2\xAB" => '"', // « (U+00AB) in UTF-8
        "\xC2\xBB" => '"', // » (U+00BB) in UTF-8
        "\xE2\x80\x98" => "'", // ‘ (U+2018) in UTF-8
        "\xE2\x80\x99" => "'", // ’ (U+2019) in UTF-8
        "\xE2\x80\x9A" => "'", // ‚ (U+201A) in UTF-8
        "\xE2\x80\x9B" => "'", // ‛ (U+201B) in UTF-8
        "\xE2\x80\x9C" => '"', // “ (U+201C) in UTF-8
        "\xE2\x80\x9D" => '"', // ” (U+201D) in UTF-8
        "\xE2\x80\x9E" => '"', // „ (U+201E) in UTF-8
        "\xE2\x80\x9F" => '"', // ‟ (U+201F) in UTF-8
        "\xE2\x80\xB9" => "'", // ‹ (U+2039) in UTF-8
        "\xE2\x80\xBA" => "'", // › (U+203A) in UTF-8
        "\xe2\x80\x93" => "-",
        "\xe2\x80\x94" => "-",
        "\xe2\x82\x09" => "-",
        "\xe2\x82\x11" => "-",
        "\xe2\x82\x12" => "-",
      );

      foreach ($_POST['data'] as $value) {
        $data = str_getcsv($value, ';');
        $count = count($data);
        if ($count != 13 && ($count < 3 || $count > 12)) {
          echo mb_substr($data[0], 0, 50, 'utf-8');
          Yii::app()->end();
        }
        if ($count == 13) {
          $brand_name = strtr($data[1], $quotes);
          $brand = Brand::model()->findByAttributes(array('name' => $brand_name));
          if (is_null($brand)) {
            $brand = new Brand;
            $brand->name = $brand_name;
            $brand->save();
          }
          $group_name = strtr($data[7], $quotes);
          $group = Category::model()->findByAttributes(array(
            'name' => $group_name), 'level=1');
          if (is_null($group)) {
            $group = new Category;
            $group->name = $group_name;
            $group->saveNode();
          }

          $category_name = strtr($data[6], $quotes);
          $category = Category::model()->findByAttributes(array(
            'name' => $category_name), 'level=2');
          if (is_null($category)) {
            $category = new Category;
            $category->name = $category_name;
            $category->appendTo($group);
          }
          $subcategory_name = strtr($data[5], $quotes);
          if ($data[2] == 'Т3453') {
            $d = $data[2];
          }
          $subcategory = Category::model()->findByAttributes(array(
            'name' => $subcategory_name), 'level = 3');
          if (is_null($subcategory)) {
            $subcategory = new Category;
            $subcategory->name = $subcategory_name;
            $subcategory->appendTo($category);
          }
          $name = strtr($data[0], $quotes);
          $age = (string) $data[10];
          $ages = split(' ', $age);
          $productData = array(
            'name' => $name,
            'article' => $data[2],
            'brand_id' => (int) $brand->id,
            'gender_id' => $data[11],
            'remainder' => $data[8],
            'description' => strtr($data[12], $quotes),
            'price' => $data[9],
            'age' => $ages[0],
            'age_to' => isset($ages[1]) ? $ages[1] : '',
            'show_me' => 1,
          );
          $product = Product::model()->findByAttributes(array(
            'article' => $productData['article']));
          if (is_null($product)) {
            $product = new Product;
            $product->attributes = $productData;
            $product->save(FALSE);
          }
          $product_category = ProductCategory::model()->findByAttributes(array(
            'product_id' => $product->id, 'category_id' => $subcategory->id));
          if (is_null($product_category)) {
            $product_category = new ProductCategory;
            $product_category->category_id = $subcategory->id;
            $product_category->product_id = $product->id;
            $product_category->save();
          }

          if ($_POST['uploadImage'] == 'true') {
            try {
              $imageUrl = $data[3];
              $ext = substr(basename($imageUrl), strrpos(basename($imageUrl), '.', -1));
              $productData['img'] = '/productimages/' . $product->id . $ext;
              $ch = curl_init($imageUrl);
              $fp = fopen($productImagePath . $product->id . $ext, 'w+');
              curl_setopt($ch, CURLOPT_FILE, $fp);
              curl_setopt($ch, CURLOPT_HEADER, 0);
              curl_exec($ch);
              fclose($fp);

              $smallImgUrl = $data[4];
              $small_ext = substr(basename($smallImgUrl), strrpos(basename($smallImgUrl), '.', -1));
              $productData['small_img'] = '/productimages/'
                  . $product->id . 's' . $small_ext;
              $sfp = fopen($productImagePath . $product->id . 's' . $small_ext, 'w+');
              curl_setopt($ch, CURLOPT_URL, $smallImgUrl);
              curl_setopt($ch, CURLOPT_FILE, $sfp);
              curl_exec($ch);
              curl_close($ch);
              fclose($sfp);
            } catch (Exception $e) {
              Yii::trace($product->article . '; ' . $product->name, 'error');
            }
          }
          $product->attributes = $productData;
          if ($_POST['uploadImage'] == 'true') {
            $product->img = $productData['img'];
            $product->small_img = $productData['small_img'];
          }
          $product->save(FALSE);
        }
        else {
          $product = Product::model()->findByAttributes(array(
            'article' => $data[2]));
          if (!is_null($product)) {
            $product->remainder = $data[1];
//            $product->price = $data[3];
            $product->save(FALSE);
          }
        }
      }
      echo 'ok';
    }
    else {
      echo 'error';
    }
    Yii::app()->end();
  }

  public function actionFeatureValues() {
    if (isset($_POST['category'])) {
      $feature = $this->features($_POST['id'], $_POST['category']);
      echo $this->renderPartial('_feature', array(
        'feature' => $feature,
          ), true);
    }
    Yii::app()->end();
  }

  public function actionPriceUpload() {
    Yii::import('webroot.js_plugins.jQueryFileUpload.server.php.UploadHandler');

    $dir = YiiBase::getPathOfAlias('webroot') . '/uploads/' . Yii::app()->theme->name . '/';
    $tmpDir = $dir . 'temp/';

    $options = array(
      'upload_dir' => $tmpDir,
      'accept_file_types' => '/\.(xls)$/i',
    );
    $upload_handler = new UploadHandler($options);

    $file = 'price.xls';
    if (isset($_FILES['files']['name'])) {
      if (file_exists($dir . $file))
        unlink($dir . $file);
      rename($tmpDir . $_FILES['files']['name'], $dir . $file);
    }
  }

}
