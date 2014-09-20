<?php

class m140919_134100_pay_amount extends CDbMigration
{
	public function up()
	{
    $this->renameColumn('store_pay', 'mnt_amount', 'amount');
	}

	public function down()
	{
		echo "m140919_134100_pay_amount does not support migration down.\n";
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