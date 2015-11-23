<?php

class m151123_173506_add_index_to_customerprofile extends CDbMigration
{
	public function up()
	{
		$this->createIndex('customer_profile_user', 'store_customer_profile', 'user_id');
		$this->createIndex('customer_profile_session', 'store_customer_profile', 'session_id');
	}

	public function down()
	{
		echo "m151123_173506_add_index_to_customerprofile does not support migration down.\n";
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