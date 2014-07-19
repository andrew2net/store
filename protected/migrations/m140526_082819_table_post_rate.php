<?php

class m140526_082819_table_post_rate extends CDbMigration
{
	public function up()
	{
    $this->createTable('store_delivery_rate', array(
      'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
      'region_id' => 'int(11) UNSIGNED NOT NULL',
      'delivery_id' => 'int(11) UNSIGNED NOT NULL',
      'weight' => 'DECIMAL(4,2)',
      'price' => 'DECIMAL(10,2)',
    ));
    
    $this->addForeignKey('rate_region', 'store_delivery_rate', 'region_id',
        'store_region', 'id', 'CASCADE', 'CASCADE');
    $this->addForeignKey('rate_delivery', 'store_delivery_rate', 'delivery_id',
        'store_delivery', 'id', 'CASCADE', 'CASCADE');
	}

	public function down()
	{
		echo "m140526_082819_table_post_rate does not support migration down.\n";
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