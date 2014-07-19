<?php

class m140601_055711_table_currency_rate extends CDbMigration
{
	public function up()
	{
    $this->createTable('store_currency_rate', array(
      'date' => 'date NOT NULL',
      'from' => 'VARCHAR(3) NOT NULL',
      'from_quantity' => 'INT(3) NOT NULL',
      'to' => 'VARCHAR(3) NOT NULL',
      'to_quantity' => 'INT(3) NOT NULL',
      'rate' => 'float',
    ));
    $this->addPrimaryKey('pk_currency_rate', 'store_currency_rate', 'date, from, to');
    $this->addForeignKey('fk_currency_from', 'store_currency_rate', 'from', 'store_currency', 'code', 'CASCADE', 'CASCADE');
    $this->addForeignKey('fk_currency_to', 'store_currency_rate', 'to', 'store_currency', 'code', 'CASCADE', 'CASCADE');
	}

	public function down()
	{
		echo "m140601_055711_table_currency_rate does not support migration down.\n";
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