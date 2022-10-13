<?php

use yii\db\Migration;

/**
 * Class m221013_103523_add_shop_products_weight_field
 */
class m221013_103523_add_shop_products_weight_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_products}}', 'weight', $this->integer()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%shop_products}}', 'weight');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221013_103523_add_shop_products_weight_field cannot be reverted.\n";

        return false;
    }
    */
}
