<?php

class m140919_135253_pay_columns_alter extends CDbMigration
{
	public function up()
	{
    $this->renameColumn('store_pay', 'mnt_operation_id', 'operation_id');
    $this->renameColumn('store_pay', 'mnt_corr_acc', 'corr_acc');
    $this->alterColumn('store_pay', 'pay_system_id', 'VARCHAR(30)');
    
	}

	public function down()
	{
		echo "m140919_135253_pay_columns_alter does not support migration down.\n";
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