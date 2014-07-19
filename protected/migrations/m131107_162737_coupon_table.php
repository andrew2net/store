<?php

class m131107_162737_coupon_table extends CDbMigration {

  public function safeUp() {
    $this->createTable('store_coupon', array(
      'id' => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
      'code' => 'varchar(8) NOT NULL',
      'type_id' => 'tinyint UNSIGNED NOT NULL',  //0-summa, 1-percent
      'value'=> 'int(5) UNSIGNED NOT NULL',
      'used_id' => 'tinyint UNSIGNED NOT NULL',  //0-unused, 1-permanent, 2-used
      'time_issue' => 'timestamp',   //time of issue
      'time_used' => 'TIMESTAMP',
      'date_limit' => 'date',
    ));
    $this->createIndex('coupon_code_used', 'store_coupon', 'used_id, time_used, code');
  }

  public function down() {
    echo "m131107_162737_coupon_table does not support migration down.\n";
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