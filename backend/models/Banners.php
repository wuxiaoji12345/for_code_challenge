<?php

namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Banners extends \common\models\Banners
{
    const STATUS_VALID = 1;
    const STATUS_INVALID = 2;

    const POSITION_FRONT_PAGE = 1;
    const POSITION_DETAIL = 2;

    const JUMP_INNER = 1;
    const JUMP_OUTER_URL = 2;

    public static $positionList = [
        self::POSITION_FRONT_PAGE => '首页',
        self::POSITION_DETAIL => '详情',
    ];

    public static $jumpTypeList = [
        self::JUMP_INNER => '内部跳转',
        self::JUMP_OUTER_URL => '外部url跳转',
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