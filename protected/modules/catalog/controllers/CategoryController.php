<?php

class CategoryController extends BaseController {

  public function behaviors() {
    return array(
      'jsTreeBehavior' => array('class' => 'application.behaviors.JsTreeBehavior',
        'modelClassName' => 'Category',
        'form_alias_path' => 'application.modules.catalog.views.category._form',
        'view_alias_path' => 'application.modules.catalog.views.category.view',
        'label_property' => 'name',
        'rel_property' => 'name'
      )
    );
  }

  public function filters() {
    return array_merge(parent::filters(), array(
      array('auth.filters.AuthFilter')
    ));
  }

  public function actionIndex() {
    $model = new Category;
    $this->render('index', array('model' => $model));
  }

  /**
   * Performs the AJAX validation.
   * @param CModel the model to be validated
   */
  protected function performAjaxValidation($model) {
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'category-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }

  public function actionUpload() {
    $img_storage = Yii::app()->params['img_storage'];
    $uploaddir = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . 'uploads' 
        .DIRECTORY_SEPARATOR .$img_storage. DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
    $uploadfile = $uploaddir . Yii::app()->user->id . basename($_FILES['file']['name']);

    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
      echo '/uploads/'.$img_storage.'/temp/' . Yii::app()->user->id . $_FILES['file']['name'];
    }
    else {
      echo "Possible file upload attack!\n";
    }
  }

}