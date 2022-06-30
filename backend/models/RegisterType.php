<?php

namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class RegisterType extends \common\models\RegisterType
{
    const STATUS_VALID = 1;
    const STATUS_INVALID = 2;

    const TYPE_SINGLE = 1;
    const TYPE_UNION = 2;

    const NEED_CHECK_YES = 2;
    const NEED_CHECK_NO = 1;

    const REGISTER_NO_LIMIT = 0;

    const MEMBER_PAY_AND_ADD = 1;
    const MEMBER_MUST_HAVE = 2;

    public static $typeList = [
        self::TYPE_SINGLE => '单场',
        self::TYPE_UNION => '联票',
    ];

    public static $checkList = [
        self::NEED_CHECK_YES => '需要审核',
        self::NEED_CHECK_NO => '不需要审核',
    ];

    public static $memberInfo = [
        self::MEMBER_PAY_AND_ADD => '先报名后加成员',
        self::MEMBER_MUST_HAVE => '必须有成员',
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

    public function existMatchRegister($matchID)
    {
        $cnt = $this->find()
            ->where([
                'matchid' => $matchID
            ])
            ->count();
        if ($cnt > 0) {
            return true;
        }

        return false;
    }
}