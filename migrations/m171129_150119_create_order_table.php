<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m171129_150119_create_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'profile_id' => $this->integer()->notNull(),
            'service_id' => $this->integer()->notNull(),
            'payment_status' => $this->string(),
            'active' => $this->boolean()->defaultValue(1),
        ]);

        $this->createIndex('idx-order-profile_id', '{{%order}}', 'profile_id');
        $this->addForeignKey('fk-order-profile_id', '{{%order}}', 'profile_id', '{{%profile}}', 'id', 'CASCADE');

        $this->createIndex('idx-order-service_id', '{{%order}}', 'service_id');
        $this->addForeignKey('fk-order-service_id', '{{%order}}', 'service_id', '{{%service}}', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order');
    }
}
