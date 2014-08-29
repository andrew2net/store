<?php

class m140828_041703_mail_time extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_mail', 'made_time', 'datetime NOT NULL');
    $this->addColumn('store_mail', 'sent_time', 'datetime');
	}

	public function down()
	{
		echo "m140828_041703_mail_time does not support migration down.\n";
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