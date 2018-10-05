<?php

use yii\db\Migration;

/**
 * Handles adding englishversion to table `profile`.
 */
class m180221_072356_add_englishversion_column_to_profile_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('profile', 'hobbies_and_interests_en', $this->string(500));
        $this->addColumn('profile', 'about_me_en', $this->string(500));
        $this->addColumn('profile', 'requirements_from_partner_en', $this->string(500));
        $this->addColumn('profile', 'profession_en', $this->string());
        $this->addColumn('profile', 'specialty_en', $this->string());
        $this->addColumn('profile', 'name_en', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('profile', 'hobbies_and_interests_en');
        $this->dropColumn('profile', 'about_me_en');
        $this->dropColumn('profile', 'requirements_from_partner_en');
        $this->dropColumn('profile', 'profession_en');
        $this->dropColumn('profile', 'specialty_en');
        $this->dropColumn('profile', 'name_en');
    }
}
