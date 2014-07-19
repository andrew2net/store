<?php

class m131016_104707_page_table extends CDbMigration {

  public function up() {
    $this->createTable('{{page}}', array(
      'id' => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
      'url' => 'string NOT NULL',
      'title' => 'string NOT NULL',
      'content' => 'text',
    ));
  }

  public function down() {
    echo "m131016_104707_pages_table does not support migration down.\n";
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