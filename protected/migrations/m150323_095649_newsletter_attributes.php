<?php

class m150323_095649_newsletter_attributes extends CDbMigration
{
	public function up()
	{
      $this->addColumn('store_newsletter', 'send_price', 'boolean');
      
      $this->update('{{profiles}}', ['newsletter' => '1']);
      
      $this->createTable('store_mail_newsletter', [
        'mail_id' => 'int(11) UNSIGNED NOT NULL',
        'newsletter_id' => 'int(11) UNSIGNED NOT NULL',
      ]);
      $this->addForeignKey('fk_mail_newsletter', 'store_mail_newsletter', 'mail_id', 'store_mail', 'id', 'CASCADE', 'RESTRICT');
      $this->addForeignKey('fk_newsletter_mail', 'store_mail_newsletter', 'newsletter_id', 'store_newsletter', 'id', 'CASCADE', 'RESTRICT');
	}

	public function down()
	{
		echo "m150323_095649_newsletter_attributes does not support migration down.\n";
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