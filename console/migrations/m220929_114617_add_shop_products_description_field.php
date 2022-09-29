<?php

use yii\db\Migration;

/**
 * Class m220929_114617_add_shop_products_description_field
 */
class m220929_114617_add_shop_products_description_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_products}}', 'description', $this->text()->after('name'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%shop_products}}', 'description');
    }
}
