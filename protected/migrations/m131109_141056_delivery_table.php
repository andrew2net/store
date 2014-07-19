<?php

class m131109_141056_delivery_table extends CDbMigration {

  public function safeUp() {
    $this->createTable('store_delivery', array(
      'id' => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
      'name' => 'varchar(30) NOT NULL',
      'description' => 'text',
    ));
    $this->createTable('store_region', array(
      'id' => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
      'type_id' => 'INT(1) UNSIGNED NOT NULL',
      'country_code' => 'VARCHAR(2)',
      'name' => 'varchar(100)',
    ));
  }

  public function down() {
    echo "m131109_141056_delivery_table does not support migration down.\n";
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