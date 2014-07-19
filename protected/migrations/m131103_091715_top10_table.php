<?php

class m131103_091715_top10_table extends CDbMigration {

  public function safeUp() {
    $this->createTable('store_top10', array(
      'product_id' => 'int(11) UNSIGNED NOT NULL PRIMARY KEY',
    ));

    $this->addForeignKey('top10_product', 'store_top10', 'product_id'
        , 'store_product', 'id', 'CASCADE');
  }

  public function down() {
    echo "m131103_091715_top10_table does not support migration down.\n";
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