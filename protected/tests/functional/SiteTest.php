<?php
/**
 * Description of SiteTest
 *
 */
class SiteTest extends WebTestCase {
  
  public function testActionIndex(){
    $this->open('');
    $this->assertTitle('Tornado - аксессуары для мобильных устройств оптом');
    $this->assertTextPresent('Оптовая');
  }
}

?>
