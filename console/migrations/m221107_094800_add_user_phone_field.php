<?php

use yii\db\Migration;

/**
 * Class m221107_094800_add_user_phone_field
 */
class m221107_094800_add_user_phone_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'phone', $this->string()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'phone');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221107_094800_add_user_phone_field cannot be reverted.\n";

        return false;
    }
    */
}
