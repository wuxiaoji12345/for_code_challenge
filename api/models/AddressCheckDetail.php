<?php


namespace api\models;


class AddressCheckDetail extends \common\models\AddressCheckDetail
{
    const STATUS_VALID = 1;
    const STATUS_INVALID = 2;

    public function getRecord($checkID, $checkItemID)
    {
        return self::findOne([
            'swim_address_check_id' => $checkID,
            'swim_address_check_item_id' => $checkItemID,
            'status' => self::STATUS_VALID
        ]);
    }

    public function addOne($checkID, $itemID, $resultJson, $itemSnapshot, $check_status)
    {
        $detailModel = $this->getRecord($checkID, $itemID);
        if (!isset($detailModel)) {
            $detailModel = new self();
        }

        $detailModel->swim_address_check_id = $checkID;
        $detailModel->swim_address_check_item_id = strval($itemID);
        $detailModel->result = $resultJson;
        $detailModel->item_snapshot = $itemSnapshot;
        $detailModel->status = self::STATUS_VALID;
        $detailModel->check_status = $check_status;
        if (!$detailModel->save()) {
            \Yii::error(json_encode($detailModel->getErrors()));
            throw new \Exception('保存失败');
        }
    }

    public function getCheckDetail($checkID)
    {
        $detailData = $this->find()
            ->select(['swim_address_check_item_id', 'result', 'check_status'])
            ->asArray()
            ->where([
                'swim_address_check_id' => $checkID,
                'status' => self::STATUS_VALID
            ])
            ->all();
        $ret = [];
        foreach ($detailData as $value) {
            $ret[$value['swim_address_check_item_id']]['result'] = json_decode($value['result'], true);
            $ret[$value['swim_address_check_item_id']]['check_status'] = $value['check_status'];
        }
        return $ret;
    }
}