<?php

/**
 * Description of WebServiceAction
 *
 */
class WebServiceAction extends CWebServiceAction {

	private $_service;

  /**
   * Runs the action.
   * If the GET parameter {@link serviceVar} exists, the action handle the remote method invocation.
   * If not, the action will serve WSDL content;
   */
  public function run() {
    $hostInfo = Yii::app()->getRequest()->getHostInfo();
    $controller = $this->getController();
    if (($serviceUrl = $this->serviceUrl) === null)
      $serviceUrl = $hostInfo . $controller->createUrl($this->getId(), array($this->serviceVar => 1)) . (strpos($hostInfo, 'local') === FALSE ? '' : '?XDEBUG_SESSION_START=netbeans-xdebug');
    if (($wsdlUrl = $this->wsdlUrl) === null)
      $wsdlUrl = $hostInfo . $controller->createUrl($this->getId());
    if (($provider = $this->provider) === null)
      $provider = $controller;

    $this->_service = $this->createWebService($provider, $wsdlUrl, $serviceUrl);

    if (is_array($this->classMap))
      $this->_service->classMap = $this->classMap;

    foreach ($this->serviceOptions as $name => $value)
      $this->_service->$name = $value;

    if (isset($_GET[$this->serviceVar]))
      $this->_service->run();
    else
      $this->_service->renderWsdl();

    Yii::app()->end();
  }

}
