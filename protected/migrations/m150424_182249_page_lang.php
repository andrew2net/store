<?php

class m150424_182249_page_lang extends CDbMigration
{
	public function up()
	{
      $this->addColumn('{{page}}', 'lang', 'VARCHAR(2)');
      $lang = Yii::app()->params['country'] ? Yii::app()->params['country'] : 'KZ';
      $this->update('{{page}}', ['lang' => $lang]);
	}

	public function down()
	{
		echo "m150424_182249_page_lang does not support migration down.\n";
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