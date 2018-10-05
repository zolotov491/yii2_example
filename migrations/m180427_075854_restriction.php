<?php

use yii\db\Migration;

/**
 * Class m180427_075854_restriction
 */
class m180427_075854_restriction extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        // Create tables to set restricted regular expressions
        $this->createTable('{{%chat_restriction}}', [
            'id' => $this->primaryKey(),
            'description' => $this->string(),
            'regex' => $this->binary(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%chat_restriction}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180427_075854_restriction cannot be reverted.\n";

        return false;
    }
    */
}
