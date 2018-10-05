<?php

use yii\db\Migration;

/**
 * Class m180421_123302_message
 */
class m180421_123302_message extends Migration
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

        $this->createTable('{{%message}}', [
            'id' => $this->primaryKey(),
            'sender_id' => $this->integer()->comment('Sender user id'),
            'recipient_id' => $this->integer()->comment('Recipient user id'),
            'dialog_id' => $this->integer(),
            'content' => $this->binary(),
            'view' => $this->smallInteger()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        // Create sender index
        $this->createIndex('idx-message-sender_id', '{{%message}}', [
            'sender_id'
        ], false);

        // Add sender foreign key
        $this->addForeignKey('fk-message-sender_id',
            '{{%message}}', ['sender_id'],
            '{{%user}}', ['id']);
        
        // Create recipient index
        $this->createIndex('idx-message-recipient_id', '{{%message}}', [
            'recipient_id'
        ], false);

        // Add recipient foreign key
        $this->addForeignKey('fk-message-recipient_id',
            '{{%message}}', ['recipient_id'],
            '{{%user}}', ['id']);
        
        // Create dialog index
        $this->createIndex('idx-message-dialog_id', '{{%message}}', [
            'dialog_id'
        ], false);

        // Add dialog foreign key
        $this->addForeignKey('fk-message-dialog_id',
            '{{%message}}', ['dialog_id'],
            '{{%dialog}}', ['id']);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-message-dialog_id', '{{%message}}');
        $this->dropIndex('idx-message-dialog_id', '{{%message}}');
        $this->dropForeignKey('fk-message-recipient_id', '{{%message}}');
        $this->dropIndex('idx-message-recipient_id', '{{%message}}');
        $this->dropForeignKey('fk-message-sender_id', '{{%message}}');
        $this->dropIndex('idx-message-sender_id', '{{%message}}');
        $this->dropTable('{{%message}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180421_123302_message cannot be reverted.\n";

        return false;
    }
    */
}
