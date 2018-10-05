<?php

use yii\db\Migration;

/**
 * Handles the creation of table `language`.
 */
class m171204_145703_create_language_proficiency_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('language_proficiency', [
            'id' => $this->primaryKey(),
            'name_ru' => $this->string(),
            'name_us' => $this->string(),
        ]);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('language_proficiency');
    }
}
