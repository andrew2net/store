<?php

class m150117_114100_delivery_limit extends CDbMigration
{
	public function up()
	{
    $this->alterColumn('store_delivery', 'size_summ', 'NUMERIC(4,1) UNSIGNED');
	}

	public function down()
	{
		echo "m150117_114100_delivery_limit does not support migration down.\n";
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