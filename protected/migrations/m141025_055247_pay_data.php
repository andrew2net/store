<?php

class m141025_055247_pay_data extends CDbMigration
{
	public function up()
	{
    $this->dropColumn('store_pay', 'pay_system_id');
    $this->dropColumn('store_pay', 'corr_acc');
    
    $this->createTable('store_pay_data', array(
      'id' => 'int(11) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT',
      'pay_id' => 'int(11) unsigned NOT NULL',
      'name' => 'string',
      'value' => 'string',
    ));
    
    $this->addForeignKey('fk_pay_data', 'store_pay_data', 'pay_id', 'store_pay', 'id', 'CASCADE');
	}

	public function down()
	{
		echo "m141025_055247_pay_data does not support migration down.\n";
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