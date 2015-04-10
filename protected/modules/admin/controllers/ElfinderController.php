<?php

class ElfinderController extends CController {

  public $layout = false;

  public function filters() {
    return array(array('auth.filters.AuthFilter'));
  }

  public function actions() {
    $img_storage = '/images/' . Yii::app()->params['img_storage'] . '/common/';
    return array(
      'connector' => array(
        'class' => 'ext.elFinder2.ElFinderConnectorAction',
        'settings' => array(
          'roots' => array(
            array(
              'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
              'path' => Yii::getPathOfAlias('webroot') . $img_storage, // path to files (REQUIRED)
              'URL' => Yii::app()->baseUrl . $img_storage, // URL to files (REQUIRED)
              'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
            ))
        )
      ),
    );
  }

  public function actionElfinder(){
    $this->render('elfinder');
  }
}
