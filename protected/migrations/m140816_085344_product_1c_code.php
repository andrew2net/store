<?php

class m140816_085344_product_1c_code extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_product', 'code', 'VARCHAR(11)');
	}

	public function down()
	{
		echo "m140816_085344_product_1c_code does not support migration down.\n";
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