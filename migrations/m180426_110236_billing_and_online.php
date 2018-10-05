<?php

use yii\db\Migration;

/**
 * Class m180426_110236_billing_and_online
 */
class m180426_110236_billing_and_online extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'online', $this->integer()->defaultValue(0));
        $this->addColumn('{{%user}}', 'chat_subscription', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'online');
        $this->dropColumn('{{%user}}', 'chat_subscription');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180426_110236_billing_and_online cannot be reverted.\n";

        return false;
    }
    */
}
