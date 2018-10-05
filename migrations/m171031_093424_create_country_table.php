<?php

use yii\db\Migration;

/**
 * Handles the creation of table `country`.
 */
class m171031_093424_create_country_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('country', [
            'id' => $this->primaryKey(),
            'name_ru' => $this->string()->notNull(),
            'name_us' => $this->string()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('country');
    }
}
