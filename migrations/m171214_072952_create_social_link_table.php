<?php

use yii\db\Migration;

/**
 * Handles the creation of table `social_link`.
 */
class m171214_072952_create_social_link_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('social_link', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->defaultValue(null),
            'css_style' => $this->string(),
            'link' => $this->string(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('social_link');
    }
}
