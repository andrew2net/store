<?php

class m141023_055828_product_weight extends CDbMigration
{
	public function up()
	{
    $this->alterColumn('store_product', 'weight', 'DECIMAL(5,3)');
	}

	public function down()
	{
		echo "m141023_055828_product_weight does not support migration down.\n";
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