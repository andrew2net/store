<?php

class m140604_174116_delivery_zone_type extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_delivery', 'zone_type_id', 'INT(2) UNSIGNED');
	}

	public function down()
	{
		echo "m140604_174116_delivery_zone_type does not support migration down.\n";
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