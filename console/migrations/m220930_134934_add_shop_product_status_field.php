<?php

use yii\db\Migration;

/**
 * Class m220930_134934_add_shop_product_status_field
 */
class m220930_134934_add_shop_product_status_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_products}}', 'status', $this->smallInteger()->notNull());
        $this->update('{{%shop_products}}', ['status' => 1]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%shop_products}}', 'status');
    }
}
