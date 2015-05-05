<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of YmlController
 *
 * @author atlant
 */
class YmlController extends Controller {

  public function actionIndex() {
    if (!$xml = Yii::app()->cache->get('yml')) {
      Yii::import('application.modules.admin.models.Page');
      Yii::import('application.modules.catalog.models.Category');
      $sitemap = new SitemapLogic();
      $sitemap->addUrlsByModel(Page::model(), SitemapLogic::WEEKLY);
      $sitemap->addUrlsByModel(Category::model());
      $xml = $sitemap->render();
      Yii::app()->cache->set('yml', 'yml', 3600 * 24);
    }
    header('Content-type: text/xml');
    echo $xml;
    Yii::app()->end();
  }

}
