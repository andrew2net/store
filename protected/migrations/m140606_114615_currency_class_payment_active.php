<?php

class m140606_114615_currency_class_payment_active extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_currency', 'class', 'string');
    $this->addColumn('store_payment', 'active', 'boolean');
	}

	public function down()
	{
		echo "m140606_114615_currency_class_payment_active does not support migration down.\n";
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