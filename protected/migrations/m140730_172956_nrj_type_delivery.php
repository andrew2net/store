<?php

class m140730_172956_nrj_type_delivery extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_delivery', 'transport_type_id', 'int(1)');
	}

	public function down()
	{
		echo "m140730_172956_nrj_type_delivery does not support migration down.\n";
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