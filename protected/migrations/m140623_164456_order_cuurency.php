<?php

class m140623_164456_order_cuurency extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_order', 'currency_code', 'VARCHAR(3)');
    $this->addForeignKey('fk_order_currency', 'store_order', 'currency_code', 'store_currency', 'code', 'CASCADE', 'CASCADE');
	}

	public function down()
	{
		echo "m140623_164456_order_cuurency does not support migration down.\n";
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