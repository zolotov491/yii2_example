<?php

use yii\db\Migration;

/**
 * Handles the creation of table `messenger`.
 */
class m180111_125441_create_messenger_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('messenger', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->defaultValue(null),
            'link' => $this->string(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('messenger');
    }
}
