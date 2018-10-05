<?php

use yii\db\Migration;

/**
 * Handles adding position to table `photo`.
 */
class m180306_083117_add_position_column_to_photo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('photo', 'position', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('photo', 'position');
    }
}
