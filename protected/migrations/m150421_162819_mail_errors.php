<?php

class m150421_162819_mail_errors extends CDbMigration
{
	public function up()
	{
      $this->addColumn('store_mail', 'errors', 'INT(1)');
	}

	public function down()
	{
		echo "m150421_162819_mail_errors does not support migration down.\n";
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