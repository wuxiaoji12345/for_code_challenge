<?php


namespace backend\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class UserChannelExtra extends \common\models\UserChannelExtra
{
    const STATUS_VALID = 1;
    const STATUS_INVALID = 2;

    const CHECKER_YES = 1;
    const CHECKER_NO = 2;

    const SUPER_CHECKER_YES = 2;
    const SUPER_CHECKER_NO = 1;

    public static $checkerList = [
        self::CHECKER_YES => '是',
        self::CHECKER_NO => '否',
    ];
    public static $SuperCheckerList = [
        self::SUPER_CHECKER_YES => '是',
        self::SUPER_CHECKER_NO => '否',
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