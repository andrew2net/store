<?php

class m140529_153915_table_region_delivery extends CDbMigration
{
	public function up()
	{
    $this->createTable('store_region_delivery', array(
      'region_id' => 'INT(11) UNSIGNED NOT NULL',
      'delivery_id' => 'INT(11) UNSIGNED NOT NULL',
      'zone' => 'INT(3) UNSIGNED',
      'weight_rate' => 'DECIMAL(10,2)',
    ));
    
   $this->addPrimaryKey('region_delivery_pk', 'store_region_delivery', 'region_id, delivery_id');
   $this->addForeignKey('delivery_fk', 'store_region_delivery', 'delivery_id', 'store_delivery', 'id', 'CASCADE', 'CASCADE');
   $this->addForeignKey('region_fk', 'store_region_delivery', 'region_id', 'store_region', 'id', 'CASCADE', 'CASCADE');
    
    $this->addColumn('store_delivery', 'max_weight', 'DECIMAL(4,2)');
    $this->addColumn('store_delivery', 'size_method_id', 'INT(1) UNSIGNED');
    $this->addColumn('store_delivery', 'size_summ', 'INT(4) UNSIGNED');
	}

	public function down()
	{
		echo "m140529_153915_table_region_delivery does not support migration down.\n";
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