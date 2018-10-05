<?php

use yii\db\Migration;

/**
 * Handles the creation of table `reviews`.
 */
class m171102_091643_create_reviews_table extends Migration
{

    public function up()
    {
        $this->createTable('{{%reviews}}', [
            'id' => $this->primaryKey(),
            'profile_id' => $this->integer()->notNull(),
            'image' => $this->string()->defaultValue(null),
            'message' => $this->text(),
            'active' => $this->boolean()->defaultValue(0)
        ]);

        $this->createIndex('idx-reviews-profile_id', '{{%reviews}}', 'profile_id');
        $this->addForeignKey('fk-reviews-profile_id', '{{%reviews}}', 'profile_id', '{{%profile}}', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%reviews}}');
    }
}
