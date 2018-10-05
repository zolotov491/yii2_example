<?php

use yii\db\Migration;


/**
 * Class m180421_123240_dialog
 */
class m180421_123240_dialog extends Migration
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

        $this->createTable('{{%dialog}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->comment('User created dialog'),
            'with_id' => $this->integer()->comment('User dialog with'),
            'last_message_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        // Create user index
        $this->createIndex('idx-dialog-user_id', '{{%dialog}}', [
            'user_id'
        ], false);

        // Add user foreign key
        $this->addForeignKey('fk-dialog-user_id',
            '{{%dialog}}', ['user_id'],
            '{{%user}}', ['id']);

        // Create user with index
        $this->createIndex('idx-dialog-with_id', '{{%dialog}}', [
            'with_id'
        ], false);

        // Add user foreign key
        $this->addForeignKey('fk-dialog-with_id',
            '{{%dialog}}', ['with_id'],
            '{{%user}}', ['id']);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-dialog-user_id', '{{%dialog}}');
        $this->dropIndex('idx-dialog-user_id', '{{%dialog}}');
        $this->dropForeignKey('fk-dialog-with_id', '{{%dialog}}');
        $this->dropIndex('idx-dialog-with_id', '{{%dialog}}');
        $this->dropTable('{{%dialog}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180421_123240_dialog cannot be reverted.\n";

        return false;
    }
    */
}
