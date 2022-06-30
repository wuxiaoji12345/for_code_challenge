<?php

namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Match extends \common\models\Match
{
    const STATUS_VALID = 1;
    const STATUS_INVALID = 2;

    const PUBLISH_YES = 1;
    const PUBLISH_NO = 2;

    const MATCH_TYPE_ONLINE = 1;
    const MATCH_TYPE_OFFLINE = 2;
    const MATCH_TYPE_BOTH = 3;

    public static $publish = [
        self::PUBLISH_YES => '已发布',
        self::PUBLISH_NO => '未发布',
    ];

    public function dropdownList()
    {
        $data = $this->find()
            ->asArray()
            ->select(['title', 'id'])
            ->where([
                'status' => self::STATUS_VALID
            ])
            ->orderBy([
                'id' => SORT_DESC
            ])
            ->all();

        return array_column($data, 'title', 'id');
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

    public function getTitleByID($id)
    {
        $data = $this->find()
            ->select('title')
            ->where(['id' => $id])
            ->one();
        if (isset($data->title)) {
            return $data->title;
        }

        return '-';
    }

    public function getIDsByTitle($title)
    {
        $data = $this->find()
            ->asArray()
            ->select(['id'])
            ->where([
                'status' => self::STATUS_VALID
            ])
            ->andWhere([
                'like', 'title', $title
            ])
            ->all();

        return array_column($data, 'id');
    }
}