<?php

class m140823_101209_store_mail extends CDbMigration
{
	public function up()
	{
    $this->createTable('store_mail', array(
      'id' => 'int(11) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT',
      'uid' => 'int(11) NOT NULL',
      'type_id' => 'int(1) unsigned NOT NULL',
      'status_id' => 'int(1) unsigned NOT NULL',
    ));
    
    $this->createTable('store_mail_order', array(
      'mail_id' => 'int(11) unsigned NOT NULL',
      'order_id' => 'int(11) unsigned NOT NULL',
    ));
    
    $this->addForeignKey('fk_mail_order', 'store_mail_order', 'mail_id', 'store_mail', 'id', 'CASCADE');
    $this->addForeignKey('fk_order_mail', 'store_mail_order', 'order_id', 'store_order', 'id');
	}

	public function down()
	{
		echo "m140823_101209_store_mail does not support migration down.\n";
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