<?php

class m131201_155431_pament_table extends CDbMigration {

  public function safeUp() {
    $this->createTable('store_payment', array(
      'id' => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
      'name' => 'string',
      'description' => 'text',
      'type_id' => 'tinyint',
      'sign_name' => 'string',
      'sign_key' => 'string',
    ));

    $this->addForeignKey('order_payment', 'store_order', 'payment_id'
        , 'store_payment', 'id');
    Yii::app()->db->createCommand()->insert('store_payment', array(
      'name' => 'Наличными',
      'type_id' => 0,
      'description' => 'Оплата наличными при получении заказа у курьера или в нашем офисе',
    ));
    Yii::app()->db->createCommand()->insert('store_payment', array(
      'name' => 'Безналичный расчет',
      'type_id' => 1,
      'description' => 'Для оплаты покупки Вы будете перенаправлены на платежный шлюз для ввода реквизитов Вашей карты',
    ));
  }

  public function down() {
    echo "m131201_155431_pament_table does not support migration down.\n";
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