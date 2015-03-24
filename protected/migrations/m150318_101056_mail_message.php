<?php

class m150318_101056_mail_message extends CDbMigration {

  public function up() {
    $this->createTable('store_newsletter', [
      'id' => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
      'subject' => 'string',
      'is_sent' => 'boolean',
      'time' => 'timestamp',
    ]);
    
    $this->createTable('store_newsletter_block', [
      'id' => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
      'newsletter_id' => 'int(11) UNSIGNED NOT NULL',
      'image' => 'string',
      'text' => 'text'
    ]);
    
    $this->addForeignKey('fk_newsletter_block', 'store_newsletter_block', 'newsletter_id', 'store_newsletter', 'id', 'RESTRICT');
  }

  public function down() {
    echo "m150318_101056_mail_message does not support migration down.\n";
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
