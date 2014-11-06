<?php

class m141106_194532_delivery_max_weght extends CDbMigration
{
	public function up()
	{
    $this->alterColumn('store_delivery', 'max_weight', 'decimal(5,2)');
	}

	public function down()
	{
		echo "m141106_194532_delivery_max_weght does not support migration down.\n";
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