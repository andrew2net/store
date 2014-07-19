<?php

class m131028_043824_product_table extends CDbMigration {

  public function safeUp() {
    $this->createTable('store_product', array(
      'id' => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
      'name' => 'string',
      'url' => 'string',
      'img' => 'string',
      'small_img' => 'string',
      'article' => 'varchar(25)',
      'brand_id' => 'integer UNSIGNED',
      'remainder' => 'smallint UNSIGNED',
      'description' => 'text',
      'price' => 'decimal(12,2) UNSIGNED',
      'show_me' => 'boolean',
    ));
    $this->addForeignKey('product_brand', 'store_product', 'brand_id', 'store_brand'
        , 'id', 'RESTRICT', 'CASCADE');
    $this->createIndex('unique_article', 'store_product', 'article', TRUE);
  }

  public function down() {
    echo "m131028_043824_product_table does not support migration down.\n";
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