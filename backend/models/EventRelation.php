<?php

namespace backend\models;

class EventRelation extends \common\models\EventRelation
{
    const STATUS_VALID = 1;
    const STATUS_INVALID = 2;

    const STATE_PAID = 1;
    const STATE_UNPAID = 2;
    const STATE_REFUND = 3;

    public static $stateList = [
        self::STATE_PAID => '已支付',
        self::STATE_UNPAID => '未支付',
        self::STATE_REFUND => '已退款',
    ];
}
