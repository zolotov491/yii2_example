<?php

use yii\db\Migration;

/**
 * Class m180427_075823_ban
 */
class m180427_075823_ban extends Migration
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

        // User ban all chats
        $this->addColumn('{{%user}}', 'chat_banned', $this->smallInteger()->defaultValue(0));
        
        // User to user ban tables
        $this->createTable('{{%chat_ban}}', [
            'id' => $this->primaryKey(),
            'who' => $this->integer()->comment('Who banned'),
            'whom' => $this->integer()->comment('Banned by whom'),
        ], $tableOptions);
        
        // Add indexes
        $this->createIndex('idx-chat-ban-who', '{{%chat_ban}}', 'who');
        $this->createIndex('idx-chat-ban-whom', '{{%chat_ban}}', 'whom');
        
        // Chat settings
        $this->createTable('{{%chat_settings}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'value' => $this->binary(),
        ], $tableOptions);
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%chat_settings}}');
        $this->dropIndex('idx-chat-ban-who', '{{%chat_ban}}');
        $this->dropIndex('idx-chat-ban-whom', '{{%chat_ban}}');
        $this->dropTable('{{%chat_ban}}');
        $this->dropColumn('{{%user}}', 'chat_banned');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180427_075823_ban cannot be reverted.\n";

        return false;
    }
    */
}
