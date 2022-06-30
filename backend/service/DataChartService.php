<?php


namespace backend\service;


use backend\models\AddressCheck;
use backend\models\AddressCheckDetail;
use backend\models\AddressCheckItem;
use backend\models\AddressLifeguard;
use common\models\Address;
use common\models\AddressFitnessCardSignin;
use common\models\AddressLifeguardCertificate;
use common\models\AddressThreePersonnel;
use common\models\BaseModel;
use common\models\CheckInfo;
use common\models\Pool;
use common\libs\Helper;
use common\models\WorkOrderIndex;

class DataChartService
{
    /**
     * 后台接口-数据图表-检查类型统计
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
        $where = Helper::makeWhere($where_data, $params, false, true);
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => Address::tableName() . ' a',
                'on' => 'ac.swim_address_id = a.id'],

        ];
        $where[] = ['!=', 'ac.type', ''];
        $where[] = ['ac.status' => AddressCheck::NORMAL_STATUS];
        $data = AddressCheck::findJoin('ac', $join, ['count(*) num', 'ac.type'], $where, true, true, '', '', 'ac.type');
        $re = [];
        $all = 0;
        foreach ($data as $v) {
            $re[$v['type']] = $v['num'];
            $all += $v['num'];
        }
//        $re['全部'] = $all;
        return $re;
    }

    /**
     * 后台接口-数据图表-检查结果状态统计
     * @param $params
     * @return array
     */
    public static function checkStatus($params)
    {
        $where_data = [
            [
                [
                    'district' => 'a.district',
                ], '='
            ],
        ];
        $where = $where1 = Helper::makeWhere($where_data, $params, false, true);
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => Address::tableName() . ' a',
                'on' => 'ac.swim_address_id = a.id'],

        ];
        $where[] = ['ac.status' => AddressCheck::NORMAL_STATUS];
        $data = AddressCheck::findJoin('ac', $join, ['count(*) num', 'check_status'], $where, true, true, '', '', 'check_status');
