<?php

class RegionController extends Controller {

  /**
   * @return array action filters
   */
  public function filters() {
    return array(
      array('auth.filters.AuthFilter'),
      'postOnly + delete', // we only allow deletion via POST request
    );
  }

  /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   */
  public function actionCreate() {
    $model = new Region;
    $delivery = $this->loadDelivery($model);

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if (isset($_POST['Region'])) {
      $model->attributes = $_POST['Region'];
      if ($model->save()) {
        $this->saveRegionDelivery($model, $delivery);
        $this->redirect(array('index'));
      }
    }

    $this->render('create', array(
      'model' => $model,
      'delivery' => $delivery,
    ));
  }

  /**
   * Updates a particular model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id the ID of the model to be updated
   */
  public function actionUpdate($id) {
    $region = $this->loadModel($id);
    $delivery = $this->loadDelivery($region);

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if (isset($_POST['Region'])) {
      if ($this->saveRegionDelivery($region, $delivery)) {
        $this->redirect(array('index'));
      }
    }

    $this->render('update', array(
      'model' => $region,
      'delivery' => $delivery,
    ));
  }

  private function loadDelivery(Region $region) {
    Yii::import('application.modules.payments.models.Currency');
    $deliveries = Delivery::model()
        ->with(array(
          'deliveryRates' => array(
            'on' => 'deliveryRates.region_id=:id',
            'params' => array(':id' => $region->id),
          ),
          'regionDeliveries' => array(
            'on' => 'regionDeliveries.region_id=:id',
          ),
        ))
        ->findAll(array('order' => 't.id, weight'));

    $delivery_rate = array();
    foreach ($deliveries as $delivery) {
      /* @var $delivery Delivery */

      if (count($delivery->regionDeliveries))
        $regionDelivery = $delivery->regionDeliveries[0];
      else
        $regionDelivery = new RegionDelivery;

      $delivery_rate[$delivery->id] = array(
        'name' => $delivery->name,
        'regionDelivery' => $regionDelivery,
        'zones' => $this->getZones($delivery->zone_type_id, $region->country_code),
        'currency' => is_null($delivery->currency) ? '' : $delivery->currency->short,
        'data' => array(),
      );
      foreach ($delivery->deliveryRates as $rate)
        $delivery_rate[$delivery->id]['data'][$rate->id] = $rate;
    }
    return $delivery_rate;
  }

  private function getZones($type_id, $country_code) {
    return CHtml::listData(Zone::model()->findAll(array(
              'condition' => 'type_id=:id AND country_code=:c_code',
              'params' => array(
                ':id' => $type_id,
                ':c_code' => $country_code),
              'order' => 'zone',
              'group' => 'zone')
            ), 'zone', 'zone');
  }

