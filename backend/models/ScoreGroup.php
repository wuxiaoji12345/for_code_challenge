<?php

namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class ScoreGroup extends \common\models\ScoreGroup
{
    const STATUS_VALID = 1;
    const STATUS_INVALID = 2;

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

    public function addOne($matchID, $ssid, $itemID, $groupNum)
    {
        $this->matchid = $matchID;
        $this->ssid = $ssid;
        $this->itemid = $itemID;
        $this->groupnum = $groupNum;
        $this->status = self::STATUS_VALID;

        if (!$this->save()) {
            throw new \Exception('保存失败');
        }
    }
}