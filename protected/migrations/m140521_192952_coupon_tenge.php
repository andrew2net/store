<?php

class m140521_192952_coupon_tenge extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_coupon', 'value_tenge', 'int(5) UNSIGNED NOT NULL');
	}

	public function down()
	{
		echo "m140521_192952_coupon_tenge does not support migration down.\n";
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