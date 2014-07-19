<?php

class m140624_061236_payment_params_table extends CDbMigration
{
	public function up()
	{
    $this->createTable('store_payment_params', array(
      'id' => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
      'payment_id' => 'int(11) UNSIGNED NOT NULL',
      'name' => 'string',
      'value' => 'string',
    ));
    $this->addForeignKey('fk_params_payment', 'store_payment_params', 'payment_id', 'store_payment', 'id', 'CASCADE');
    
    $this->addColumn('store_payment', 'action_url', 'string');
    
    $this->addColumn('store_currency', 'iso', 'VARCHAR(3)');
	}

	public function down()
	{
		echo "m140624_061236_payment_params_table does not support migration down.\n";
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