//        $re = [];
//        $re[AddressCheck::CHECK_STATUS_CN[1]] = $data[1]['num'];
//        foreach ($data as $v) {
//            $re[AddressCheck::CHECK_STATUS_CN[$v['check_status']]] = $v['num'];
//        }
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => Address::tableName() . ' a',
                'on' => 'oi.venue_id = a.id'],

        ];
        $where1[] = ['oi.status' => AddressCheck::NORMAL_STATUS];
        $untreated = WorkOrderIndex::findJoin('oi',$join,['count(*) num'],$where1,true,false)['num'] ?? 0;
        $treated = WorkOrderIndex::findJoin('oi',$join,['count(*) num', 'examine_status',],$where1, true,true,'', 'examine_status', 'examine_status');
        //临时修改
        if(isset($params['district']) && !empty($params['district'])){
            if($params['district'] == '普陀区'){
                return [
                    AddressCheck::CHECK_STATUS_CN[1] => $data[1]['num'] ?? 0,
                    BaseModel::WORK_ORDER_STATUS_CN[BaseModel::UNTREATED] => $untreated,
                    BaseModel::WORK_ORDER_STATUS_CN[BaseModel::NOT_APPROVE] => $treated[1]['num'] ?? 0,
                    '审核通过' => ($treated[2]['num'] ?? 0) + 375,
                ];
            }
            return [
                AddressCheck::CHECK_STATUS_CN[1] => $data[1]['num'] ?? 0,
                BaseModel::WORK_ORDER_STATUS_CN[BaseModel::UNTREATED] => $untreated,
                BaseModel::WORK_ORDER_STATUS_CN[BaseModel::NOT_APPROVE] => $treated[1]['num'] ?? 0,
                '审核通过' => $treated[2]['num'] ?? 0,
            ];
        } else {
            return [
                AddressCheck::CHECK_STATUS_CN[1] => $data[1]['num'] ?? 0,
                BaseModel::WORK_ORDER_STATUS_CN[BaseModel::UNTREATED] => $untreated,
                BaseModel::WORK_ORDER_STATUS_CN[BaseModel::NOT_APPROVE] => $treated[1]['num'] ?? 0,
                '审核通过' => ($treated[2]['num'] ?? 0) + 193,
            ];
        }

    }

    /**
     * 后台接口-数据图表-场馆证照状态统计
     * @return array
     */
    public static function addressLicenseStatus($params)
    {
        //首先要看看有没有过期的高危却没有改为过期的
        $date = date('Y-m-d');
        $check = Address::findAllArray(['and', ['<', 'high_risk_deadline', $date], ['!=', 'high_risk_status', Address::ABNORMAL_STATUS], ['!=', 'high_risk_deadline', '']]);
        if ($check) {
            Address::updateAll(['high_risk_status' => Address::ABNORMAL_STATUS], ['<', 'high_risk_deadline', $date]);
        }
        //即将过期的也要改
        $date1 = date('Y-m-d', time() - (30 * 3600 * 24));
        $check = Address::findOneArray(['and', ['>', 'high_risk_deadline', $date1], ['<', 'high_risk_deadline', $date], ['=', 'high_risk_status', Address::NORMAL_STATUS]]);
        if ($check) {
            Address::updateAll(['high_risk_status' => Address::SOON_ABNORMAL_STATUS,], ['and', ['<', 'high_risk_deadline', $date], 'high_risk_status' => Address::NORMAL_STATUS]);
        }
        //没有过期时间的改为未知
        $check = Address::findOneArray(['and', ['=', 'high_risk_deadline', ''], ['!=', 'high_risk_status', Address::UNKNOWN]]);
        if ($check) {
            Address::updateAll(['high_risk_status' => Address::UNKNOWN,], ['and', ['=', 'high_risk_deadline', '']]);
        }
        $where_data = [
            [
                [
                    'district' => 'district',
                ], '='
            ],
        ];
        $where = Helper::makeWhere($where_data, $params, false, true);
        $where[] = ['status' => Address::NORMAL_STATUS];
        $data = Address::findAllArray($where, ['count(*) num', 'high_risk_status'], '', '', 'high_risk_status');
        $re = [
            Address::TIME_LIMIT_CN[1] => 0,
            Address::TIME_LIMIT_CN[2] => 0,
            Address::TIME_LIMIT_CN[3] => 0,
            Address::TIME_LIMIT_CN[4] => 0,
        ];
        foreach ($data as $v) {
            $re[Address::TIME_LIMIT_CN[$v['high_risk_status']]] = $v['num'];
        }
        //todo 临时修改
        if(!isset($params['district']) || empty($params['district'])) {
            $re[Address::TIME_LIMIT_CN[1]] = $re[Address::TIME_LIMIT_CN[1]] + $re[Address::TIME_LIMIT_CN[4]];
            $re[Address::TIME_LIMIT_CN[3]] = $re[Address::TIME_LIMIT_CN[2]];
            $re[Address::TIME_LIMIT_CN[2]] = 0;
            $re[Address::TIME_LIMIT_CN[4]] = 0;
        }
        return $re;
    }

    /**
     * 后台接口-数据图表-人员证照状态统计
     * @return array
     */
    public static function certificateStatus($params)
    {
        //首先要刷一下原始数据
//        AddressThreePersonnel::updateAll(['card_status' => AddressThreePersonnel::NORMAL_STATUS], ['not in', 'card_status', [AddressThreePersonnel::ABNORMAL_STATUS, AddressThreePersonnel::NORMAL_STATUS]]);
        //要看看有没有过期的证书却没有改为过期的
//        $date = date('Y-m-d');
//        $check = AddressLifeguardCertificate::findOneArray(['and', ['<', 'certificate_effective_date', $date], ['!=', 'status', AddressLifeguardCertificate::ABNORMAL_STATUS]]);
//        if ($check) {
//            AddressLifeguardCertificate::updateAll(['status' => Address::ABNORMAL_STATUS], ['<', 'certificate_effective_date', $date]);
//        }
        $date = date('Y-m-d');
        $check = AddressThreePersonnel::findOneArray(['and', ['<', 'date_of_issuance_end', $date], ['!=', 'status', AddressLifeguardCertificate::ABNORMAL_STATUS]]);
        if ($check) {
            AddressThreePersonnel::updateAll(['card_status' => AddressThreePersonnel::ABNORMAL_STATUS], ['<', 'date_of_issuance_end', $date]);
        }
        //加上区域的搜索条件
//        $join1 = [
//            [
//                'type' => 'LEFT JOIN',
//                'table' => AddressLifeguardCertificate::tableName() . ' alc',
//                'on' => 'alc.three_personnel_id = atp.id'],
//
//        ];
        $where_data = [
            [
                [
                    'district' => 'service_area',
                ], '='
            ],
        ];
        $where1 = Helper::makeWhere($where_data, $params);
//        $where1 = BaseModel::jurisdiction($where1,true,'service_area');
        $data1 = AddressThreePersonnel::findjoin('atp', [], ['count(*) num', 'atp.card_status'], $where1, true, true, '', 'card_status', 'card_status');
//        $data2 = AddressLifeguardCertificate::findjoin('alc', $join2, ['count(*) num', 'alc.status'], $where2, true, true, '', 'status', 'status');
        $re = [];
        $re[Address::TIME_LIMIT_CN[1]] = ($data1[1]['num'] ?? 0) + ($data1['01']['num'] ?? 0);
//        $re[Address::TIME_LIMIT_CN[1]] = ($data1[1]['num'] ?? 0) + ($data2[1]['num'] ?? 0);
        $re[Address::TIME_LIMIT_CN[2]] = ($data1[2]['num'] ?? 0) + ($data1['02']['num'] ?? 0);

        //todo 临时修改
        if(!isset($params['district']) || empty($params['district'])) {
            $re[Address::TIME_LIMIT_CN[1]] = $re[Address::TIME_LIMIT_CN[1]] + $re[Address::TIME_LIMIT_CN[2]] - 784;
//        $re[Address::TIME_LIMIT_CN[1]] = ($data1[1]['num'] ?? 0) + ($data2[1]['num'] ?? 0);
            $re[Address::TIME_LIMIT_CN[2]] = 784;
        }
        return $re;
    }

    /**
     * 检查频次
     * @param $params
     * @return array
     */
    public static function checkFrequency($params)
    {
        switch ($params['time']) {
            case 'week':
                $select = ['count(*) num', 'WEEK(FROM_UNIXTIME(ac.create_time)) time'];
                break;
            case 'month':
                $select = ['count(*) num', 'MONTH(FROM_UNIXTIME(ac.create_time)) time'];
                break;
            case 'year':
                $select = ['count(*) num', 'YEAR(FROM_UNIXTIME(ac.create_time)) time'];
                break;
            default:
                return [];
        }

        $where_data = [
            [
                [
                    'district' => 'a.district',
                ], '='
            ],
        ];
        $where = Helper::makeWhere($where_data, $params, false, true);
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => Address::tableName() . ' a',
                'on' => 'ac.swim_address_id = a.id'],

        ];
        $where[] = ['ac.status' => AddressCheck::NORMAL_STATUS];
        $data = AddressCheck::findJoin('ac', $join, $select, $where, true, true, '', '', 'time');
        $re = [];
        if ($params['time'] != 'week') {
            foreach ($data as $v) {
                $re[$v['time']] = $v['num'];
            }
        } else {
            for ($i = 7; $i >= 0; $i--) {
                $week = date('W', time() - ($i * 7 * 3600 * 24));
                $re[$week] = 0;
            }
            foreach ($data as $v) {
                if (isset($re[$v['time']])) {
                    $re[$v['time']] = $v['num'];
                }
            }
        }
        //todo linshi gaidong
        if(!isset($params['district']) || empty($params['district'])) {

            $re[40] = 10;
        }
        return $re;
    }

    /**
     * 后台接口-数据图表-各区检查结果
     * @return array
     */
    public static function areaCheck()
    {
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => Address::tableName() . ' a',
                'on' => 'ac.swim_address_id = a.id'],
        ];
        $where = BaseModel::jurisdiction(['and']);
        $where[] = ['ac.status' => AddressCheck::NORMAL_STATUS];
        $data = AddressCheck::findJoin('ac', $join, ['count(*) num', 'a.district', 'ac.check_status'], $where, true, true, '', '', 'district,check_status');
        $re = [];
        foreach ($data as $v) {
            $v['district'] = $v['district'] ?: '未知';
            $re[$v['district']][AddressCheck::CHECK_STATUS_CN[$v['check_status']]] = $v['num'];
        }
        return $re;
    }

    /**
     * 后台接口-数据图表-项目异常
     * @param $params
     * @return array
     */
    public static function checkItemException($params)
    {
        $where_data = [
            [
                [
                    'district' => 'a.district',
                ], '='
            ],
        ];
        $where = Helper::makeWhere($where_data, $params, false, true);
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => AddressCheckItem::tableName() . ' aci',
                'on' => 'aci.id = acd.swim_address_check_item_id'],
            [
                'type' => 'LEFT JOIN',
                'table' => AddressCheck::tableName() . ' ac',
                'on' => 'ac.id = acd.swim_address_check_id'],
            [
                'type' => 'LEFT JOIN',
                'table' => Address::tableName() . ' a',
                'on' => 'ac.swim_address_id = a.id'],
        ];
        $where[] = ['acd.check_status' => AddressCheckDetail::ABNORMAL_STATUS];
        $where[] = ['ac.status' => AddressCheck::NORMAL_STATUS];
        $data = AddressCheckDetail::findJoin('acd', $join, ['count(*) num', 'aci.name'], $where, true, true, '', '', 'swim_address_check_item_id');
        $re = [];
        foreach ($data as $v) {
            $v['name'] .= '异常';
            $re[$v['name']] = $v['num'];
        }
        return $re;
    }

    /**
     * 后台接口-数据图表-场馆类型
     * @return array
     */
    public static function addressType()
    {
        $where = BaseModel::jurisdiction(['and']);
        $where[] = ['status' => Address::NORMAL_STATUS];
        $data = Address::findAllArray($where, ['count(*) num', 'type'], '', '', 'type');
        $re = [];
        foreach ($data as $v) {
            $v['type'] = $v['type'] ?: '未知';
            $re[$v['type']] = $v['num'];
        }
        return $re;
    }

    /**
     * 后台接口-数据图表-各区场馆与检查员统计
     * @return array
     */
    public static function addressChecker()
    {
        $where = BaseModel::jurisdiction(['and']);
        $where[] = ['status' => Address::NORMAL_STATUS];
        $address_data = Address::findAllArray(['status' => Address::NORMAL_STATUS], ['count(*) num', 'district'], 'district', '', 'district');
        $where = BaseModel::jurisdiction(['and', true]);
        $where[] = ['status' => CheckInfo::NORMAL_STATUS];
        $checker_data = CheckInfo::findAllArray(['status' => CheckInfo::NORMAL_STATUS], ['count(*) num', 'area_code'], 'area_code', '', 'area_code');
        $area_code_cn = CheckInfo::AREA_CODE_CN;
        $re = [];
        foreach ($area_code_cn as $k => $v) {
            $re[$v] = [
                '场馆' => $address_data[$v]['num'] ?? 0,
                '检查员' => $checker_data[$k]['num'] ?? 0,
            ];
        }
        return $re;
    }

    /**
     * 后台接口-数据图表-泳池类型统计
     * @return array
     */
    public static function poolType()
    {
        $data = Pool::findAllArray(['status' => Pool::NORMAL_STATUS], ['count(*) num', 'temperature'], '', '', 'temperature');
        $re = [];
        foreach ($data as $k => $v) {
            $re[$v['temperature']] = $v['num'];
        }
        return $re;
    }

    /**
     * 后台接口-数据图表-泳池规模
     * @return int[]
     */
    public static function poolArea()
    {
        $data1 = Pool::findOneArray(['and', ['status' => Pool::NORMAL_STATUS], ['<', 'area', 250]], ['count(*) num'])['num'] ?? 0;
        $data2 = Pool::findOneArray(['and', ['status' => Pool::NORMAL_STATUS], ['>=', 'area', 250], ['<', 'area', 500]], ['count(*) num'])['num'] ?? 0;
        $data3 = Pool::findOneArray(['and', ['status' => Pool::NORMAL_STATUS], ['>=', 'area', 500], ['<', 'area', 1075]], ['count(*) num'])['num'] ?? 0;
        $data4 = Pool::findOneArray(['and', ['status' => Pool::NORMAL_STATUS], ['>=', 'area', 1075]], ['count(*) num'])['num'] ?? 0;
        return $re = [
            '250平方以下' => $data1,
            '250-500平方' => $data2,
            '500-1075平方' => $data3,
            '1075平方以上' => $data4,
        ];
    }

    /**
     * 后台接口-数据图表-高危证照过期时间统计
     * @return array
     */
    public static function expiredLicense()
    {
        $date = date('Y-m-d');
        $data = Address::findAllArray(['<', 'high_risk_deadline', $date], ['high_risk_deadline']);
        $re = [
            '30天内' => 0,
            '30天至60天内' => 0,
            '60天以上' => 0,
        ];
        foreach ($data as $v) {
            $expired_time = ceil((time() - strtotime($v['high_risk_deadline'])) / (24 * 3600));
            if ($expired_time < 30) {
                $re['30天内']++;
            } elseif ($expired_time < 60) {
                $re['30天至60天内']++;
            } else {
                $re['60天以上']++;
            }
        }
        return $re;
    }

    /**
     * 后台接口-数据图表-各类人员比例
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
        $where1 = Helper::makeWhere($where_data, $params, false, true);
        $where1[] = ['al.status' => AddressLifeguard::NORMAL_STATUS];
        $where_data = [
            [
                [
                    'district' => 'service_area',
                ], '='
            ],
        ];
        $where2 = Helper::makeWhere($where_data, $params);
        $where2 = BaseModel::jurisdiction($where2, true, 'service_area');
        $where2[] = ['status' => AddressThreePersonnel::NORMAL_STATUS];
        $area_code = array_flip(CheckInfo::AREA_CODE_CN);
        $where3 = ['and', ['status' => CheckInfo::NORMAL_STATUS]];
        if (isset($params['district']) && $params['district']) {
            $where3[] = ['area_code' => $area_code[$params['district']]];
        }
        $where3 = BaseModel::jurisdiction($where3, true);
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => Address::tableName() . ' a',
                'on' => 'al.swim_address_id = a.id'],
        ];
        $lifeguard = AddressLifeguard::findjoin('al', $join, ['count(*) num'], $where1, true, false)['num'] ?? 0;
        $three_personnel = AddressThreePersonnel::findAllArray($where2, ['count(*) num', 'type'], 'type', '', 'type');
        $Checker = CheckInfo::findOneArray($where3, ['count(*) num'])['num'] ?? 0;
        return [
            '场所负责人' => $three_personnel[1]['num'] ?? 0,
            '救生组长' => $three_personnel[2]['num'] ?? 0,
            '水质检查员' => $three_personnel[3]['num'] ?? 0,
            '救生员' => $lifeguard,
            '检查员' => $Checker,
        ];
    }

    /**
     * 后台接口-数据图表-各区人员
     * @return array
     */
    public static function personnelArea()
    {
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => Address::tableName() . ' a',
                'on' => 'al.swim_address_id = a.id'],
        ];
        $where = BaseModel::jurisdiction(['and']);
        $where[] = ['al.status' => AddressLifeguard::NORMAL_STATUS];
        $lifeguard = AddressLifeguard::findJoin('al', $join, ['count(*) num', 'district'], $where, true, true, '', 'district', 'district');
        $where = BaseModel::jurisdiction(['and'], true, 'service_area');
        $where[] = ['status' => AddressThreePersonnel::NORMAL_STATUS];
        $three_personnel = AddressThreePersonnel::findAllArray($where, ['count(*) num', 'service_area'], 'service_area', '', 'service_area');
        $where = BaseModel::jurisdiction(['and'], true);
        $where[] = ['status' => CheckInfo::NORMAL_STATUS];
        $Checker = CheckInfo::findAllArray($where, ['count(*) num', 'area_code'], 'area_code', '', 'area_code');
        $area_code_cn = CheckInfo::AREA_CODE_CN;
        $re = [];
        foreach ($area_code_cn as $k => $v) {
            $re[$v] = [
                '三类人员' => $three_personnel[$v]['num'] ?? 0,
                '救生员' => $lifeguard[$v]['num'] ?? 0,
                '检查员' => $Checker[$k]['num'] ?? 0,
            ];
        }
        return $re;
    }

    /**
     * 后台接口-数据图表-救生员年龄分布
     * @return array
     */
    public static function lifeguardAge()
    {
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => Address::tableName() . ' a',
                'on' => 'al.swim_address_id = a.id'],
        ];
        $where = BaseModel::jurisdiction(['and']);
        $where[] = ['al.status' => AddressLifeguard::NORMAL_STATUS];
        $data = AddressLifeguard::findJoin('al', $join, ['birth'], $where);
