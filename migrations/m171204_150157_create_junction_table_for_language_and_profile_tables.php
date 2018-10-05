<?php

use yii\db\Migration;

/**
 * Handles the creation of table `language_profile`.
 * Has foreign keys to the tables:
 *
 * - `language`
 * - `profile`
 */
class m171204_150157_create_junction_table_for_language_and_profile_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('language_profile', [
            'language_id' => $this->integer(),
            'profile_id' => $this->integer(),
            'language_proficiency_id' => $this->integer(),
            'PRIMARY KEY(language_id, profile_id)',
        ]);

        $this->createIndex(
            'idx-language_profile-language_proficiency_id',
            'language_profile',
            'language_proficiency_id'
        );
        $this->addForeignKey(
            'fk-language_profile-language_proficiency_id',
            'language_profile',
            'language_proficiency_id',
            'language_proficiency',
            'id',
            'CASCADE'
        );

        // creates index for column `language_id`
        $this->createIndex(
            'idx-language_profile-language_id',
            'language_profile',
            'language_id'
        );

        // add foreign key for table `language`
        $this->addForeignKey(
            'fk-language_profile-language_id',
            'language_profile',
            'language_id',
            'language',
            'id',
            'CASCADE'
        );

        // creates index for column `profile_id`
        $this->createIndex(
            'idx-language_profile-profile_id',
            'language_profile',
            'profile_id'
        );

        // add foreign key for table `profile`
        $this->addForeignKey(
            'fk-language_profile-profile_id',
            'language_profile',
            'profile_id',
            'profile',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {


        $this->dropForeignKey(
            'fk-language_profile-language_proficiency_id',
            'language_profile'
        );

        $this->dropIndex(
            'idx-language_profile-language_proficiency_id',
            'language_profile'
        );


        // drops foreign key for table `language`
        $this->dropForeignKey(
            'fk-language_profile-language_id',
            'language_profile'
        );

        // drops index for column `language_id`
        $this->dropIndex(
            'idx-language_profile-language_id',
            'language_profile'
        );

        // drops foreign key for table `profile`
        $this->dropForeignKey(
            'fk-language_profile-profile_id',
            'language_profile'
        );

        // drops index for column `profile_id`
        $this->dropIndex(
            'idx-language_profile-profile_id',
            'language_profile'
        );

        $this->dropTable('language_profile');
    }
}
