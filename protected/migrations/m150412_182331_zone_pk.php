<?php

class m150412_182331_zone_pk extends CDbMigration {

  public function up() {
//      $this->alterColumn('store_zone', 'id', 'INT(11) UNSIGNED NOT NULL');
    $this->dropPrimaryKey('country_post', 'store_zone');
//      $this->dropPrimaryKey('pk_zone', 'store_zone');
//      $this->addPrimaryKey('pk_zone', 'store_zone', 'id');
    $this->addColumn('store_zone', 'id', 'INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT');
//      $this->alterColumn('store_zone', 'id', 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT');
    $this->alterColumn('store_zone', 'post_code', 'VARCHAR(20)');
    $this->insert('store_zone', ['type_id' => 3, 'country_code' => 'RU', 'post_code' => "^(?=63)\d{6}$", 'zone' => 1]);
    $this->insert('store_zone', ['type_id' => 3, 'country_code' => 'RU', 'post_code' => "^(?!63)\d{6}$", 'zone' => 2]);
    $this->insert('store_zone', ['type_id' => 4, 'country_code' => 'RU', 'post_code' => "^(?=63)\d{6}$", 'zone' => 1]);
    $this->insert('store_zone', ['type_id' => 4, 'country_code' => 'RU', 'post_code' => "^(?!63)\d{6}$", 'zone' => 2]);
//      $this->addForeignKey('fk_zone_delivery', 'store_zone', 'type_id', 'store_delivery', 'id');
  }

  public function down() {
    echo "m150412_182331_zone_pk does not support migration down.\n";
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