  private function saveRegionDelivery(Region $region, &$delivery_rate) {
    $region->attributes = $_POST['Region'];
    $valid = $region->validate();

    $delivery_rate = array();
    if (isset($_POST['DeliveryRate'])) {

      if (isset($_POST['RegionDelivery']))
        foreach ($_POST['RegionDelivery'] as $key => $value) {
          if (!isset($_POST['DeliveryRate'][$key]))
            continue;
          $delivery_model = Delivery::model()->findByPk($key);
          $delivery_rate[$key] = array(
            'name' => $delivery_model->name,
            'currency' => is_null($delivery_model->currency) ? '' : $delivery_model->currency->short,
            'zones' => $this->getZones($key, $region->country_code),
            'data' => array(),
            'regionDelivery' => array()
          );

          if ($region->isNewRecord)
            $region_delivery_model = new RegionDelivery;
          else
            $region_delivery_model = RegionDelivery::model()->findByPk(array('delivery_id' => $key, 'region_id' => $region->id));
          if (!$region_delivery_model)
            $region_delivery_model = new RegionDelivery;

          $region_delivery_model->attributes = $value;
          $valid = $region_delivery_model->validate(array('zone', 'weight_rate')) && $valid;
          $delivery_rate[$key]['regionDelivery'] = $region_delivery_model;
        }

      foreach ($_POST['DeliveryRate'] as $delivery_id => $delivery) {
        foreach ($delivery as $key => $rate) {

          if ($region->isNewRecord)
            $deliveryRate = new DeliveryRate;
          else
            $deliveryRate = DeliveryRate::model()->findByPk($key, 'delivery_id=:did AND region_id=:rid'
                , array(':did' => $delivery_id, ':rid' => $region->id));
          if (!$deliveryRate)
            $deliveryRate = new DeliveryRate;

          $delivery_rate[$delivery_id]['data'][$key] = $deliveryRate;
          $delivery_rate[$delivery_id]['data'][$key]->attributes = $rate;
          $delivery_rate[$delivery_id]['data'][$key]->delivery_id = $delivery_id;
          $valid = $delivery_rate[$delivery_id]['data'][$key]->validate(array('weight', 'price')) && $valid;
        }
      }

      if ($valid) {
        $tr = Yii::app()->db->beginTransaction();
        try {
          $region->save();
          foreach ($delivery_rate as $key => $delivery) {
            $delivery['regionDelivery']->region_id = $region->id;
            $delivery['regionDelivery']->delivery_id = $key;
            $delivery['regionDelivery']->save();
            $condition = "region_id=:rid AND delivery_id=:did";
            $ids = implode(',', array_keys($delivery['data']));
            if ($ids)
              $condition .= " AND NOT id IN ($ids)";
            DeliveryRate::model()->deleteAll($condition
                , array(':rid' => $region->id, ':did' => $key));
            foreach ($delivery['data'] as $rate) {
              $rate->region_id = $region->id;
              $rate->save();
            }
          }
          $tr->commit();
        } catch (Exception $e) {
          $tr->rollback();
          $valid = FALSE;
          throw $e;
        }
      }
    }
    elseif (!$region->save())
      return FALSE;
    return $valid;
  }

  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'admin' page.
   * @param integer $id the ID of the model to be deleted
   */
  public function actionDelete($id) {
    if (Yii::app()->request->isPostRequest) {
      // we only allow deletion via POST request
      $model = $this->loadModel($id);
      $tr = Yii::app()->db->beginTransaction();
      try {
        DeliveryRate::model()->deleteAll('region_id=:id', array(':id' => $id));
        $model->delete();
        $tr->commit();
      } catch (Exception $e) {
        $tr->rollback();
        throw $e;
      }

      // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
      if (!isset($_GET['ajax'])) {
        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
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
    $dataProvider = new CActiveDataProvider('Region', array('criteria' => array('with' => array('country'))));
    $this->render('index', array(
      'dataProvider' => $dataProvider,
    ));
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return City the loaded model
   * @throws CHttpException
   */
  public function loadModel($id) {
    $model = Region::model()->with('country')->findByPk($id);
    if ($model === null) {
      throw new CHttpException(404, 'The requested page does not exist.');
    }
    return $model;
  }

  /**
   * Performs the AJAX validation.
   * @param City $model the model to be validated
   */
  protected function performAjaxValidation($model) {
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'region-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }

  public function actionZones() {
    if (isset($_POST['code'])) {
      $delivery = Delivery::model()->findAll();
      $zones = array();
      foreach ($delivery as $value) {
        $zone_list = CHtml::listData(Zone::model()->findAll(array(
                  'condition' => 'delivery_id=:id AND country_code=:c_code',
                  'params' => array(
                    ':id' => $value->id,
                    ':c_code' => $_POST['code']),
                  'order' => 'zone')
                ), 'zone', 'zone');
        $zones[$value->id] = TbHtml::dropDownList("RegionDelivery[$value->id][zone]", '', $zone_list
                , array('prompt' => '--', 'span' => 1));
      }
      echo json_encode($zones);
    }
    Yii::app()->end();
  }

}