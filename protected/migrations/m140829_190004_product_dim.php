<?php

class m140829_190004_product_dim extends CDbMigration
{
	public function up()
	{
    $this->alterColumn('store_product', 'length', 'decimal(4,1)');
    $this->alterColumn('store_product', 'width', 'decimal(4,1)');
    $this->alterColumn('store_product', 'height', 'decimal(4,1)');
	}

	public function down()
	{
		echo "m140829_190004_product_dim does not support migration down.\n";
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