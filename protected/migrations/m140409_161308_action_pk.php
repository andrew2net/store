<?php

class m140409_161308_action_pk extends CDbMigration
{
	public function up()
	{
    $this->addPrimaryKey('pk_action', 'store_product_action', 'action_id');
	}

	public function down()
	{
		echo "m140409_161308_action_pk does not support migration down.\n";
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