<?php

/**
 * Description of CartTest
 *
 */

class CartTest extends WebTestCase {

  public function testActionIndex() {
    $this->open("cart");
    $this->assertTextPresent('Ваша корзина');
  }

}
