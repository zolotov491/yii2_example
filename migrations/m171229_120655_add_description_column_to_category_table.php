<?php

use yii\db\Migration;

/**
 * Handles adding description to table `category`.
 */
class m171229_120655_add_description_column_to_category_table extends Migration
{

    public function up()
    {
        $this->addColumn('category', 'description_ru', $this->text());
        $this->addColumn('category', 'description_us', $this->text());
    }

    public function down()
    {
        $this->dropColumn('category', 'description_ru');
        $this->dropColumn('category', 'description_us');
    }
}
