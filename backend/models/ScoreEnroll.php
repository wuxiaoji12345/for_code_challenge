<?php

namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class ScoreEnroll extends \common\models\ScoreEnroll
{
    const STATUS_VALID = 1;
    const STATUS_INVALID = 2;

    const TYPE_ONLINE = 1;
    const TYPE_OFFLINE = 2;

    const GENDER_M = 1;
    const GENDER_F = 2;

    public static $typeList = [
        self::TYPE_ONLINE => '线上',
        self::TYPE_OFFLINE => '线下',
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

    public function getSessionItemEnroll($ssid, $itemID, $fromSession = false)
    {
        $query = $this->find()
            ->asArray()
            ->select(['id', 'name as enrollname', 'gender as enrollgender'])
            ->where([
                'ssid' => $ssid,
                'itemid' => $itemID,
                'status' => self::STATUS_VALID
            ]);
        if ($fromSession) {
            $query->orderBy([
                'id' => SORT_DESC,
            ]);
        }
        $data = $query
            ->all();

        return array_column($data, null, 'id');
    }

    public function loadOnlineRegister($ssid)
    {
        $modelSession = MatchSession::findOne($ssid);
        $initEnroll = [
            'matchid' => $modelSession->matchid,
            'ssid' => $ssid,
            'type' => self::TYPE_ONLINE,
            'status' => self::STATUS_VALID,
            'unit' => '',
            'name' => '',
            'gender' => 1,
            'phone' => '',
            'idcard' => '',
            'itemid' => 0,
        ];
        $enrolls = [];
        $itemIDs = [];
        $onlineRegister = (new RegisterDetail())->getRegisterDataForEnroll($ssid);
        foreach ($onlineRegister as $value) {
            $modelRegisterInfo = RegisterInfo::findOne(['rrid' => $value['rrid']]);
            $initEnroll['unit'] = $this->getOrgName($modelRegisterInfo->registerinfos);
            $initEnroll['name'] = $modelRegisterInfo->name;
            $initEnroll['gender'] = $modelRegisterInfo->sex == '男' ? 1 : 2;
            $initEnroll['phone'] = $modelRegisterInfo->mobile;
            $initEnroll['idcard'] = $modelRegisterInfo->idnumber;

            if (!empty($value['itemid1']) && !isset($itemIDs[$value['itemid1']])) {
                $itemIDs[$value['itemid1']] = '';
                $initEnroll['itemid'] = $value['itemid1'];
                $enrolls[] = $initEnroll;
            }
            if (!empty($value['itemid2']) && !isset($itemIDs[$value['itemid2']])) {
                $itemIDs[$value['itemid2']] = '';
                $initEnroll['itemid'] = $value['itemid2'];
                $enrolls[] = $initEnroll;
            }
        }

        if (!empty($enrolls)) {
            $affect = \Yii::$app->db->createCommand()->delete(ScoreEnroll::tableName(), [
                'matchid' => $modelSession->matchid,
                'ssid' => $ssid,
                'type' => self::TYPE_ONLINE,
                'itemid' => array_keys($itemIDs),
            ])->execute();
            if ($affect == 0) {
                throw new \Exception('删除旧数据失败');
            }
            $affect = \Yii::$app->db->createCommand()
                ->batchInsert(ScoreEnroll::tableName(), array_keys($initEnroll), $enrolls)
                ->execute();
            if ($affect == 0) {
                throw new \Exception('批量插入数据失败');
            }

            return array_keys($itemIDs);
        }

        return $itemIDs;
    }

    public function getUnit($id)
    {
        $model = $this->find()
            ->where([
                'id' => $id,
            ])
            ->one();
        if (isset($model)) {
            return $model->unit;
        }

        return '';
    }

    private function getOrgName($registerInfos)
    {
        $infos = json_decode($registerInfos, true);
        foreach ($infos as $info) {
            if ($info['key_name'] == 'mv_group') return $info['value'];
        }
        return '';
    }
}