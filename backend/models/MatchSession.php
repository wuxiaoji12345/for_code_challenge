<?php

namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class MatchSession extends \common\models\MatchSession
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

    public function getNameByID($id)
    {
        $data = $this->find()
            ->select('name')
            ->where(['id' => $id])
            ->one();
        if (isset($data->name)) {
            return $data->name;
        }

        return '-';
    }

    public function getSsidList($matchid)
    {
        return $this->find()->asArray()
            ->select(['id', 'name'])
            ->where([
                'matchid' => $matchid,
                'status' => 1,
            ])
            ->all();
    }
}