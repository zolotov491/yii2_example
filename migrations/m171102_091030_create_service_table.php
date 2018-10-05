<?php

use yii\db\Migration;

/**
 * Handles the creation of table `service`.
 */
class m171102_091030_create_service_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%service}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'title_ru' => $this->string(255)->notNull(),
            'title_us' => $this->string(255)->notNull(),
            'price' => $this->decimal(),
            'description_ru' => $this->text(),
            'description_us' => $this->text(),
            'img' => $this->string(),
            'active' => $this->boolean()->defaultValue(1),
            'matching' => $this->boolean()->defaultValue(1),
        ]);

        $this->createIndex('idx-service-category_id', '{{%service}}', 'category_id');
        $this->addForeignKey('fk-service-category_id', '{{%service}}', 'category_id', '{{%category}}', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%service}}');
    }
}
