<?php

class m140729_132513_order_custom_delivery extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_order', 'customer_delivery', 'string');
	}

	public function down()
	{
		echo "m140729_132513_order_custom_delivery does not support migration down.\n";
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