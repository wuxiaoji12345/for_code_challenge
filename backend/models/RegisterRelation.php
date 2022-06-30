<?php

namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class RegisterRelation extends \common\models\RegisterRelation
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

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_time',
                'updatedAtAttribute' => 'update_time',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'create_time',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'update_time',
                ],
                'value' => function($event) {
                    if ($this->isNewRecord) { // or $event->name == ActiveRecord::EVENT_BEFORE_INSERT
                        return time();
                    } else {
                        return date('Y-m-d H:i:s');
                    }
                }
            ],
        ];
    }
}