<?php

use yii\db\Migration;

/**
 * Handles the creation of table `rules`.
 */
class m171213_081213_create_rules_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('rules', [
            'id' => $this->primaryKey(),
            'text_ru' => $this->text(),
            'text_us' => $this->text(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('rules');
    }
}
