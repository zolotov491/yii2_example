<?php

use yii\db\Migration;

/**
 * Handles adding date to table `profile`.
 */
class m180302_090009_add_date_column_to_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('profile', 'created_at', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('profile', 'created_at');
    }
}
