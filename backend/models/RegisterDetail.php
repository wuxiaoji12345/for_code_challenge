<?php


namespace backend\models;

use backend\models\MatchSessionItem;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class RegisterDetail extends \common\models\RegisterDetail
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

    public function getItemNamesByOrderID($orderID)
    {
        $data = $this->find()
            ->select(['itemid1', 'itemid2'])
            ->asArray()
            ->where([
                'rrid' => $orderID
            ])
            ->all();
        $itemIDs = [];
        foreach ($data as $value) {
            $itemIDs[] = $value['itemid1'];
            $itemIDs[] = $value['itemid2'];
        }
        return (new MatchSessionItem())->getNamesByIDs($itemIDs);
    }

    public function getSSidName($orderID)
    {
        $data = $this->find()
            ->select(['ssid', 'name'])
            ->asArray()
            ->leftJoin(MatchSession::tableName(), 'ssid=swim_match_session.id')
            ->where([
                'rrid' => $orderID
            ])
            ->one();
        return $data;
    }

    public function getRegisterDataForEnroll($ssid)
    {
        $data = $this->find()
            ->select(['rrid', 'itemid1', 'itemname2'])
            ->asArray()
            ->leftJoin(RegisterRelation::tableName(), 'swim_register_relation.id=rrid')
            ->where([
                'swim_register_detail.ssid' => $ssid,
                'swim_register_relation.state' => RegisterRelation::STATE_PAID,
            ])
            ->all();

        return $data;
    }
}