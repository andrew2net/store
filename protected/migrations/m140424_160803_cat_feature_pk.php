<?php

class m140424_160803_cat_feature_pk extends CDbMigration
{
	public function up()
	{
    $this->addPrimaryKey('category_feature', 'store_category_feature', 'category_id, feature_id');
	}

	public function down()
	{
		echo "m140424_160803_cat_feature_pk does not support migration down.\n";
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