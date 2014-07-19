<?php

class m131026_111402_brand_table extends CDbMigration
{
	public function up()
	{
    $this->createTable('store_brand', array(
      'id'=>'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
      'name'=>'string NOT NULL',
      'img' => 'string'
      )
        );
	}

	public function down()
	{
		echo "m131026_111402_brand_table does not support migration down.\n";
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