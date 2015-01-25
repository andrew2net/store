<?php

class m150124_125704_add_order_insurance extends CDbMigration
{
	public function up()
	{
      $this->addColumn('store_order', 'insurance', 'boolean');
	}

	public function down()
	{
		echo "m150124_125704_add_order_insurance does not support migration down.\n";
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