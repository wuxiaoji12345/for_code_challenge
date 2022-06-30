<?php


namespace backend\service;


use backend\models\Address;
use backend\models\AddressCheck;
use backend\models\AddressCheckDetail;
use backend\models\AddressCheckItem;
use backend\models\AddressLifeguard;
use common\libs\Helper;
use common\models\AddressFitnessCardSignin;
use common\models\AddressLifeguardCertificate;
use common\models\AddressThreePersonnel;
use common\models\CheckInfo;
use common\models\WorkOrderIndex;

class LargeScreenService
{
    const PAGE_SIZE = 20;

    /**
     * 大屏-数据概览
     * @param $params
     * @return array
     */
    public static function personnelRatio($params)
    {
        $where_data = [
            [
                [
                    'district' => 'district',
                ], '='
            ],
        ];
        $where1 = $where4 = Helper::makeWhere($where_data, $params);
        $where1[] = ['al.status' => AddressLifeguard::NORMAL_STATUS];
        $where_data = [
            [
                [
                    'district' => 'service_area',
                ], '='
            ],
        ];
        $where2 = Helper::makeWhere($where_data, $params);
        $where2[] = ['status' => AddressThreePersonnel::NORMAL_STATUS];
        $area_code = array_flip(CheckInfo::AREA_CODE_CN);
        $where3 = ['and', ['status' => CheckInfo::NORMAL_STATUS]];
        if (isset($params['district']) && $params['district']) {
            $where3[] = ['area_code' => $area_code[$params['district']]];
        }
        $where4[] = ['status' => Address::NORMAL_STATUS];
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => \common\models\Address::tableName() . ' a',
                'on' => 'al.swim_address_id = a.id'],
        ];
        $lifeguard = AddressLifeguard::findjoin('al', $join, ['count(*) num'], $where1, true, false)['num'] ?? 0;
        $three_personnel = AddressThreePersonnel::findAllArray($where2, ['count(*) num', 'type'], 'type', '', 'type');
        $checker = CheckInfo::findOneArray($where3, ['count(*) num'])['num'] ?? 0;
        $address = Address::findOneArray($where4, ['count(*) num'])['num'] ?? 0;
        return [
            '所有场所' => $address,
            '场所负责人' => $three_personnel[1]['num'] ?? 0,
            '救生组长' => $three_personnel[2]['num'] ?? 0,
            '水质检查员' => $three_personnel[3]['num'] ?? 0,
            '救生员' => $lifeguard,
            '检查员' => $checker,
        ];
    }

    /**
     * 大屏-检查概览
     * @param $params
     * @return array
     */
    public static function checkData($params)
    {
        $where_data = [
            [
                [
                    'district' => 'a.district',
                ], '='
            ],
        ];
        $where = Helper::makeWhere($where_data, $params);
        $where[] = ['!=', 'ac.type', ''];
        $where[] = ['ac.status' => AddressCheck::NORMAL_STATUS];
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => \common\models\Address::tableName() . ' a',
                'on' => 'ac.swim_address_id = a.id'],

        ];
        $data = AddressCheck::findJoin('ac', $join, ['count(*) num', 'ac.type'], $where, true, true, '', '', 'ac.type');
//        $data = AddressCheck::findAllArray(['!=', 'type', ''], ['count(*) num', 'type'], '', '', 'type');
        $re = ['全部' => 0];
        $all = 0;
        foreach ($data as $v) {
            $re[$v['type']] = (int)$v['num'];
            $all += $v['num'];
        }
        $re['全部'] = $all;
        return $re;
    }

    /**
     * 大屏-客流数
     * @param $params
     * @return array
     */
    public static function passengerFlow($params)
    {
        $where_data = [
            [
                [
                    'district' => 'district',
                ], '='
            ],
        ];
        $where = Helper::makeWhere($where_data, $params);
        $where[] = ['>', 'date' , date('Y-m-d', time() - 60 * 24 * 3600)];
        $select = ['count(*) num', 'WEEK(date) time'];
        $data = AddressFitnessCardSignin::findAllArray($where, $select, '', '', 'time');
        $re = [];
        for ($i = 7; $i >= 0; $i--) {
            $week = date('W', time() - ($i * 7 * 3600 * 24));
            $re[(int)$week] = 0;
        }
        foreach ($data as $v) {
            if (isset($re[$v['time']])) {
                $re[$v['time']] = $v['num'];
            }
        }
        return $re;
    }

    /**
     * 大屏-客流总数与场馆总数
     * @param $params
     * @return array
     */
    public static function passengerAddressStatic($params)
    {
        $where_data = [
            [
                [
                    'district' => 'district',
                ], '='
            ],
        ];
        $where = $where1 = Helper::makeWhere($where_data, $params);
        $select = ['count(*) num'];
        $where[] = ['date' => date('Ymd')];
        $passenger_num = AddressFitnessCardSignin::findOneArray($where, $select)['num'] ?? 0;
        $where1[] = ['status' => Address::NORMAL_STATUS];
        $where1[] = ['disabled' => 0];
        $where1[] = ['collapse_flag' => 0];
        $where1[] = ['approval_status' => 'S'];
        return [
            'address_num' => Address::findOneArray($where1, $select)['num'] ?? 0,
            'passenger_num' => $passenger_num
        ];
    }

    /**
     * 大屏-客流按区统计（客流明细）
     * @param $params
     * @return array
     */
    public static function passengerFlowArea($params)
    {
        $where_data = [
            [
                [
                    'district' => 'district',
                ], '='
            ],
        ];
        $where = Helper::makeWhere($where_data, $params);
        $select = ['count(*) num', 'district'];
        $where[] = ['date' => date('Ymd')];
        $data = AddressFitnessCardSignin::findAllArray($where, $select, '', '', 'district');
        $re = [];
        foreach ($data as $v) {
            $re[$v['district']] = $v['num'];
        }
        return $re;
    }

    /**
     * 大屏-开放检查大屏-项目异常
     * @param $params
     * @return int[]
     */
    public static function workOrderStatus($params)
    {
        $where = ['and'];
        if (isset($params['district']) && $params['district']) $where[] = ['district' => $params['district']];
        $select = ['count(*) num'];
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => Address::tableName() . ' a',
                'on' => 'w.venue_id = a.id'],
        ];
