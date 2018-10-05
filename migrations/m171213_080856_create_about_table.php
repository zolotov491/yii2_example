<?php

use yii\db\Migration;

/**
 * Handles the creation of table `about`.
 */
class m171213_080856_create_about_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('about', [
            'id' => $this->primaryKey(),
            'title_ru' => $this->string(255)->notNull(),
            'title_us' => $this->string(255)->notNull(),
            'text_ru' => $this->text(),
            'text_us' => $this->text(),
            'image' => $this->string(),
            'active' => $this->boolean()->defaultValue(1),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('about');
    }
}
