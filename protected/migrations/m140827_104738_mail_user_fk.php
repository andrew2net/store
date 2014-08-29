<?php

class m140827_104738_mail_user_fk extends CDbMigration
{
	public function up()
	{
    $this->addForeignKey('fk_mail_user', 'store_mail', 'uid', '{{users}}', 'id', 'CASCADE', 'CASCADE');
	}

	public function down()
	{
		echo "m140827_104738_mail_user_fk does not support migration down.\n";
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