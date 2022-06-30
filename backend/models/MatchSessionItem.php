<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class MatchSessionItem extends \common\models\MatchSessionItem
{
    const STATUS_VALID = 1;
    const STATUS_INVALID = 2;

    const TYPE_ZIYOUYONG = 1;
    const TYPE_DIEYON = 2;
    const TYPE_YANGYONG = 3;
    const TYPE_WAYONG = 4;
    const TYPE_MIXED = 5;

    const GENDER_M = 1;
    const GENDER_F = 2;
    const GENDER_MIXED = 3;

    public static $typeList = [
        self::TYPE_ZIYOUYONG => '自由泳',
        self::TYPE_DIEYON => '蝶泳',
        self::TYPE_YANGYONG => '仰泳',
        self::TYPE_WAYONG => '蛙泳',
        self::TYPE_MIXED => '混合',
    ];

    public static $genderList = [
        self::GENDER_M => '仅男子',
        self::GENDER_F => '仅女子',
        self::GENDER_MIXED => '男女皆可',
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

    public function getNamesByIDs(array $ids)
    {
        $data = $this->find()
            ->select('name')
            ->asArray()
            ->where(['id' => $ids])
            ->all();
        return array_column($data, 'name');
    }

    public function getIDsByName($name)
    {
        $data = $this->find()
            ->asArray()
            ->select(['id'])
            ->where([
                'status' => self::STATUS_VALID
            ])
            ->andWhere([
                'like', 'name', $name
            ])
            ->all();

        return array_column($data, 'id');
    }

    public function getModelByMatchIDSsIDName($matchID, $SsID, $name)
    {
        return $this->find()
            ->where([
                'matchid' => $matchID,
                'ssid' => $SsID,
                'name' => $name,
                'status' => self::STATUS_VALID
            ])
            ->one();
    }

    public function getAllItems($ssid)
    {
        return $this->find()
            ->asArray()
            ->select(['id', 'name'])
            ->where([
                'ssid' => $ssid,
                'status' => self::STATUS_VALID
            ])
            ->all();
    }

    /**
     * 场次分组
     * @param $ssid
     * @return bool
     */
    public function groupSession($ssid)
    {
        $ret = true;
        $items = $this->getAllItems($ssid);
        foreach ($items as $itemValue) {
            $ret &= $this->groupSessionItem($ssid, $itemValue['id'], true);
        }

        return $ret;
    }

    /**
     * 场次项目分组
     * @param $ssid
     * @param $itemID
     * @param $fromSession
     * @return bool
     */
    public function groupSessionItem($ssid, $itemID, $fromSession = false)
    {
        $modelSession = MatchSession::findOne($ssid);
        $modelAddress = Address::findOne($modelSession->swim_address_id);
        $lane = $modelAddress->lane;
        $enrollData = (new ScoreEnroll())->getSessionItemEnroll($ssid, $itemID, $fromSession);
        //$scoreStateData = (new ScoreStates())->getStateData($itemID);

        if ($fromSession) {
            $groups = $this->groupFromSession($enrollData, $lane);
        } else {
            $groups = $this->groupFromSessionItem($enrollData, $lane);
        }

        $ret = true;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            //删除历史记录
            $condScoreState = ['and', ['itemid' => $itemID], ['score' => null]];
            $condScoreGroup = ['ssid' => $ssid, 'itemid' => $itemID];
            Yii::$app->db->createCommand()->delete(ScoreStates::tableName(),
                $condScoreState)->execute();
            Yii::$app->db->createCommand()->delete(ScoreGroup::tableName(),
                $condScoreGroup)->execute();
            //
            foreach ($groups as $idx => $groupData) {
                $modelScoreGroup = new ScoreGroup();
                $modelScoreGroup->addOne($modelSession->matchid, $ssid, $itemID, $idx + 1);
                //
                $competeLane = (int) floor(($lane - count($groupData)) / 2) + 1;//一组人数不足泳道的情况，相同的情况下=0，少于的尽量排到中间
                foreach ($groupData as $enrollData) {
                    if (!isset($enrollData['startvalue'])) {
                        $enrollData['startvalue'] = null;
                        $enrollData['statevalue'] = null;
                        $enrollData['statevalue_time'] = null;
                        $enrollData['score'] = null;
                        $enrollData['speed'] = null;
                        $enrollData['isvalued'] = 0;
                    }
                    $modelScoreState = new ScoreStates();
                    $modelScoreState->addOne($modelSession->matchid, $itemID, $modelScoreGroup->id,
                        $idx + 1, $enrollData['id'], $competeLane, $enrollData['enrollgender'],
                        $enrollData['enrollname'], $enrollData['startvalue'], $enrollData['statevalue'],
                        $enrollData['statevalue_time'], $enrollData['score'], $enrollData['speed'],
                        $enrollData['isvalued']);
                    $competeLane++;
                }
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $ret = false;
            $transaction->rollBack();
        }

        return $ret;
    }

    public function addOneFromUpload($matchid, $ssid, $name, $typeName, $genderName, $distance, $ageMin, $ageMax)
    {
        $model = $this->getModelByMatchIDSsIDName($matchid, $ssid, $name);
        if (!isset($model)) {
            $model = new self();
        }

        $type = array_search($typeName, self::$typeList);
        $gender = array_search($genderName, [1 => '男', 2 => '女', 3 => '无限制']);
        if ($type === false || $gender === false) {
            return false;
        }

        $model->ssid = $ssid;
        $model->matchid = $matchid;
        $model->name = $name;
        $model->type = $type;
        $model->gender = $gender;
        $model->distance = intval($distance);
        $model->agemin = intval($ageMin);
        $model->agemax = intval($ageMax);
        $model->status = self::STATUS_VALID;
        return $model->save();
    }

    public function getItemIDsBySsidItemNames($ssid, array $itemNames)
    {
        $data = $this->find()
            ->asArray()
            ->select(['id', 'name'])
            ->where([
                'ssid' => $ssid,
                'name' => $itemNames,
                'status' => self::STATUS_VALID
            ])
            ->all();
        return array_column($data, 'id', 'name');
    }

    protected function groupFromSession(array $enrolls, $lanes)
    {
        $totalPlayer = count($enrolls);
        $totalGroup = (int)ceil($totalPlayer / $lanes);
        $mod = $totalPlayer % $lanes;
        $groups = [];

        if ($totalGroup == 1) { //比赛人数少于等于泳道数
            $groups[0] = $enrolls;
        } else {
            if ($mod == 0) { //可以分满每个赛道
                while (count($enrolls) > 0) {
                    $oneGroup = array_splice($enrolls, 0, $lanes); //之前larvel有bug，正好分完的，好的成绩在两边，差的在中间，要反转下数组即可
                    array_push($groups, array_reverse($oneGroup));
                }
            } else {
                if ($totalPlayer > $lanes) { //人数比泳道数多
                    $start = (int)ceil($lanes / 2);
                    if ($mod <= $lanes / 2) { //多余的人小于赛道一半
                        $groups[0] = array_splice($enrolls, 0,$start);
                    } else {
                        $groups[0] = array_splice($enrolls, 0, $mod);
                    }
                    $enrolls    =   array_reverse($enrolls);
                    for ($i = $totalGroup - 1; $i > 0; $i--) {
                        $groups[$i] = array_splice($enrolls, 0, $lanes);
                    }
                    ksort($groups);

                } else {
                    //永远不会进入
                }
            }
        }

        for ($i = 0; $i < count($groups); $i++) {
            $groups[$i] = $this->snakeGroup($groups[$i]);
            //$groups[$i] = $this->snakeGroupAI($groups[$i]); //可以替代
        }
        return $groups;
    }

    protected function groupFromSessionItem(array $enrolls, $lanes)
    {
        $totalPlayer = count($enrolls);
        $totalGroup = (int)ceil($totalPlayer / $lanes);
        $mod = $totalPlayer % $lanes;
        $groups = [];

        if ($totalGroup == 1) { //比赛人数少于等于泳道数
            $groups[0] = $enrolls;
        } else {
            if ($mod == 0) { //可以分满每个赛道
                while (count($enrolls) > 0) {
                    array_push($groups, array_splice($enrolls, 0, $lanes));
                }
            } else {
                if ($totalPlayer > $lanes) { //人数比泳道数多
                    $start = (int)ceil($lanes / 2);
                    if ($mod <= $lanes / 2) { //多余的人小于赛道一半
                        $groups[0] = array_splice($enrolls, 0, (int)$start);
                        $groups[$totalGroup - 1] = array_splice($enrolls, 0, $lanes);
                    } else {
                        $groups[0] = array_splice($enrolls, 0, $mod);
                        $groups[$totalGroup - 1] = array_splice($enrolls, 0, $lanes);
                    }
                    for ($i = $totalGroup - 2; $i > 0; $i--) {
                        $groups[$i] = array_splice($enrolls, 0, $lanes);
                    }
                } else {
                    //永远不会进入
                }
            }
        }

        return $groups;
    }

    /**
     * 蛇形分组
     * @param array $group
     * @return array
     */
    protected function snakeGroup(array $group)
    {
        $n = count($group);
        $a = array();
        if ($n === 6) {
            $a[6] = $group[5];
            $a[1] = $group[4];
            $a[5] = $group[3];
            $a[2] = $group[2];
            $a[4] = $group[1];
            $a[3] = $group[0];
        } elseif ($n === 5) {
            $a[1] = $group[4];
            $a[5] = $group[3];
            $a[2] = $group[2];
            $a[4] = $group[1];
            $a[3] = $group[0];
        } elseif ($n === 4) {
            $a[4] = $group[3];
            $a[1] = $group[2];
            $a[3] = $group[1];
            $a[2] = $group[0];
        } elseif ($n === 3) {
            $a[1] = $group[2];
            $a[3] = $group[1];
            $a[2] = $group[0];
        } elseif ($n === 2 ||  $n === 1) {
            $a = $group;
        }
        ksort($a);
        return $a;
    }

    protected function snakeGroupAI(array $group)
    {
        $lane = count($group);
        $a = array();
        $right = intval(ceil($lane / 2));
        $left = $right - 1;
        for ($i = 0; ; $i += 2) {
            if (isset($group[$i])) {
                $a[$left + 1] = $group[$i]; //赛道从1开始，$a位置往右偏移一位
                $left--;
            }
            if (isset($group[$i + 1])) {
                $a[$right + 1] = $group[$i + 1]; //赛道从1开始，$a位置往右偏移一位
                $right++;
            }
            if (count($a) == $lane) {
                break;
            }
        }
        ksort($a);
        return $a;
    }
}