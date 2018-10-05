<?php

use yii\db\Migration;

/**
 * Handles the creation of table `contact`.
 */
class m171129_145857_create_contact_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('contact', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50),
            'email' => $this->string(50),
            'message' => $this->text(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('contact');
    }
}
