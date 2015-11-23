<?php

class m151123_185241_add_uid_index_to_mail extends CDbMigration
{
	public function up()
	{
		$this->createIndex('mail_uid', 'store_mail', 'uid');
	}

	public function down()
	{
		echo "m151123_185241_add_uid_index_to_mail does not support migration down.\n";
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