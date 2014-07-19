<?php

class m140428_055922_feature_range extends CDbMigration
{
	public function up()
	{
    $this->createTable('store_product_feature_range', array(
      'product_id' => 'INT(11) UNSIGNED NOT NULL',
      'feature_id' => 'INT(11) UNSIGNED NOT NULL',
      'from' => 'float',
      'to' => 'float',
    ));
    $this->addPrimaryKey('pk_product_feature', 'store_product_feature_range', 'product_id, feature_id');
    $this->addForeignKey('product_range', 'store_product_feature_range', 'product_id', 'store_product', 'id', 'CASCADE');
    $this->addForeignKey('feature_range', 'store_product_feature_range', 'feature_id', 'store_feature', 'id', 'CASCADE');
    
    $this->createTable('store_product_feature_value', array(
      'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
      'product_id' => 'INT(11) UNSIGNED NOT NULL',
      'value_id' => 'INT(11) UNSIGNED NOT NULL',
    ));
    $this->addForeignKey('product_value', 'store_product_feature_value', 'product_id', 'store_product', 'id', 'CASCADE');
    $this->addForeignKey('feature_val', 'store_product_feature_value', 'value_id', 'store_feature_value', 'id', 'CASCADE');
	}

	public function down()
	{
		echo "m140428_055922_feature_range does not support migration down.\n";
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