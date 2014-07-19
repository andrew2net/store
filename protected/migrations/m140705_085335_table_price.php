<?php

class m140705_085335_table_price extends CDbMigration
{
	public function up()
	{
    $this->createTable('store_price', array(
      'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
      'name' => 'string NOT NULL',
      'summ' => 'DECIMAL(12,2) UNSIGNED',
    ));
    
    $this->createTable('store_product_price', array(
      'product_id' => 'INT(11) UNSIGNED NOT NULL',
      'price_id' => 'INT(11) UNSIGNED NOT NULL',
      'price' => 'DECIMAL(12,2) UNSIGNED',
    ));
    $this->addPrimaryKey('pk_product_price', 'store_product_price', 'product_id, price_id');
    $this->addForeignKey('fk_product_price', 'store_product_price', 'product_id', 'store_product', 'id', 'CASCADE');
    $this->addForeignKey('fk_price_product', 'store_product_price', 'price_id', 'store_price', 'id', 'CASCADE');
	}

	public function down()
	{
		echo "m140705_085335_table_price does not support migration down.\n";
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