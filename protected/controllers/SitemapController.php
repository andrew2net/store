<?php

/**
 * Description of SitemapController
 *
 */
class SitemapController extends Controller {

  public function actionIndex() {
    if (!$xml = Yii::app()->cache->get('sitemap')){
      Yii::import('application.modules.admin.models.Page');
      $sitemap = new SitemapLogic();
      $sitemap->addUrlsByModel(Page::model(), SitemapLogic::WEEKLY);
      $xml = $sitemap->render();
    }
    header('Content-type: text/xml');
    echo $xml;
    Yii::app()->end();
  }

}
