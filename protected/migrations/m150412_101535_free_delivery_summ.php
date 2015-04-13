<?php

class m150412_101535_free_delivery_summ extends CDbMigration
{
	public function up()
	{
      $this->addColumn('store_region_delivery', 'free_summ', 'DECIMAL(12,2) UNSIGNED');
	}

	public function down()
	{
		echo "m150412_101535_free_delivery_summ does not support migration down.\n";
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