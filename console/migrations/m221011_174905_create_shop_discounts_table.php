<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop_discount}}`.
 */
class m221011_174905_create_shop_discounts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%shop_discounts}}', [
            'id' => $this->primaryKey(),
            'percent' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'from_date' => $this->integer(),
            'to_date' => $this->integer(),
            'active' => $this->boolean()->notNull(),
            'sort' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shop_discounts}}');
    }
}
