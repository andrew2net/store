<?php

class m140528_133030_table_currency extends CDbMigration
{
	public function up()
	{
    $this->createTable('store_currency', array(
      'code' => 'VARCHAR(3) NOT NULL PRIMARY KEY',
      'name' => 'VARCHAR(20)',
      'short' => 'VARCHAR(5)'
    ));
    
    $this->addColumn('store_delivery', 'active', 'boolean');
    $this->addColumn('store_delivery', 'currency_code', 'VARCHAR(3)');
    $this->addForeignKey('delivery_currency', 'store_delivery', 'currency_code', 'store_currency', 'code', 'CASCADE', 'CASCADE');
	}

	public function down()
	{
		echo "m140528_133030_table_currency does not support migration down.\n";
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