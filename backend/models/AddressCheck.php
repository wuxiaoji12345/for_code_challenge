<?php

namespace backend\models;

use backend\models\AddressCheckDetail;
use yii\db\ActiveRecord;

class AddressCheck extends \common\models\AddressCheck
{
    const STATUS_VALID = 1;
    const STATUS_INVALID = 2;

    public function getUserChannel()
    {
        return $this->hasOne(UserChannel::className(), ['id' => 'user_channel_id']);
    }

    public function getAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'swim_address_id']);
    }

    public function getAddressLastCheckDetail($addressID,$is_address = true)
    {
        $where['status'] = self::STATUS_VALID;
        if($is_address){
            $where['swim_address_id'] = $addressID;
        } else {
            $where['user_channel_id'] = $addressID;
        }
        $checkData = $this->find()
            ->select(['id'])
            ->asArray()
            ->where($where)
            ->orderBy([
                'check_date' => SORT_DESC
            ])
            ->one();
        if (isset($checkData['id'])) {
            $detailData = (new AddressCheckDetail())->find()
                ->select(['swim_address_check_item_id', 'result'])
                ->asArray()
                ->where([
                    'swim_address_check_id' => $checkData['id'],
                    'status' => self::STATUS_VALID
                ])
                ->all();
            $ret = [];
            foreach ($detailData as $value) {
                $ret[$value['swim_address_check_item_id']]['result'] = json_decode($value['result'], true);
            }
            return $ret;
        }

        return [];
    }
}