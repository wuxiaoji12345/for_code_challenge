<?php

namespace backend\models;

use backend\models\AddressCheckItem;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class AddressCheckDetail extends \common\models\AddressCheckDetail
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

    /**
     * 每日检查内容和结果
     * @param $checkID
     * @return array
     */
    public function getDetailByCheckID($checkID)
    {
        $data = $this->find()
            ->asArray()
            ->select(['swim_address_check_item_id', 'result', 'item_snapshot', 'check_status'])
            ->where([
                'swim_address_check_id' => $checkID,
                'status' => self::STATUS_VALID
            ])
            ->all();

        $kv = array_column($data, null, 'swim_address_check_item_id');
        $itemNames = (new AddressCheckItem())->getNamesByItemIDs(array_keys($kv));
        $ret = [];
        foreach ($itemNames as $itemID => $name) {
            $ret[$name] = [
                'list' => json_decode($kv[$itemID]['item_snapshot'], true),
                'result' => json_decode($kv[$itemID]['result'], true),
                'check_status' => $kv[$itemID]['check_status']
            ];
        }

        return $ret;
    }
}