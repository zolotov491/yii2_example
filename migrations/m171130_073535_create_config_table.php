<?php

use yii\db\Migration;

/**
 * Handles the creation of table `config`.
 */
class m171130_073535_create_config_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        /** TODO create setup form in admin */
        $this->createTable('config', [
            'id' => $this->primaryKey(),
            'title_ru' => $this->string(),
            'title_us' => $this->string(),
            /**  Gallery default value ON */
            'gallery' => $this->boolean()->defaultValue(1),
            'profile' => $this->boolean()->defaultValue(0),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('config');
    }
}