//        return array_merge($where, [['w.status' => WorkOrderIndex::NOT_APPROVE]]);
        $untreated = WorkOrderIndex::findJoin('w', $join, $select, array_merge($where, [['w.status' => WorkOrderIndex::UNTREATED]]), true, false)['num'] ?? 0;
        $not_approve = WorkOrderIndex::findJoin('w', $join, $select, array_merge($where, [['w.status' => WorkOrderIndex::NOT_APPROVE], ['examine_status' => WorkOrderIndex::NOT_APPROVE]]), true, false)['num'] ?? 0;
        $approved = WorkOrderIndex::findJoin('w', $join, $select, array_merge($where, [['examine_status' => WorkOrderIndex::APPROVED]]), true, false)['num'] ?? 0;
        return [
            'all' => $untreated + $not_approve + $approved,
            'untreated' => $untreated,
            'not_approve' => $not_approve,
            'approved' => $approved,
        ];

    }

    /**
     * 大屏-开放检查大屏-场馆合格率
     * @param $params
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]|null
     */
    public static function addressQualifiedRate($params)
    {
        $page_info = Helper::makePageInfo($params, self::PAGE_SIZE);

        $where_data = [
            [
                [
                    'district' => 'district',
                ], '='
            ],
        ];
        $where = $all_where = Helper::makeWhere($where_data, $params);
        $where[] = ['acd.check_status' => AddressCheckDetail::APPROVED];
        $where[] = ['ac.status' => AddressCheck::NORMAL_STATUS];
        $all_where[] = ['ac.status' => AddressCheck::NORMAL_STATUS];
        $join = [
            [
                'type' => 'inner JOIN',
                'table' => AddressCheck::tableName() . ' ac',
                'on' => 'ac.id = acd.swim_address_check_id'],
            [
                'type' => 'LEFT JOIN',
                'table' => \common\models\Address::tableName() . ' a',
                'on' => 'ac.swim_address_id = a.id'],
        ];
//        $where[] = ['acd.check_status' => AddressCheckDetail::ABNORMAL_STATUS];
        $data = AddressCheckDetail::findJoin('acd', $join, ['count(*) num', 'a.name', 'a.district', 'from_unixtime(ac.create_time,"%Y-%m-%d %H:%i:%s") time'], $where, true, true, 'acd.id desc', '', 'acd.swim_address_check_id', '', $page_info);
        $all = AddressCheckDetail::findJoin('acd', $join, ['count(*) num', 'acd.check_status'], $all_where, true, true, '', 'check_status', 'acd.check_status');
        $qualified = $all[1]['num'] ?? 0;
        $not_qualified = $all[2]['num'] ?? 0;
        $all = $qualified + $not_qualified;
        $data['qualified_rate'] = (string)($all == 0 ? '-' : ceil(($qualified * 100) / $all) . '%');
        return $data;
    }

    /**
     * 大屏-开放检查大屏-场所证照有效率列表
     * @param $params
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]|null
     */
    public static function addressLicenseQualifiedRate($params)
    {
        $page_info = Helper::makePageInfo($params, self::PAGE_SIZE);

        $where_data = [
            [
                [
                    'district' => 'district',
                ], '='
            ],
        ];
        $where = Helper::makeWhere($where_data, $params);
        $where[] = ['status' => Address::NORMAL_STATUS];
        $data = Address::findJoin('', [], ['district', 'name', 'high_risk_status', 'high_risk_deadline time'], $where, true, true, 'id desc', '', '', '', $page_info);
        $all = Address::find()->where($where)->count();
        $where[] = ['!=', 'high_risk_status', Address::ABNORMAL_STATUS];
        $qualified = Address::find()->where($where)->count();
        if ($data['list']) {
            foreach ($data['list'] as &$v) {
                $v['high_risk_status'] = Address::TIME_LIMIT_CN[$v['high_risk_status']];
                //临时修改
                if($v['high_risk_status'] == '过期'){
                    $v['high_risk_status'] = '即将过期';
                } else {
                    $v['high_risk_status'] = '有效';
                }
            }
        }
        $data['qualified_rate'] = $all == 0 ? '-' : ceil(($qualified * 100) / $all) . '%';
        return $data;
    }

    /**
     * 大屏-开放检查大屏-人员证照有效率列表
     * @param $params
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]|null
     */
    public static function certificateQualifiedRate($params)
    {
        //首先要看看有没有过期的证书却没有改为过期的
        $date = date('Y-m-d');
        $check = AddressThreePersonnel::findOneArray(['and', ['<', 'date_of_issuance_end', $date], ['!=', 'card_status', AddressThreePersonnel::ABNORMAL_STATUS]]);
        if ($check) {
            AddressThreePersonnel::updateAll(['card_status' => AddressLifeguardCertificate::ABNORMAL_STATUS], ['<', 'date_of_issuance_end', $date]);
        }
        $page_info = Helper::makePageInfo($params, self::PAGE_SIZE);

        //加上区域的搜索条件
        $join1 = [
            [
                'type' => 'LEFT JOIN',
                'table' => Address::tableName() . ' a',
                'on' => 'atp.address_id != 0 and atp.address_id = a.address_id'],

        ];
        $where_data = [
            [
                [
                    'district' => 'service_area',
                ], '='
            ],
        ];
        $where1 = Helper::makeWhere($where_data, $params);
        $where1[] = ['=', 'atp.status', AddressThreePersonnel::NORMAL_STATUS];
        $data = AddressThreePersonnel::findjoin('atp', $join1, ['atp.name', 'service_area', 'a.name address_name', 'atp.type', 'atp.card_status', 'atp.date_of_issuance_end'], $where1, true, true, 'atp.card_status asc,atp.id desc', '', '', [], $page_info);
        $data2 = AddressThreePersonnel::findjoin('atp', $join1, ['count(*) num', 'atp.card_status'], $where1, true, true, '', '', 'card_status');
//        $re = [];
//        $re[Address::TIME_LIMIT_CN[3]] = ($data1[3]['num'] ?? 0) + ($data2[3]['num'] ?? 0);
        $all = 0;
        $qualified = 0;
        if ($data['list']) {
            foreach ($data['list'] as &$v) {
//                $v['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $v['card_status'] = AddressLifeguardCertificate::TIME_LIMIT_CN[(int)$v['card_status']] ?? '';
                $v['type'] = AddressLifeguardCertificate::THREE_PERSONNEL_TYPE_CN[(int)$v['type']] ?? '';
            }
            unset($v);
        }
        foreach ($data2 as $v) {
            if ((int)$v['card_status'] == AddressLifeguardCertificate::NORMAL_STATUS) $qualified = $v['num'];
            $all += $v['num'];
        }
        $data['qualified_rate'] = $all == 0 ? '-' : ceil(($qualified * 100) / $all) . '%';
        return $data;
    }

    /**
     * 大屏-检查人员检查次数与待审核工单
     * @param $params
     * @return array
     */
    public static function checkerCheckInfo($params)
    {
//        $page_info = Helper::makePageInfo($params, self::PAGE_SIZE);
        $area_code = array_flip(CheckInfo::AREA_CODE_CN);
        $where3 = ['and', ['c.status' => CheckInfo::NORMAL_STATUS]];
        if (isset($params['district']) && $params['district']) {
            $where3[] = ['area_code' => $area_code[$params['district']]];
        }
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => AddressCheck::tableName() . ' a',
                'on' => 'c.user_channel_id = a.user_channel_id'],
        ];
        $check_info = CheckInfo::findJoin('c', $join, ['count(*) check_num', 'c.user_channel_id', 'c.name'], $where3, true, true, '', 'user_channel_id', 'c.user_channel_id');
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => WorkOrderIndex::tableName() . ' w',
                'on' => 'c.user_channel_id = w.commit_id or c.user_channel_id = w.principal_channel_id'],
        ];
        $where3[] = ['w.status' => WorkOrderIndex::NOT_APPROVE];
        $where3[] = ['w.examine_status' => WorkOrderIndex::NOT_APPROVE];
        $order_info = CheckInfo::findJoin('c', $join, ['count(*) order_num', 'c.user_channel_id'], $where3, true, true, '', 'user_channel_id', 'c.user_channel_id');
        $re = [];
        foreach ($check_info as $k => $v) {
            $v['order_num'] = $order_info[$k]['order_num'] ?? 0;
            $re[] = $v;
        }
        return $re;
    }
}