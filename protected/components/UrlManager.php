<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UrlManager
 *
 * @author atlant
 */
class UrlManager extends CUrlManager {

  public function createUrl($route, $params = array(), $ampersand = '&') {
    if (!isset($params['language']) && !isset($params['no_language']) && !Yii::app()->params['country']) {
      if (Yii::app()->user->hasState('language'))
        $params['language'] = Yii::app()->user->getState('language');
      else if (isset(Yii::app()->request->cookies['language']))
        $params['language'] = Yii::app()->request->cookies['language']->value;
    }elseif (isset($params['no_language'])) {
      unset($params['no_language']);
    }
    $url = parent::createUrl($route, $params, $ampersand);
    if (preg_match('/language(?:\/|=)(?:ru|kz)/', $url)) {
      unset($params['language']);
      $url = parent::createUrl($route, $params, $ampersand);
    }
    return $url;
  }

}
