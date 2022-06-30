<?php


namespace api\models;


use backend\models\AddressCheck;

class AddressCheckItem extends \common\models\AddressCheckItem
{
    const STATUS_VALID = 1;
    const STATUS_INVALID = 2;

    public function getNameByID($id)
    {
        $data = $this->find()
            ->select(['name'])
            ->where(['id' => $id])
            ->one();
        if (isset($data->name)) {
            return $data->name;
        }

        return '-';
    }

    /**
     * 获取场馆检查内容及上次选择项
     * 返回数据中type定义  1-选择项 2-单行填空 3-多行填空 4-图片 5-签名图片
     * @param $addressID
     * @param $addressCheckID
     * @return array
     */
    public function apiCheckList($addressID, $addressCheckID = false)
    {
        $checkList = $this->find()
            ->select(['id', 'name', 'pid', 'info', 'national_standard'])
            ->asArray()
            ->where([
                'status' => self::STATUS_VALID
            ])
            ->andWhere([
                '!=', 'info', ''
            ])
            ->orderBy([
                'weight' => SORT_DESC
            ])
            ->all();
        $ret = [];
        foreach ($checkList as $checkItem) {
            $name = $checkItem['name'];
            $national_standard = $checkItem['national_standard'];
//            if ($checkItem['pid'] != 0) {
//                $name = $this->getNameByID($checkItem['pid']) . ':' . $checkItem['name'];
//            }
            //附带上次选择内容
            if ($addressCheckID === false) {
                $lastCheckData = (new AddressCheck())->getAddressLastCheckDetail($addressID);
            } else {
                $lastCheckData = (new AddressCheckDetail())->getCheckDetail($addressCheckID);
            }
            $check_status = $lastCheckData[$checkItem['id']]['check_status'] ?? AddressCheckDetail::NORMAL_STATUS;
            $detail = json_decode($checkItem['info'], true);
            $attrs = [];
            if (isset($detail['selectOption'])) {
                foreach ($detail['selectOption'] as $selectID => $options) {
                    $attrOptions = [];
                    $answer = null;
                    if (isset($lastCheckData[$checkItem['id']]['result']['select'][$selectID])) {
                        $answer = array_intersect(array_keys($options),
                            $lastCheckData[$checkItem['id']]['result']['select'][$selectID]);
                    }
                    foreach ($options as $selectValue => $isCorrect) {
                        $temp = [
                            'key' => $isCorrect,
                            'show_name' => $selectValue,
                        ];
                        if (($answer !== null) && (in_array($selectValue, $answer))) {
                            $temp['answer'] = true;
                        }
                        $attrOptions[] = $temp;
                    }
                    $attrs[] = [
                        'type' => 1, //选择项
                        'options' => $attrOptions,
                        'key_name' => $selectID,
                    ];
                }
            }
            if (isset($detail['inputOption'])) {
                foreach ($detail['inputOption'] as $idx => $value) {
                    $fillValue = '';
                    if ($addressCheckID !== false) {
                        if (isset($lastCheckData[$checkItem['id']]['result']['input'][$idx])) {
                            $fillValue = str_replace($value . ' ', '', $lastCheckData[$checkItem['id']]['result']['input'][$idx]);
                        }
                    }
                    $isTextArea = (strpos($value, 'xxx') === false);
                    $attrs[] = [
                        'type' => ($isTextArea ? 2 : 3), // 2-单行填空 3-多行填空
                        'options' => $value,
                        'key_name' => '',
                        'value' => $fillValue,
                    ];
                }
            }
            if (isset($detail['imageOption'])) {
                $fillValue = '';
                if ($addressCheckID !== false) {
                    if (isset($lastCheckData[$checkItem['id']]['result']['image'])) {
                        $fillValue = $lastCheckData[$checkItem['id']]['result']['image'];
                    }
                }
                $max = isset($detail['imageOption']['max']) ? $detail['imageOption']['max'] : 1;
                $max = ($max < 1) ? 1 : $max;
                $attrs[] = [
                    'type' => 4, // 4-图片
                    'options' => $max,
                    'key_name' => '',
                    'value' => $fillValue,
                ];
            }
            if (isset($detail['signatureOption'])) {
                $fillValue = '';
                if ($addressCheckID !== false) {
                    if (isset($lastCheckData[$checkItem['id']]['result']['image'])) {
                        $fillValue = $lastCheckData[$checkItem['id']]['result']['image'];
                    }
                }
                $max = isset($detail['signatureOption']['max']) ? $detail['signatureOption']['max'] : 1;
                $max = ($max < 1) ? 1 : $max;
                $attrs[] = [
                    'type' => 5, // 5-签名图片
                    'options' => $max,
                    'key_name' => '',
                    'value' => $fillValue,
                ];
            }
            $required = $detail['required'] ?? true;
            $ret[] = [
                'item_id' => $checkItem['id'],
                'title' => $name,
                'p_name' => $this->getNameByID($checkItem['pid']),
                'attrs' => $attrs,
                'required' => $required,
                'check_status' => $check_status,
                'national_standard' => $national_standard,
            ];
        }

        return $ret;
    }

    public function checkList()
    {
        $checkList = $this->find()
            ->select(['id', 'info'])
            ->asArray()
            ->where([
                'status' => self::STATUS_VALID
            ])
            ->andWhere([
                '!=', 'info', ''
            ])
            ->orderBy([
                'weight' => SORT_DESC
            ])
            ->all();

        return $checkList;
    }
}