<?php

class m140417_130211_price_tenge extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_product', 'price_tenge', 'decimal(12,2) UNSIGNED');
	}

	public function down()
	{
		echo "m140417_130211_price_tenge does not support migration down.\n";
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