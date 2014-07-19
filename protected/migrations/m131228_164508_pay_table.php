<?php

class m131228_164508_pay_table extends CDbMigration {

  public function safeUp() {
    $this->createTable('store_pay', array(
      'id' => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
      'order_id' => 'int(11) UNSIGNED NOT NULL',
      'mnt_operation_id' => 'varchar(30)',
      'mnt_amount' => 'decimal(12,2) UNSIGNED',
      'pay_system_id' => 'varchar(10)',
      'mnt_corr_acc' => 'varchar(30)',
      'time' => 'datetime',
    ));

    $this->addForeignKey('pay_order', 'store_pay', 'order_id', 'store_order', 'id');
  }

  public function down() {
    echo "m131228_164508_pay_table does not support migration down.\n";
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