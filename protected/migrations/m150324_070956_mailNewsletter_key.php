<?php

class m150324_070956_mailNewsletter_key extends CDbMigration
{
	public function up()
	{
      $this->addPrimaryKey('pk_mail_newsletter', 'store_mail_newsletter', 'mail_id, newsletter_id');
	}

	public function down()
	{
		echo "m150324_070956_mailNewsletter_key does not support migration down.\n";
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