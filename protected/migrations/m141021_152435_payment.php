<?php

class m141021_152435_payment extends CDbMigration
{
	public function up()
	{
    $this->renameColumn('store_payment', 'sign_name', 'merchant_id');
    $this->dropColumn('store_payment', 'action_url');
    $this->addColumn('store_payment', 'currency_code', 'VARCHAR(3)');
    $this->dropTable('store_payment_params');
    $this->addColumn('store_pay', 'status_id', 'int(2)');
	}

	public function down()
	{
		echo "m141021_152435_payment does not support migration down.\n";
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