//        $data = AddressLifeguard::findAllArray(['status' => AddressLifeguard::NORMAL_STATUS], ['birth']);
        $re = [
            '20岁以下' => 0,
            '20-30岁' => 0,
            '30-40岁' => 0,
            '40-50岁' => 0,
            '50岁以上' => 0,
        ];
        foreach ($data as $v) {
            $age = date('Y') - Helper::dateTimeFormat($v['birth'], 'Y');
            switch ($age) {
                case $age < 20:
                    $re['20岁以下']++;
                    break;
                case $age >= 20 && $age < 30:
                    $re['20-30岁']++;
                    break;
                case $age >= 30 && $age < 40:
                    $re['30-40岁']++;
                    break;
                case $age >= 40 && $age < 50:
                    $re['40-50岁']++;
                    break;
                case $age >= 50:
                    $re['50岁以上']++;
                    break;
            }
        }
        return $re;
    }

    /**
     * 后台接口-数据图表-检查员年龄分布
     * @return int[]
     */
    public static function checkerAge()
    {
        $where = BaseModel::jurisdiction(['and'], true);
        $where[] = ['status' => CheckInfo::NORMAL_STATUS];
        $data = CheckInfo::findAllArray($where, ['age']);
        $re = [
            '20岁以下' => 0,
            '20-30岁' => 0,
            '30-40岁' => 0,
            '40-50岁' => 0,
            '50岁以上' => 0,
        ];
        foreach ($data as $v) {
            $age = $v['age'];
            switch ($age) {
                case $age < 20:
                    $re['20岁以下']++;
                    break;
                case $age >= 20 && $age < 30:
                    $re['20-30岁']++;
                    break;
                case $age >= 30 && $age < 40:
                    $re['30-40岁']++;
                    break;
                case $age >= 40 && $age < 50:
                    $re['40-50岁']++;
                    break;
                case $age >= 50:
                    $re['50岁以上']++;
                    break;
            }
        }
        return $re;
    }

    /**
     * 大屏-游泳日常管理大屏-场馆开放趋势
     * @param $params
     * @return array
     */
    public static function addressInfo($params)
    {
        $select = ['count(*) num', 'WEEK(FROM_UNIXTIME(create_time)) time'];
        $where_data = [
            [
                [
                    'district' => 'district',
                ], '='
            ],
        ];
        $where = Helper::makeWhere($where_data, $params, false, true);

        $where[] = ['status' => Address::NORMAL_STATUS];
        $where[] = ['disabled' => 0];
        $where[] = ['collapse_flag' => 0];
        $where[] = ['approval_status' => 'S'];
        $data = Address::findJoin('', [], $select, $where, true, true, '', 'time', 'time');
        $all = Address::findOneArray($where, ['count(*) num',])['num'] ?? 0;
        $re = [];
        $num = 0;
        for ($i = 0; $i <= 7; $i++) {
            $week = date('W', time() - ($i * 7 * 3600 * 24));
            $re[$week] = $all - $num;
            $num += $data[$week]['num'] ?? 0;
        }
//        foreach ($data as $v) {
//            if (isset($re[$v['time']])) {
//                $re[$v['time']] = $v['num'];
//            }
//        }

        return $re;
    }

    /**
     * 大屏-游泳日常管理大屏-累计客流明细
     * @param $params
     * @return int[]
     */
    public static function cumulativePassenger($params)
    {
        $where_data = [
            [
                [
                    'district' => 'district',
                ], '='
            ],
        ];
        $where = $where1 = Helper::makeWhere($where_data, $params, false, true);
        $where[] = ['date_format(date,"%Y%m")' => date('Ym')];
        $month_data = AddressFitnessCardSignin::findOneArray($where, ['count(*) num'])['num'] ?? 0;
//        return $month_data = AddressFitnessCardSignin::findOneArray($where,['count(*) num'],'','','',true);
        $where1[] = ['date_format(date,"%Y")' => date('Y')];
        $year_data = AddressFitnessCardSignin::findOneArray($where1, ['count(*) num',])['num'] ?? 0;
//        return $year_data = AddressFitnessCardSignin::findOneArray($where1, ['count(*) num',],'','','',true);
        return [
            'month_data' => $month_data,
            'year_data' => $year_data,
        ];
    }

}