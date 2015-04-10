<?php

class ElFinderConnectorAction extends CAction {

  /**
   * @var array
   */
  public $settings = array();

  public function run() {
    require_once(dirname(__FILE__) . '/php/elFinder.class.php');
    include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'php/elFinderConnector.class.php';
    include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'php/elFinderVolumeDriver.class.php';
    include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'php/elFinderVolumeLocalFileSystem.class.php';

    $fm = new elFinderConnector(new elFinder($this->settings));
    $fm->run();
  }

}
