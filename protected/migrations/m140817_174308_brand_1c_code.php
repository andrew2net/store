<?php

class m140817_174308_brand_1c_code extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_brand', 'code', 'VARCHAR(9)');
	}

	public function down()
	{
		echo "m140817_174308_brand_1c_code does not support migration down.\n";
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