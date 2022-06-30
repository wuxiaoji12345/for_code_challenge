<?php


namespace api\models;

use common\helpers\Utils;
use common\models\WorkOrder;
use common\models\WorkOrderIndex;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class AddressCheck extends \common\models\AddressCheck
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

    public function getRecord($addressID, $checkDate)
    {
        return $this->find()
            ->where([
                'swim_address_id' => $addressID,
                'status' => self::STATUS_VALID,
            ])
            ->andWhere([
                '>=', 'check_date', date('Y-m-d 00:00:00', strtotime($checkDate))
            ])
            ->andWhere([
                '<=', 'check_date', date('Y-m-d 23:59:59', strtotime($checkDate))
            ])
            ->one();
    }

    public function apiList($addressID, $page, $limit, $check_status)
    {
        $page = ($page > 0) ? $page : 1;
        $limit = ($limit > 0) ? $limit : 10;
        $start = ($page - 1) * $limit;
        $query = $this->find()->asArray()
            ->select(['id', 'check_date', 'check_status', 'swim_address_id'])
            ->where([
                'swim_address_id' => $addressID,
                'status' => 1,
            ]);
        if ($check_status) $query = $query->andWhere(['check_status' => $check_status]);
        $total = $query->count();
        $list = $query->orderBy([
            'check_date' => SORT_DESC
        ])
            ->offset($start)->limit($limit)
            ->all();
        $data = [
            'page' => $page,
            'pages' => ceil($total / $limit),
            'total' => intval($total),
            'list' => $list,
        ];
        return $data;
    }

    public function checkManHistory($channel_id, $page, $limit, $check_status, $district, $item_id)
    {
        $channel_id = Utils::ecbDecrypt(\Yii::$app->params['channelIDKey'], $channel_id);
        $is_super_checker = UserChannelExtra::findOneArray(['status' => UserChannelExtra::NORMAL_STATUS, 'user_channel_id' => $channel_id],
                ['is_super_checker'])['is_super_checker'] ?? UserChannelExtra::SUPER_CHECKER_NO;
        $where = $is_super_checker == UserChannelExtra::SUPER_CHECKER_YES ? [
            'ac.status' => AddressCheck::NORMAL_STATUS,
        ] : [
            'ac.user_channel_id' => $channel_id,
            'ac.status' => AddressCheck::NORMAL_STATUS,
        ];
        $page = ($page > 0) ? $page : 1;
        $limit = ($limit > 0) ? $limit : 10;
        $start = ($page - 1) * $limit;
        $query = $this->find()->alias('ac')->asArray()
            ->select(['ac.id check_id', 'ac.check_date', 'ac.check_status', 'ac.swim_address_id'])
            ->leftJoin(\common\models\Address::tableName() . ' a', 'a.id = ac.swim_address_id')
            ->leftJoin(WorkOrderIndex::tableName() . ' w', 'w.address_check_id = ac.id')
            ->groupBy('check_id')
            ->where($where);
        if ($check_status) $query = $query->andWhere(['ac.check_status' => $check_status]);
        if ($district) $query = $query->andWhere(['a.district' => $district]);
        if ($item_id) $query = $query->andWhere(['like','w.info',$item_id]);
        $total = $query->count();
        $list = $query->orderBy([
            'ac.check_date' => SORT_DESC
        ])
            ->offset($start)->limit($limit)
            ->all();
        $data = [
            'page' => $page,
            'pages' => ceil($total / $limit),
            'total' => intval($total),
            'list' => $list,
        ];
        return $data;
    }
}