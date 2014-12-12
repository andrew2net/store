<?php

class m141212_122936_product_descript extends CDbMigration
{
	public function up()
	{
    $this->addColumn('store_product', 'seo', 'text');
	}

	public function down()
	{
		echo "m141212_122936_product_descript does not support migration down.\n";
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