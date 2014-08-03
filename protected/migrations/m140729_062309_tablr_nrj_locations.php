<?php

class m140729_062309_tablr_nrj_locations extends CDbMigration
{
	public function up()
	{
    $this->createTable('nrj_locations', array(
      'id' => 'VARCHAR(7) NOT NULL PRIMARY KEY',
      'name' => 'VARCHAR (30) NOT NULL'
    ));
	}

	public function down()
	{
		echo "m140729_062309_tablr_nrj_locations does not support migration down.\n";
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