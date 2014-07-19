<?php

class m140408_165201_page_fields extends CDbMigration
{
	public function up()
	{
    $this->addColumn('{{page}}', 'menu_show', 'TINYINT UNSIGNED DEFAULT 0');
	}

	public function down()
	{
		echo "m140408_165201_page_fields does not support migration down.\n";
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