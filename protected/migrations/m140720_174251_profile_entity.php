<?php

class m140720_174251_profile_entity extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_customer_profile', 'entity_id', 'INT(1)');
	}

	public function down()
	{
		echo "m140720_174251_profile_entity does not support migration down.\n";
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