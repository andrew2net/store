<?php

class m151123_224756_customer_profile_add_index_session extends CDbMigration
{
	public function up()
	{
		$this->dropIndex('customer_profile_user', 'store_customer_profile');
		$this->createIndex('customer_profile_session', 'store_customer_profile', 'session_id');
	}

	public function down()
	{
		echo "m151123_224756_customer_profile_add_index_session does not support migration down.\n";
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