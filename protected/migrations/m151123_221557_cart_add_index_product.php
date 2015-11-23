<?php

class m151123_221557_cart_add_index_product extends CDbMigration
{
	public function up()
	{
		$this->dropIndex('mail_uid', 'store_mail');
	}

	public function down()
	{
		echo "m151123_221557_cart_add_index_product does not support migration down.\n";
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