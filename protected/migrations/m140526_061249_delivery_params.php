<?php

class m140526_061249_delivery_params extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_delivery', 'length', 'INT(4)');
    $this->addColumn('store_delivery', 'width', 'INT(4)');
    $this->addColumn('store_delivery', 'height', 'INT(4)');
    $this->addColumn('store_delivery', 'oversize', 'INT(3)');
    $this->addColumn('store_delivery', 'insurance', 'INT(2)');
	}

	public function down()
	{
		echo "m140526_061249_delivery_params does not support migration down.\n";
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