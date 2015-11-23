<?php

class m151123_170206_create_index_for_mail extends CDbMigration
{
	public function up()
	{
            $this->createIndex('mail_status', 'store_mail', 'status_id');
            $this->createIndex('mail_type', 'store_mail', 'type_id');
	}

	public function down()
	{
		echo "m151123_170206_create_index_for_mail does not support migration down.\n";
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