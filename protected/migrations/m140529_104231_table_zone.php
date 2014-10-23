<?php

class m140529_104231_table_zone extends CDbMigration
{
	public function up()
	{
    $this->createTable('store_zone', array(
      'type_id' => 'INT(2) UNSIGNED NOT NULL',
      'country_code' => 'VARCHAR(2) NOT NULL',
      'post_code' => 'VARCHAR(6) NOT NULL',
      'zone' => 'INT(3) UNSIGNED',
    ));
    $this->addPrimaryKey('country_post', 'store_zone', 'country_code, post_code');
	}

	public function down()
	{
		echo "m140529_104231_table_zone does not support migration down.\n";
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