<?php

use yii\db\Migration;

/**
 * Class m221013_103534_add_shop_products_quantity_field
 */
class m221013_103534_add_shop_products_quantity_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_products}}', 'quantity', $this->integer()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%shop_products}}', 'quantity');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221013_103534_add_shop_products_quantity_field cannot be reverted.\n";

        return false;
    }
    */
}
