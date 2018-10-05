<?php

use yii\db\Migration;

/**
 * Handles adding created_at to table `order`.
 */
class m180302_090251_add_created_at_column_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'created_at', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'created_at');
    }
}
