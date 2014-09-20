<?php

class m140919_150941_pay_currency extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_pay', 'currency_iso', 'VARCHAR(3)');
	}

	public function down()
	{
		echo "m140919_150941_pay_currency does not support migration down.\n";
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