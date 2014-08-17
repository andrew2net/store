<?php

class m140814_045241_1c_code extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_price', 'code', 'VARCHAR(9)');
    $this->addColumn('store_category', 'code', 'VARCHAR(11)');
	}

	public function down()
	{
		echo "m140814_045241_1c_code does not support migration down.\n";
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