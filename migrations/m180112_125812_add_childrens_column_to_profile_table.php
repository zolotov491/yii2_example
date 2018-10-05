<?php

use yii\db\Migration;

/**
 * Handles adding childrens to table `profile`.
 */
class m180112_125812_add_childrens_column_to_profile_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('profile', 'count_children', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('profile', 'count_children');
    }
}
