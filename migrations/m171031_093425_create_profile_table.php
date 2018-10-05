<?php

use yii\db\Migration;

/**
 * Handles the creation of table `profile`.
 */
class m171031_093425_create_profile_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%profile}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'surname' => $this->string(),
            'user_id' => $this->integer()->notNull(),
            'country_id' => $this->integer()->notNull(),
            'gender' => $this->string(),
            'day' => $this->integer(2)->notNull(),
            'month' => $this->integer(2)->notNull(),
            'year' => $this->integer(4)->notNull(),
            'phone' => $this->bigInteger(),
            'skype' => $this->string()->defaultValue(null),
            'viber' => $this->string()->defaultValue(null),
            'social_network_address' => $this->string(),
            'how_did_find_out_about_us' => $this->string(),
            'hobbies_and_interests' => $this->string()->defaultValue(null),
            'growth' => $this->integer()->defaultValue(null),
            'growth_ft' => $this->integer()->defaultValue(null),
            'weight' => $this->integer()->defaultValue(null),
            'weight_lbs' => $this->integer()->defaultValue(null),
            'hair_color' => $this->string()->defaultValue(null),
            'eye_color' => $this->string()->defaultValue(null),
            'education' => $this->string()->defaultValue(null),
            'specialty' => $this->string()->defaultValue(null),
            'profession' => $this->string()->defaultValue(null),
            'marital_status' => $this->string()->defaultValue(null),
            'children' => $this->string()->defaultValue(null),
            'want_children' => $this->string()->defaultValue(null),
            'alcohol_consumption' => $this->string()->defaultValue(null),
            'smoking' => $this->string()->defaultValue(null),
            'about_me' => $this->string(500)->defaultValue(null),
            'requirements_from_partner' => $this->string(500)->defaultValue(null),
            'age' => $this->integer()->defaultValue(null),
            'inhabited_locality' => $this->string()
        ]);

        $this->createIndex('idx-profile-user_id', '{{%profile}}', 'user_id');
        $this->addForeignKey('fk-profile-user_id', '{{%profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE');

        $this->createIndex('idx-profile-country_id', '{{%profile}}', 'country_id');
        $this->addForeignKey('fk-profile-country_id', '{{%profile}}', 'country_id', '{{%country}}', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%profile}}');
    }
}
