<?php

class m150411_103302_news extends CDbMigration
{
	public function up()
	{
      $this->createTable('store_news', [
        'id' => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
        'title' => 'string',
        'date' => 'date',
        'text' => 'text',
        'active' => 'boolean',
      ]);
	}

	public function down()
	{
		echo "m150411_103302_news does not support migration down.\n";
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