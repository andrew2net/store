<?php

/**
 * Description of FeatureController
 *
 */
class FeatureController extends Controller {

  public function filters() {
    return array(
      array('auth.filters.AuthFilter'),
      'postOnly + delete',
    );
  }

  public function actionIndex() {
    $model = new Feature('search');
    $model->unsetAttributes();
    if (isset($_GET['Feature'])) {
      $model->attributes = $_GET['Feature'];
    }
    $this->render('index', array('model' => $model));
  }

  public function actionCreate() {
    $model = new Feature;
    $values = $model->featureValues;

    if (isset($_POST['Feature'])) 
      $this->saveFeature($model, $values);
    
    $this->render('create', array('model' => $model, 'values' => $values));
  }

  public function actionUpdate($id) {
    $model = $this->loadModel($id);
    /* @var $model Feature */
    $values = $model->featureValues;

    if (isset($_POST['Feature'])) 
      $this->saveFeature($model, $values);
    
    $this->render('update', array('model' => $model, 'values' => $values));
  }

  private function saveFeature(&$model, &$values) {
    $model->attributes = $_POST['Feature'];
    $valid = $model->validate();
    $values = array();
    if (isset($_POST['FeatureValue']) && $model->type_id == 1) {
      foreach ($_POST['FeatureValue'] as $key => $value) {
        $values[$key] = new FeatureValue;
        $values[$key]->value = $value['value'];
        $valid = $values[$key]->validate(array('value')) && $valid;
      }
      if ($valid) {
        $tr = Yii::app()->db->beginTransaction();
        try {
          $model->save();
          $this->delFeatures($model->id);
          foreach ($values as $value) {
            $value->feature_id = $model->id;
            $value->save();
          }
          $tr->commit();
          $this->redirect('/admin/catalog/feature');
        } catch (Exception $e) {
          $tr->rollback();
          throw $e;
        }
      }
    }
    else if ($model->save()) {
      $this->delFeatures($model->id);
      $this->redirect('/admin/catalog/feature');
    }
  }

  private function delFeatures($id) {
    Yii::app()->db->createCommand()->delete('store_feature_value', 'feature_id=:id', array(':id' => $id));
  }

  public function actionDelete($id) {
    if (Yii::app()->request->isPostRequest) {
      $this->loadModel($id)->delete();

      if (isset($_GET['ajax']))
        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnURL'] : array('/admin/catalog/feature'));
    }
    else {
      return CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }
  }

  public function loadModel($id) {
    $model = Feature::model()->findByPk($id);
    if ($model === NULL)
      throw new CHttpException(404, 'The requested page does not exist.');
    return $model;
  }

}

