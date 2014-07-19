<?php

class m140525_172916_product_weight_dimention extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_product', 'weight', 'DECIMAL(4,2)');
    $this->addColumn('store_product', 'length', 'INT(4)');
    $this->addColumn('store_product', 'width', 'INT(4)');
    $this->addColumn('store_product', 'height', 'INT(4)');
	}

	public function down()
	{
		echo "m140525_172916_product_weight_dimention does not support migration down.\n";
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