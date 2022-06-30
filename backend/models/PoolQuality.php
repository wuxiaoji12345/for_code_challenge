<?php

namespace backend\models;

class PoolQuality extends \common\models\PoolQuality
{
    const STATUS_VALID = 1;
    const STATUS_INVALID = 2;

    public static $typeList = [
        1 => '温度',
        2 => 'pH',
        3 => 'ORP',
        4 => '余氯',
        5 => '浑浊度NTU',
    ];
}
