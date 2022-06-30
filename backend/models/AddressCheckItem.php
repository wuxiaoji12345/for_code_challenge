<?php

namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class AddressCheckItem extends \common\models\AddressCheckItem
{
    const STATUS_VALID = 1;
    const STATUS_INVALID = 2;

    /**
     * 检查项页面展示html
     * @return string
     */
    public function getCheckInfoForIndexPage()
    {
        $arr = json_decode($this->info, true);
        $info = '';
        if (isset($arr['selectOption'])) {
            $info .= '选择项：';
            foreach ($arr['selectOption'] as $optionArr) {
                $info .= implode('，', array_keys($optionArr)) . ' | ';
            }
            $info = substr($info, 0, -2) . '<br/>';
        }
        if (isset($arr['inputOption'])) {
            $info .= '输入项：' . implode('，', $arr['inputOption']) . '<br/>';
        }
        return $info;
    }

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

    public function updateLevel()
    {
        if ($this->pid == 0) {
            $this->level = 1;
        } else {
            $parent = $this->findOne($this->pid);
            $this->level = $parent->level + 1;
        }

        return true;
    }

    public function getCheckItemByPid($pid = 0)
    {
        $data = $this->find()
            ->select(['id', 'name'])
            ->where([
                'status' => self::STATUS_VALID,
                'pid' => $pid
            ])
            ->asArray()
            ->all();
        $kv = [0 => '无父项目'];
        $kv =  $kv + array_column($data, 'name', 'id');
        return $kv;
    }

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

    public function getNamesByItemIDs(array $itemIDs)
    {
        $data = $this->find()
            ->select(['id', 'name', 'pid'])
            ->asArray()
            ->where([
                'id' => $itemIDs,
            ])
            ->orderBy([
                'weight' => SORT_DESC
            ])
            ->all();
        $ret = [];
        foreach ($data as $value) {
            $ret[$value['id']] = [];
            if ($value['pid'] == 0) {
                $ret[$value['id']] = $value['name'];
            } else {
                $ret[$value['id']] = $this->getNameByID($value['pid']) . '-' . $value['name'];
            }
        }

        return $ret;
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
            ->select(['id', 'name', 'pid', 'info'])
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
            if ($checkItem['pid'] != 0) {
                $name = $this->getNameByID($checkItem['pid']) . ':' . $checkItem['name'];
            }
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
                'attrs' => $attrs,
                'required' => $required,
                'check_status' => $check_status,
            ];
        }

        return $ret;
    }
}