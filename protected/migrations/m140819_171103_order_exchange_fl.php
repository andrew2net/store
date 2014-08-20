<?php

class m140819_171103_order_exchange_fl extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_order', 'exchange', 'boolean');
	}

	public function down()
	{
		echo "m140819_171103_order_exchange_fl does not support migration down.\n";
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