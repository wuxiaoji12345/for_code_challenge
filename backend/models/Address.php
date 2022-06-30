<?php

namespace backend\models;

use common\models\AddressContactPerson;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use function GuzzleHttp\Psr7\str;

class Address extends \common\models\Address
{
    const STATUS_VALID = 1;
    const STATUS_INVALID = 2;

    public function dropdownList()
    {
        $data = $this->find()
            ->asArray()
            ->select(['name', 'id'])
            ->where([
                'status' => self::STATUS_VALID
            ])
            ->orderBy([
                'id' => SORT_DESC
            ])
            ->all();

        return array_column($data, 'name', 'id');
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
                'value' => function ($event) {
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

    /**
     * 后台接口-游泳场馆信息新增（第一步）
     * @param $params
     * @return array
     */
    public static function add($params)
    {
        $add = parent::add($params);
        if (!$add[0]) return $add;
        $add[1]->address_id = (string)($add[1]->id);
        if(!$add[1]->save()) return [false,$add[1]->getErrors];
        $contact_model = new AddressContactPerson();
        $contact_model->name = $params['principal'];
        $contact_model->address_id = (string)($add[1]->id);
        $contact_model->phone = $params['mobile'];
        $contact_model->last_access = time();
        if ($contact_model->save()) {
            return $add;
        } else {
            return [false, $contact_model->getErrors()];
        }
    }
}