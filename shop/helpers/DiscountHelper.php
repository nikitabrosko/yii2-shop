<?php

namespace shop\helpers;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class DiscountHelper
{
    public static function statusList(): array
    {
        return [
            true => 'Yes',
            false => 'No',
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case true:
                $class = 'label label-success';
                break;
            case false:
                $class = 'label label-default';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', self::statusName($status), [
            'class' => $class,
        ]);
    }
}