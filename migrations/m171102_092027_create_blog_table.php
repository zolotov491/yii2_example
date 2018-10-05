<?php

use yii\db\Migration;

/**
 * Handles the creation of table `blog`.
 */
class m171102_092027_create_blog_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%blog}}', [
            'id' => $this->primaryKey(),
            'title_ru' => $this->string(255)->notNull(),
            'title_us' => $this->string(255)->notNull(),
            'short_description_ru' => $this->text(),
            'short_description_us' => $this->text(),
            'text_ru' => $this->text(),
            'text_us' => $this->text(),
            'img' => $this->string(),
            'active' => $this->boolean()->defaultValue(1),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%blog}}');
    }
}
