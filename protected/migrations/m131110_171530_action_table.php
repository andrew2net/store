<?php

class m131110_171530_action_table extends CDbMigration {

  public function safeUp() {
    $this->createTable('store_action', array(
      'id' => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
      'type_id' => 'tinyint UNSIGNED NOT NULL',
      'name' => 'varchar(30)',
      'img' => 'string',
      'url' => 'string',
      'show' => 'boolean',
    ));
  }

  public function down() {
    echo "m131110_171530_action_table does not support migration down.\n";
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