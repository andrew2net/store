<?php

class m150109_183717_order_phone extends CDbMigration
{
	public function up()
	{
    $this->alterColumn('store_order', 'phone', 'VARCHAR(20)');
	}

	public function down()
	{
		echo "m150109_183717_order_phone does not support migration down.\n";
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