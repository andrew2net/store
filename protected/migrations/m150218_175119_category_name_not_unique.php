<?php

class m150218_175119_category_name_not_unique extends CDbMigration
{
	public function up()
	{
      $this->dropIndex('name_UNIQUE', 'store_category');
	}

	public function down()
	{
		echo "m150218_175119_category_name_not_unique does not support migration down.\n";
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