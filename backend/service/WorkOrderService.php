<?php


namespace backend\service;


use backend\models\AddressCheckDetail;
use backend\models\UserChannelExtra;
use common\libs\Helper;
use common\models\Address;
use common\models\CheckInfo;
use common\models\WorkOrder;
use common\models\WorkOrderHistory;
use common\models\WorkOrderIndex;

class WorkOrderService
{
    const PAGE_SIZE = 20;

    /**
     * 后台接口-工单主表列表
     * @param $params
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]|null
     */
    public static function workOrderList($params)
    {
        if (isset($params['status']) && $params['status'] != WorkOrderIndex::UNTREATED) {
            $params['examine_status'] = $params['status'];
            if ($params['status'] == WorkOrderIndex::APPROVED) unset($params['status']);
        }
        $page_info = Helper::makePageInfo($params, self::PAGE_SIZE);
        $where_data = [
            [
                [
                    'source_type' => 'source_type',
                    'examine_status' => 'examine_status',
                    'status' => 'w.status',
                    'district' => 'a.district',
                ], '='
            ],
        ];
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => CheckInfo::tableName() . ' ci',
                'on' => 'w.commit_id = ci.user_channel_id'],
            [
                'type' => 'LEFT JOIN',
                'table' => Address::tableName() . ' a',
                'on' => 'w.venue_id = a.id'],
        ];

        $where = Helper::makeWhere($where_data, $params, false, true);
        $or_data = [
            [
                [
                    'search' => 'ci.name',
                ], 'like'
            ],
            [
                [
                    'search' => 'a.name',
                ], 'like'
            ],
        ];
        $or_where = Helper::makeWhere($or_data, $params, true);
        $where[] = ['!=', 'w.status', WorkOrderIndex::DELETED];
        $where[] = $or_where;
        $re = WorkOrderIndex::findJoin('w', $join, ['w.*', 'ci.name check_name',
            'a.district', 'a.name address_name',], $where, true, true, 'create_time desc', '', '', [], $page_info);
        if ($re['list']) {
            foreach ($re['list'] as &$v) {
                //临时改动
                $v['address_name'] = '(测试)'.$v['address_name'];
                $v['index_title'] = '(测试)'.$v['index_title'];
                $v['venue_name'] = '(测试)'.$v['venue_name'];
                $v['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $v['type'] = WorkOrderIndex::WORK_ORDERS_TYPE_CN[$v['type']];
                $v['source_type'] = WorkOrderIndex::SOURCE_TYPE_CN[$v['source_type']];
                $v['status'] = $v['status'] == 0 ? WorkOrderIndex::WORK_ORDER_STATUS_CN[$v['status']] : WorkOrderIndex::WORK_ORDER_STATUS_CN[$v['examine_status']];
                unset($v['examine_status']);
            }
        }
        return $re;
    }

    /**
     * 后台接口-更改转办人
     * @param $params
     */
    public static function transfer($params)
    {
        WorkOrderIndex::updateAll(['principal_channel_id' => $params['user_channel_id']], ['id' => $params['id']]);
    }

    /**
     * 后台接口-检查人员工单下拉框列表
     * @return array|string|\yii\db\ActiveRecord[]
     */
    public static function checkerInfoList()
    {
        return CheckInfo::findAllArray(['and', ['status' => CheckInfo::NORMAL_STATUS], ['!=', 'user_channel_id', 0]]);
    }

    /**
     * 后台接口-检查工单催办
     * @param $params
     */
    public static function urge($params)
    {
        WorkOrderIndex::updateAll(['type' => WorkOrderIndex::URGENT_TYPE], ['id' => $params['id']]);
    }

    /**
     * 后台接口-工单提交
     * @param $params
     * @return string
     */
    public static function handleWorkOrder($params)
    {
        $user_info = UserChannelExtra::findOneArray(['user_channel_id' => $params['channel_id']]);
        if ($params['status'] == WorkOrderIndex::WORK_ORDER_STATUS_CN[0]) {
            foreach ($params['work_orders'] as $v) {
                WorkOrder::updateAll(['handle_notes' => $v['handle_notes'], 'handle_img' => $v['handle_img'], 'status' => WorkOrder::NOT_APPROVE], ['id' => $v['id']]);
                $model = new WorkOrderHistory();
                $model->work_order_id = $v['id'];
                $model->operation_id = $params['channel_id'];
                $model->operation_name = $user_info['realname'] ?? '';
                $model->operation_type = $user_info['operation_type'] ?? 1;
                $model->operation_status = WorkOrderHistory::NOT_APPROVE;
                $model->handle_img = $v['handle_img'] ?? '';
                $model->handle_notes = $v['handle_notes'] ?? '';
                $model->create_time = time();
                $model->save();
            }
            WorkOrderIndex::updateAll(['status' => WorkOrderIndex::NOT_APPROVE], ['id' => $params['id']]);
        } else {
            if (isset($params['principal_channel_id']) && $params['principal_channel_id']) {
                WorkOrderIndex::updateAll(['principal_channel_id' => $params['principal_channel_id']], ['id' => $params['id']]);
                return '';
            }
            foreach ($params['work_orders'] as $v) {
                WorkOrder::updateAll(['feedback_notes' => $v['feedback_notes'], 'feedback_status' => $v['feedback_status']], ['id' => $v['id']]);
                $model = new WorkOrderHistory();
                $model->work_order_id = $v['id'];
                $model->operation_id = $params['channel_id'];
                $model->operation_name = $user_info['realname'] ?? '';
                $model->operation_type = $user_info['operation_type'] ?? 1;
                $model->operation_status = $v['feedback_status'] == WorkOrderHistory::AGREE ? 2 : 3;
                $model->feedback_notes = $v['feedback_notes'] ?? '';
                $model->create_time = time();
                $model->save();
            }
            $check = WorkOrder::findOneArray(['feedback_status' => WorkOrder::NOT_AGREE, 'index_id' => $params['id']]);
            if ($check) {
                WorkOrderIndex::updateAll(['status' => WorkOrderIndex::UNTREATED], ['id' => $params['id']]);
                WorkOrder::updateAll(['status' => WorkOrderIndex::UNTREATED, 'handle_notes' => '', 'handle_img' => '', 'feedback_notes' => ''], ['id' => $params['id']]);
            } else {
                WorkOrderIndex::updateAll(['examine_status' => WorkOrderIndex::APPROVED], ['id' => $params['id']]);
            }
        }
    }
}