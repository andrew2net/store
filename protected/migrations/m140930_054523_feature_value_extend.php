<?php

class m140930_054523_feature_value_extend extends CDbMigration
{
	public function up()
	{
    $this->alterColumn('store_feature_value', 'value', 'varchar(30)');
	}

	public function down()
	{
		echo "m140930_054523_feature_value_extend does not support migration down.\n";
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