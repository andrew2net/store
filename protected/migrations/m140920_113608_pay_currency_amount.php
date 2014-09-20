<?php

class m140920_113608_pay_currency_amount extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_pay', 'currency_amount', 'DECIMAL(12,2) UNSIGNED');
	}

	public function down()
	{
		echo "m140920_113608_pay_currency_amount does not support migration down.\n";
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