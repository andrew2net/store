<?php

class m131030_094846_product_category_table extends CDbMigration
{
	public function safeUp()
	{
    $this->createTable('store_product_category', array(
      'product_id'=>'int(11) UNSIGNED NOT NULL',
      'category_id'=>'int(11) UNSIGNED NOT NULL',
    ));
    $this->addForeignKey('product_id', 'store_product_category', 'product_id'
        , 'store_product', 'id', 'CASCADE', 'CASCADE');
    $this->addForeignKey('category_id', 'store_product_category', 'category_id'
        , 'store_category', 'id', 'CASCADE', 'CASCADE');
	}

	public function down()
	{
		echo "m131030_094846_product_category_table does not support migration down.\n";
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