<?php

class m140411_190821_profile_country extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_customer_profile', 'country_code', 'VARCHAR(2)');
    $this->addColumn('store_order', 'country_code', 'VARCHAR(2)');
	}

	public function down()
	{
		echo "m140411_190821_profile_country does not support migration down.\n";
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