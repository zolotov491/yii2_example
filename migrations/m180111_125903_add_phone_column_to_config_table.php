<?php

use yii\db\Migration;

/**
 * Handles adding phone to table `config`.
 */
class m180111_125903_add_phone_column_to_config_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('config', 'phone_ua', $this->string());
        $this->addColumn('config', 'phone_us', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('config', 'phone_ua');
        $this->dropColumn('config', 'phone_us');
    }
}
