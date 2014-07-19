<?php

class m131130_111136_cart_table extends CDbMigration {

  public function safeUp() {
    $this->createTable('store_cart', array(
      'id' => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
      'session_id' => 'varchar(32)',
      'user_id' => 'int(11)',
      'product_id' => 'int(11) UNSIGNED NOT NULL',
      'quantity' => 'smallint UNSIGNED',
      'time' => 'timestamp',
    ));
    $this->addForeignKey('cart_product', 'store_cart', 'product_id'
        , 'store_product', 'id', 'CASCADE');
  }

  public function down() {
    echo "m131130_111136_cart_table does not support migration down.\n";
    return false;
  }

  /*
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
   */
}