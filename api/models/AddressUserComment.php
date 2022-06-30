<?php

namespace api\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class AddressUserComment extends \common\models\AddressUserComment
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

    public function apiCommentList($swimAddressID, $page = 1, $length = 20)
    {
        $length = ($length <= 0) ? 20 : $length;
        $page = ($page <= 0) ? 1 : $page;
        $start = ($page - 1) * $length;
        $data = $this->find()
            ->asArray()
            ->select(['user_id', new Expression('date(comment_date) as comment_date'), 'comment', 'score'])
            ->where([
                'swim_address_id' => $swimAddressID,
                'status' => self::STATUS_VALID,
            ])
            ->orderBy([
                'comment_date' => SORT_DESC
            ])
            ->offset($start)
            ->limit($length)
            ->all();
        return $data;
    }

    public function addOne($addressID, $userID, $score, $comment)
    {
        $model = $this->getUserAddressComment($addressID, $userID);
        if (!isset($model)) {
            $model = new self();
        }
        $model->swim_address_id = $addressID;
        $model->user_id = $userID;
        $model->score = $score;
        $model->comment = $comment;
        $model->comment_date = date('Y-m-d H:i:s');;
        $model->status = self::STATUS_VALID;
        if (!$model->save()) {
            throw new \Exception('保存失败');
        }
    }

    public function getUserAddressComment($addressID, $userID)
    {
        return $this->findOne([
            'swim_address_id' => $addressID,
            'user_id' => $userID,
            'status' => self::STATUS_VALID
        ]);
    }
}