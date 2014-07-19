<?php

class m140419_145554_feature extends CDbMigration {

  public function safeUp() {
    $this->createTable('store_feature', array(
      'id' => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
      'name' => 'string',
      'type_id' => 'tinyint UNSIGNED',
    ));

    $this->createTable('store_category_feature', array(
      'category_id' => 'INT(11) UNSIGNED NOT NULL',
      'feature_id' => 'INT(11) UNSIGNED NOT NULL',
      'search' => 'boolean',
    ));
    $this->addForeignKey('category_feature', 'store_category_feature', 'category_id', 'store_category', 'id', 'CASCADE');
    $this->addForeignKey('feature_category', 'store_category_feature', 'feature_id', 'store_feature', 'id', 'CASCADE');

    $this->createTable('store_product_feature', array(
      'product_id' => 'INT(11) UNSIGNED NOT NULL',
      'feature_id' => 'INT(11) UNSIGNED NOT NULL',
      'value' => 'string',
    ));
    $this->addPrimaryKey('pk_product_feature', 'store_product_feature', 'product_id, feature_id');
    $this->addForeignKey('product_feature', 'store_product_feature', 'product_id', 'store_product', 'id', 'CASCADE');
    $this->addForeignKey('feature', 'store_product_feature', 'feature_id', 'store_feature', 'id', 'CASCADE');
  }

  public function safeDown() {
    echo "m140419_145554_feature does not support migration down.\n";
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