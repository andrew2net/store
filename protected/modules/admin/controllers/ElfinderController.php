<?php

class ElfinderController extends CController {

  public $layout = false;

  public function filters() {
    return array(array('auth.filters.AuthFilter'));
  }

  public function actions() {
    $img_storage = '/images/' . Yii::app()->params['img_storage'];
    return array(
      'connector' => array(
        'class' => 'ext.elFinder2.ElFinderConnectorAction',
        'settings' => array(
          'roots' => array(
            array(
              'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
              'path' => Yii::getPathOfAlias('webroot') . $img_storage. '/common/', // path to files (REQUIRED)
              'URL' => Yii::app()->baseUrl . $img_storage. '/common/', // URL to files (REQUIRED)
              'accessControl' => 'access', // disable and hide dot starting files (OPTIONAL)
              'tmbPath' => Yii::getPathOfAlias('webroot') . $img_storage . '/.tmb',
              'tmbURL' => Yii::app()->baseUrl . $img_storage. '/.tmb',
            ))
        )
      ),
    );
  }

  public function actionElfinder() {
    $this->render('elfinder');
  }

}
