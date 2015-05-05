<?php

class m150505_060345_local_remainder extends CDbMigration
{
	public function up()
	{
      $this->addColumn('store_product', 'remainder_KZ', 'smallint UNSIGNED');
      $this->renameColumn('store_product', 'remainder', 'remainder_RU');
      $this->execute('UPDATE store_product SET remainder_KZ=remainder_RU');
	}

	public function down()
	{
		echo "m150505_060345_local_remainder does not support migration down.\n";
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