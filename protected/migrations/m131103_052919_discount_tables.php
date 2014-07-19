<?php

class m131103_052919_discount_tables extends CDbMigration {

  public function safeUp() {
    $this->createTable('store_discount', array(
      'id' => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
      'type_id' => 'tinyint UNSIGNED NOT NULL', //birthday, week, select product
      'begin_date' => 'date',
      'end_date' => 'date',
      'actual' => 'boolean',
      'product_id' => 'tinyint UNSIGNED NOT NULL', //all, group, product
      'percent' => 'tinyint UNSIGNED NOT NULL',
    ));

    $this->createTable('store_discount_category', array(
      'discount_id' => 'int(11) UNSIGNED NOT NULL',
      'category_id' => 'int(11) UNSIGNED NOT NULL',
    ));
    $this->addForeignKey('discount_category', 'store_discount_category'
        , 'discount_id', 'store_discount', 'id', 'CASCADE', 'CASCADE');
    $this->addForeignKey('category_discount', 'store_discount_category'
        , 'category_id', 'store_category', 'id', 'CASCADE', 'CASCADE');

    $this->createTable('store_discount_product', array(
      'discount_id' => 'int(11) UNSIGNED NOT NULL',
      'product_id' => 'int(11) UNSIGNED NOT NULL',
    ));
    $this->addForeignKey('discount_product', 'store_discount_product'
        , 'discount_id', 'store_discount', 'id', 'CASCADE', 'CASCADE');
    $this->addForeignKey('product_discount', 'store_discount_product'
        , 'product_id', 'store_product', 'id', 'CASCADE', 'CASCADE');
  }

  public function down() {
    echo "m131103_052919_discount_tables does not support migration down.\n";
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