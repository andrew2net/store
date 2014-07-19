<?php

class m140425_101554_feature_value extends CDbMigration {

  public function up() {
    $this->createTable('store_feature_value', array(
      'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
      'feature_id' => 'INT(11) UNSIGNED NOT NULL',
      'value' => 'VARCHAR(10)',
    ));

    $this->addForeignKey('feature_value', 'store_feature_value', 'feature_id', 'store_feature', 'id', 'CASCADE');

    $this->dropColumn('store_category_feature', 'search');

    $this->addColumn('store_feature', 'search', 'boolean');
    $this->addColumn('store_feature', 'unit', 'VARCHAR(6)');
  }

  public function down() {
    echo "m140425_101554_feature_value does not support migration down.\n";
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