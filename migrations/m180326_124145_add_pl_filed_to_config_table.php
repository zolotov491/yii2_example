<?php

use yii\db\Migration;

/**
 * Class m180326_124145_add_pl_filed_to_config_table
 */
class m180326_124145_add_pl_filed_to_config_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('config', 'phone_pl', $this->string());
        $this->update('config', ['phone_pl' => '+48 53 007-19-40'],['id' => 1]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('config', 'phone_pl');
    }

}
