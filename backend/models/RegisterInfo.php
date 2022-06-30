<?php

namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class RegisterInfo extends \common\models\RegisterInfo
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

    public function getUridsByNameMobile($name, $mobile)
    {
        $data = $this->find()
            ->select(['rrid'])
            ->asArray()
            ->andFilterWhere([
                'like', 'name', $name
            ])
            ->andFilterWhere([
                'like', 'mobile', $mobile
            ])
            ->all();
        return array_column($data, 'rrid');
    }

    public function getFiledByRridMatchid($rrid, $matchid, $field)
    {
        $model = $this->find()
            ->select($field)
            ->where([
                'rrid' => $rrid,
                'matchid' => $matchid,
            ])
            ->one();
        if (isset($model->$field)) {
            return $model->$field;
        }

        return '';
    }
}