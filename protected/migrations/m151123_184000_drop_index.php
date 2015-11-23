<?php

class m151123_184000_drop_index extends CDbMigration
{
	public function up()
	{
		$this->dropIndex('customer_profile_session', 'store_customer_profile');
	}

	public function down()
	{
		echo "m151123_184000_drop_index does not support migration down.\n";
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