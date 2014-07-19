<?php

class m140523_100425_post_code extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_customer_profile', 'post_code', 'VARCHAR(6)');
    $this->addColumn('store_order', 'post_code', 'VARCHAR(6)');
	}

	public function down()
	{
		echo "m140523_100425_post_code does not support migration down.\n";
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