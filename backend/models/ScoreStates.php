<?php

namespace backend\models;

use common\models\ScoreGroup;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class ScoreStates extends \common\models\ScoreStates
{
    const STATUS_VALID = 1;
    const STATUS_INVALID = 0;

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => 'update_time',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'update_time',
                ],
                'value' => function($event) {
                    return date('Y-m-d H:i:s');
                }
            ],
        ];
    }

    public function getGroupNameLane($itemID, $enrollID)
    {
        $ret = [
            'group' => '-',
            'lane' => '-',
        ];
        $model = $this->find()
            ->where([
                'itemid' => $itemID,
                'enrollid' => $enrollID,
            ])
            ->one();
        if (isset($model)) {
            $ret['lane'] = $model->lane;
            $modelGroup = ScoreGroup::findOne($model->groupid);
            if (isset($modelGroup)) {
                $ret['group'] = $modelGroup->groupnum;
            }
        }

        return $ret;
    }

    public function addOne($matchID, $itemID, $groupID, $groupNum, $enrollID, $lane,
                           $enrollGender, $enrollName, $startValue = null, $stateValue = null,
                           $stateValueTime = null, $score = null, $speed = null, $isValued = 1)
    {
        $this->matchid = $matchID;
        $this->itemid = $itemID;
        $this->groupid = $groupID;
        $this->groupnum = $groupNum;
        $this->enrollid = $enrollID;
        $this->lane = $lane;
        $this->enrollgender = $enrollGender;
        $this->enrollname = $enrollName;
        $this->startvalue = $startValue;
        $this->statevalue = $stateValue;
        $this->statevalue_time = $stateValueTime;
        $this->score= $score;
        $this->speed = $speed;
        $this->isvalued = $isValued;

        if (!$this->save()) {
            throw new \Exception('保存失败');
        }
    }

    public function getPrintGroupData($itemID)
    {
        $data = $this->find()
            ->select(['lane', 'enrollname', 'groupnum', 'enrollid'])
            ->asArray()
            ->where([
                'itemid' => $itemID
            ])
            ->orderBy(['groupnum' => SORT_ASC, 'lane' => SORT_ASC])
            ->all();
        return $data;
    }

    public function getStateData($itemID)
    {
        $data = $this->find()
            ->asArray()
            ->select(['id', 'matchid', 'enrollid', 'enrollgender', 'enrollname', 'startvalue',
                'statevalue', 'statevalue_time', 'score', 'speed', 'isvalued'])
            ->where([
                'itemid' => $itemID,
            ])
            ->andWhere([
                '!=', 'isvalued', self::STATUS_INVALID
            ])
            ->all();

        return array_column($data, null, 'enrollid');
    }
}