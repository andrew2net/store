<?php

class m140604_085345_price_country extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_customer_profile', 'price_country', 'VARCHAR(2)');
	}

	public function down()
	{
		echo "m140604_085345_price_country does not support migration down.\n";
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