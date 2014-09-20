<?php

class m140919_153306_pay_currency_fk extends CDbMigration
{
	public function up()
	{
    $this->createIndex('currency_iso', 'store_currency', 'iso');
    $this->addForeignKey('fk_pay_currency', 'store_pay', 'currency_iso', 'store_currency', 'iso', 'RESTRICT', 'CASCADE');
	}

	public function down()
	{
		echo "m140919_153306_pay_currency_fk does not support migration down.\n";
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