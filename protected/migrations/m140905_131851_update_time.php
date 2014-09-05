<?php

class m140905_131851_update_time extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_brand', 'update_time', 'timestamp');
    $this->addColumn('store_category', 'update_time', 'timestamp');
    $this->addColumn('store_product', 'update_time', 'timestamp');
    $this->addColumn('store_top10', 'update_time', 'timestamp');
    $this->addColumn('store_discount', 'update_time', 'timestamp');
    $this->addColumn('{{page}}', 'update_time', 'timestamp');
	}

	public function down()
	{
		echo "m140905_131851_update_time does not support migration down.\n";
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