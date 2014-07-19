<?php

class m140715_170643_profile_price extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_customer_profile', 'price_id', 'INT(11) UNSIGNED');
	}

	public function down()
	{
		echo "m140715_170643_profile_price does not support migration down.\n";
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