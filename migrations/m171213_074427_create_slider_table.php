<?php

use yii\db\Migration;

/**
 * Handles the creation of table `slider`.
 */
class m171213_074427_create_slider_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('slider', [
            'id' => $this->primaryKey(),
            'active' => $this->boolean()->defaultValue(1),
            'image' => $this->string()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('slider');
    }
}
