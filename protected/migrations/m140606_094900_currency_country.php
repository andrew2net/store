<?php

class m140606_094900_currency_country extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_currency', 'country_code', 'VARCHAR(2) NOT NULL');
	}

	public function down()
	{
		echo "m140606_094900_currency_country does not support migration down.\n";
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