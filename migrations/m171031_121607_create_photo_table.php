<?php

use yii\db\Migration;

/**
 * Handles the creation of table `photo`.
 */
class m171031_121607_create_photo_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%photo}}', [
            'id' => $this->primaryKey(),
            'profile_id' => $this->integer()->notNull(),
            'name' => $this->string(),
        ]);

        $this->createIndex('idx-photo-profile_id', '{{%photo}}', 'profile_id');
        $this->addForeignKey('fk-photo-profile_id', '{{%photo}}', 'profile_id', '{{%profile}}', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%photo}}');
    }
}
