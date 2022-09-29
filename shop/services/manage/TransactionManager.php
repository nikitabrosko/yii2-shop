<?php

namespace shop\services\manage;

class TransactionManager
{
    public function wrap(callable $function)
    {
        \Yii::$app->db->transaction($function);
    }
}