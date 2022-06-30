<?php


namespace backend\service;


use backend\models\AddressCheckDetail;
use backend\models\AddressCheckItem;
use backend\models\UserChannelExtra;
use common\libs\Helper;
use common\models\Address;
use common\models\AddressCheck;
use common\models\AddressCoach;
use common\models\AddressContactPerson;
use common\models\AddressLifeguard;
use common\models\AddressLifeguardCertificate;
use common\models\AddressThreePersonnel;
use common\models\BaseModel;
use common\models\BkUser;
use common\models\CheckInfo;
use common\models\Pool;
use common\models\WorkOrderIndex;
use yii\helpers\ArrayHelper;

class AddressService
{
    const PAGE_SIZE = 20;

    /**
     * 后台接口-游泳场馆信息列表
     * @param $params
     * @param bool $is_wechat
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]|null
     */
    public static function getList($params, $is_wechat = false)
    {
        //首先要看看有没有过期的高危却没有改为过期的
        $date = date('Y-m-d');
        $check = Address::findAllArray(['and', ['<', 'high_risk_deadline', $date], ['!=', 'high_risk_status', Address::ABNORMAL_STATUS]]);
        if ($check) {
            Address::updateAll(['high_risk_status' => Address::ABNORMAL_STATUS], ['<', 'high_risk_deadline', $date]);
        }
        //即将过期的也要改
        $date1 = date('Y-m-d', time() - (30 * 3600 * 24));
        $check = Address::findOneArray(['and', ['<', 'high_risk_deadline', $date1], ['>', 'high_risk_deadline', $date], ['=', 'high_risk_status', Address::NORMAL_STATUS]]);
        if ($check) {
            Address::updateAll(['high_risk_status' => Address::SOON_ABNORMAL_STATUS,], ['and', ['<', 'high_risk_deadline', $date], ['>', 'high_risk_deadline', $date], 'high_risk_status' => Address::NORMAL_STATUS]);
        }
        $page_info = Helper::makePageInfo($params, self::PAGE_SIZE);
        $where_data = [
            [
                [
                    'name' => 'name',
                ], 'like'
            ],
            [
                [
                    'district' => 'district',
                    'high_risk_status' => 'high_risk_status',
                    'type' => 'type',
                ], '='
            ],
        ];
//        return \Yii::$app->user->identity;
        $where = Helper::makeWhere($where_data, $params, false, true);
        $where[] = ['status' => Address::NORMAL_STATUS];
        $order = 'high_risk_status desc,update_time desc,high_risk_deadline desc';
        if (isset($params['user_id'])) {
            $user_model = BkUser::findOne(['id' => $params['user_id']]);
            if ($user_model) {
                $district = BaseModel::AREA_CODE_CN[$user_model->area_code];
                $where[] = ['district' => $district];
            }
            $order = 'id desc';
        }
        if (!$is_wechat) {
            $re = Address::findJoin('', [], ['*'], $where, true, true, $order, '', '', '', $page_info);
            if ($re['list']) {
                $address_ids = ArrayHelper::getColumn($re['list'], 'id');
                $join = [
                    [
                        'type' => 'LEFT JOIN',
                        'table' => CheckInfo::tableName() . ' c',
                        'on' => 'ac.user_channel_id = c.user_channel_id'],
                ];
                $check_info = \backend\models\AddressCheck::findJoin('ac', $join, ['count(*) num', 'c.name', 'swim_address_id', 'ac.create_time', 'ac.type', 'ac.check_num'],
                    ['swim_address_id' => $address_ids], true, true, 'ac.create_time desc', 'swim_address_id', 'swim_address_id');
                $order_num = WorkOrderIndex::findAllArray(['status' => WorkOrderIndex::UNTREATED, 'venue_id' => $address_ids], ['count(*) num', 'venue_id'], 'venue_id', '', 'venue_id');
                foreach ($re['list'] as &$v) {
                    $v['high_risk_status'] = Address::TIME_LIMIT_CN[$v['high_risk_status']];
                    $v['create_time'] = date('Y-m-d', $v['create_time']);
                    $v['untreated'] = $order_num[$v['id']]['num'] ?? 0;
                    $v['check_num'] = $check_info[$v['id']]['num'] ?? 0;
                    $v['check_type'] = $check_info[$v['id']]['type'] ?? '';
                    $v['last_checker_name'] = $check_info[$v['id']]['name'] ?? '';
                    $v['last_check_time'] = isset($check_info[$v['id']]['create_time']) ? date('Y/m/d', $check_info[$v['id']]['create_time']) : '';
                }
            }
        } else {
            $re = Address::findJoin('', [], ['name', 'longitude', 'latitude',], $where, true, true, 'high_risk_status desc,update_time desc,high_risk_deadline desc', '', '', '', $page_info);
        }
        return $re;
    }

    /**
     * 后台接口-游泳场馆信息详情
     * @param $params
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function getInfo($params)
    {
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => AddressContactPerson::tableName() . ' cp',
                'on' => 'cp.address_id = a.address_id'],
        ];
        $re = Address::findJoin('a', $join, ['a.*', 'cp.name contact_name', 'cp.phone landline_phone',], ['a.id' => $params['id'], 'a.status' => Address::NORMAL_STATUS], true, false);
        if ($re) {
            $re['high_risk_status'] = Address::TIME_LIMIT_CN[$re['high_risk_status']];
            $re['create_time'] = date('Y-m-d', $re['create_time']);
            $re['mobile'] = $re['phone'];
        }
        $re['three_personnel_num'] = AddressCoach::find()->where(['status' => AddressCoach::NORMAL_STATUS, 'address_id' => $re['address_id']])->count();
        return $re;
    }

    public static function add($params)
    {
        //ocr获取图片信息
        $data = Helper::ocr($params['licenseUrl']);
        foreach ($data['items'] as $v) {
            if (mb_substr($v['text'], 0, 7) == '许可证有效期限') {
                $time = explode('-', $v['text'])[1];
                $time = Helper::dateTimeFormat($time, 'Y-m-d', true);
            }
        }
        $address = new Address();
        $params['high_risk_deadline'] = $time ?? '';
        $params['high_risk_status'] = isset($time) && $time > date('Y-m-d') ? Address::NORMAL_STATUS : Address::ABNORMAL_STATUS;
        $address->load($params, '');
        return $address->save();
    }

    /**
     * 后台接口-游泳场馆信息详情-救生员详情
     * @param $params
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function lifeguard($params)
    {
        $where_data = [
            [
                [
                    'id' => 'al.id',
                ], '='
            ],
        ];
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => Address::tableName() . ' a',
                'on' => 'al.swim_address_id = a.address_id'],
        ];
        $with = [['addressLifeguardCertificates' => function ($query) {
            $query->select(['lifeguard_id', 'practice_certificate_code',
                'certificate_effective_date', 'recent_training_date', 'practice_certificate_url', 'cert_type', 'cert_level']);
        }]];
        $where = Helper::makeWhere($where_data, $params);
        $where[] = ['al.status' => Address::NORMAL_STATUS];
        $v = AddressLifeguard::findJoin('al', $join, ['al.*', 'a.name address_name',], $where, true, false, '', '', '', $with);
        if ($v) {
            $v['type'] = AddressLifeguard::POST_TYPE_CN[$v['type']];
//            $v['mobile'] = Helper::desensitization($v['mobile']);
//            $v['cert_type'] = AddressLifeguard::CERT_TYPE_CN[$v['cert_type']];
            $v['gender'] = AddressLifeguard::GENDER_CN[$v['gender']];
            $v['create_time'] = date('Y-m-d', $v['create_time']);
            $v['age'] = $v['id_card'] ? date('Y') - substr($v['id_card'], 6, 4) : '未知';
            foreach ($v['addressLifeguardCertificates'] as $tmp) {
                $tmp['cert_type'] = AddressLifeguard::CERT_TYPE_CN[$tmp['cert_type']];
                $v['certificate_info'][] = $tmp;
            }
            unset($v['addressLifeguardCertificates']);
        }
        return $v ?? '';
    }

    /**
     * 后台接口-游泳场馆信息详情-救生员详情列表
     * @param $params
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]|null
     */
    public static function lifeguardList($params)
    {
        $page_info = Helper::makePageInfo($params, self::PAGE_SIZE);
        $where_data = [
            [
                [
                    'address_id' => 'al.swim_address_id',
                    'district' => 'a.district',
//                    'cert_type' => 'al.cert_type',
                ], '='
            ],
        ];
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => Address::tableName() . ' a',
                'on' => 'al.swim_address_id = a.id'],
        ];
        $where = Helper::makeWhere($where_data, $params, false, true);
        $or_data = [
            [
                [
                    'search' => 'al.name',
                ], 'like'
            ],
            [
                [
                    'search' => 'a.name',
                ], 'like'
            ],
            [
                [
                    'search' => 'al.mobile',
                ], 'like'
            ],
        ];
        $or_where = Helper::makeWhere($or_data, $params, true);
        $where[] = ['al.status' => Address::NORMAL_STATUS];
//        $where[] = ['!=', 'al.swim_address_id', 0];
        $where[] = $or_where;
//        return $where;
        $with = [['addressLifeguardCertificates' => function ($query) {
            $query->select(['lifeguard_id', 'practice_certificate_code',
                'certificate_effective_date', 'recent_training_date', 'practice_certificate_url', 'cert_type', 'cert_level']);
        }]];
        $re = AddressLifeguard::findJoin('al', $join, ['al.*', 'a.name address_name', 'a.district'], $where, true, true, 'al.update_time desc', '', '', $with, $page_info);
        if ($re['list']) {
            foreach ($re['list'] as &$v) {
                $v['type'] = AddressLifeguard::POST_TYPE_CN[$v['type']];
                $v['mobile'] = Helper::desensitization($v['mobile']);
                $v['cert_type'] = isset($v['addressLifeguardCertificates'][0]['cert_type']) ? AddressLifeguard::CERT_TYPE_CN[$v['addressLifeguardCertificates'][0]['cert_type']] : '';
                $v['lifeguard_id'] = $v['addressLifeguardCertificates'][0]['lifeguard_id'] ?? 0;
                $v['practice_certificate_code'] = $v['addressLifeguardCertificates'][0]['practice_certificate_code'] ?? '';
                $v['certificate_effective_date'] = $v['addressLifeguardCertificates'][0]['certificate_effective_date'] ?? '';
                $v['recent_training_date'] = $v['addressLifeguardCertificates'][0]['recent_training_date'] ?? '';
                $v['practice_certificate_url'] = $v['addressLifeguardCertificates'][0]['practice_certificate_url'] ?? '';
                $v['cert_level'] = $v['addressLifeguardCertificates'][0]['cert_level'] ?? '';
                $v['gender'] = AddressLifeguard::GENDER_CN[$v['gender']];
                $v['create_time'] = date('Y-m-d', $v['create_time']);
                $v['age'] = $v['id_card'] ? date('Y') - (substr($v['id_card'], 6, 4) ?: 0) : '未知';
                unset($v['addressLifeguardCertificates']);
            }
        }
        return $re;
    }

    /**
     * 后台接口-检查列表
     * @param $params
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]|null
     */
    public static function checkList($params)
    {
        $page_info = Helper::makePageInfo($params, self::PAGE_SIZE);
        $where_data = [
            [
                [
                    'address_id' => 'ac.swim_address_id',
                    'district' => 'a.district',
                    'check_status' => 'ac.check_status',
                    'user_channel_id' => 'ac.user_channel_id',
                ], '='
            ],
            [
                [
                    'ac.check_date' => ['start_time', 'end_time'],
                ], 'between'
            ],
        ];
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => Address::tableName() . ' a',
                'on' => 'ac.swim_address_id = a.id'],
            [
                'type' => 'LEFT JOIN',
                'table' => CheckInfo::tableName() . ' ci',
                'on' => 'ac.user_channel_id = ci.user_channel_id'],
            [
                'type' => 'LEFT JOIN',
                'table' => WorkOrderIndex::tableName() . ' w',
                'on' => 'ac.id = w.address_check_id'],
            [
                'type' => 'LEFT JOIN',
                'table' => CheckInfo::tableName() . ' ci1',
                'on' => 'w.principal_channel_id != 0 and w.principal_channel_id = ci1.user_channel_id'],
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
                    'search' => 'ci1.name',
                ], 'like'
            ],
            [
                [
                    'search' => 'a.name',
                ], 'like'
            ],
        ];
        $or_where = Helper::makeWhere($or_data, $params, true);
        $where[] = ['ac.status' => Address::NORMAL_STATUS];
        $where[] = $or_where;
        $re = AddressCheck::findJoin('ac', $join, ['ac.*', 'a.name address_name', 'ci.name check_name', 'ci1.name principal_name',
            'a.district', 'w.info work_order_name', 'w.id work_order_id', 'w.examine_status', 'w.work_order_num'], $where, true, true, 'check_date desc', '', '', [], $page_info);
        if ($re['list']) {
            foreach ($re['list'] as &$v) {
                $v['check_status'] = AddressCheck::CHECK_STATUS_CN[$v['check_status']];
                $v['examine_status'] = $v['examine_status'] ? AddressCheck::EXAMINE_STATUS_CN[$v['examine_status']] : $v['examine_status'];
                $v['check_name'] = empty($v['principal_name']) ? $v['check_name'] : $v['check_name'] . '/' . $v['principal_name'];
                unset($v['principal_name']);
            }
        }
        return $re;
    }

    /**
     * 后台接口-工单详情
     * @param $params
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]|null
     */
    public static function workOrderInfo($params)
    {
        $where_data = [
            [
                [
                    'work_order_id' => 'w.id',
                ], '='
            ],
        ];
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => UserChannelExtra::tableName() . ' uc',
                'on' => 'uc.is_owner = w.venue_id'],
            [
                'type' => 'LEFT JOIN',
                'table' => CheckInfo::tableName() . ' ci',
                'on' => 'w.commit_id = ci.user_channel_id'],
            [
                'type' => 'LEFT JOIN',
                'table' => CheckInfo::tableName() . ' ci1',
                'on' => 'w.principal_channel_id = ci1.user_channel_id'],
        ];
        $with = [['workOrders' => function ($query) {
            $query->select(['id', 'title', 'handle_notes', 'feedback_notes', 'index_id', 'status', 'img_url', 'handle_img', 'feedback_status']);
        }]];
        $where = Helper::makeWhere($where_data, $params);
        $where[] = ['!=', 'w.status', WorkOrderIndex::DELETED];
        $re = WorkOrderIndex::findJoin('w', $join, ['w.*', 'ci.name check_name', 'ci1.name principal_name',
            'uc.realname handle_name'], $where, true, false, '', '', '', $with);
        if ($re) {
            //去除null
            foreach ($re as &$val){
                $val = $val ?? '';
            }
            //整改意见要单取
            $item_model = AddressCheckItem::findOne(['name' => '整改意见']);
            $swim_address_check_item_id = $item_model ? $item_model->id : 0;
            $detail = AddressCheckDetail::findOneArray(['status' => AddressCheckDetail::NORMAL_STATUS, 'swim_address_check_id' => $re['address_check_id'],
                'swim_address_check_item_id' => $swim_address_check_item_id]);
            if ($detail) {
                $re['rectification_opinions'] = json_decode($detail['result'], true)['input'][0];
            } else {
                $re['rectification_opinions'] = '';
            }
            $re['create_time'] = date('Y-m-d H:i:s', $re['create_time']);
            $re['work_orders'] = $re['workOrders'];
            foreach ($re['work_orders'] as &$v) {
                $v = $v ?? '';
            }
            $re['type'] = WorkOrderIndex::WORK_ORDERS_TYPE_CN[$re['type']];
            $re['commit_type'] = WorkOrderIndex::COMMIT_TYPE_CN[$re['commit_type']];
//            $re['check_name'] = $re['principal_channel_id'] ? $re['principal_name'] : $re['check_name'];
            $re['reviewer_name'] = $re['principal_channel_id'] ? $re['principal_name'] : $re['check_name'];
            $re['status'] = $re['status'] == 0 ? WorkOrderIndex::WORK_ORDER_STATUS_CN[$re['status']] : WorkOrderIndex::WORK_ORDER_STATUS_CN[$re['examine_status']];
            unset($re['principal_name']);
            unset($re['workOrders']);
        }
        return $re ?? '';
    }

    /**
     * 后台接口-游泳场馆泳池列表
     * @param $params
     * @return array|string|\yii\db\ActiveRecord[]
     */
    public static function poolList($params)
    {
        $where_data = [
            [
                [
                    'sid' => 'sid',
                ], '='
            ],
        ];
        $where = Helper::makeWhere($where_data, $params);
        $where[] = ['=', 'status', Pool::NORMAL_STATUS];
        return Pool::findAllArray($where);
    }

    /**
     * 后台接口-场馆所需企查查模糊搜索接口
     * @param $params
     * @return array
     */
    public static function qiChaCha($params)
    {
        $qichacha = \Yii::$app->params['qichacha'];
        $time = time();
        $header = ['Token:' . strtoupper(md5($qichacha['key'] . $time . $qichacha['selectKey'])), 'Timespan:' . $time];
        $url = $qichacha['url'] . '?' . http_build_query(['key' => $qichacha['key'], 'searchKey' => $params['name'], 'pageSize' => 20]);
//        return [$header,$url];
        $data = json_decode(Helper::curlGet($url, $header), true);
        $re = [];
        foreach ($data['Result'] as $v) {
            $re[] = [
                'name' => $v['Name'],
                'social_credit_code' => $v['CreditCode'],
                'legal_representative' => $v['OperName'],
            ];
        }
        return $re;
    }

    /**
     * 后台接口-新增游泳场馆第一步的高危证照ocr
     * @param $params
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\db\Exception
     */
    public static function Ocr($params)
    {
        //ocr获取图片信息
        $data = Helper::ocr($params['url']);
        $re = ['address' => '', 'open_license' => '',
            'high_risk_deadline' => '', 'principal' => '', 'issuing_authority' => '', 'nature_business' => '', 'address_person' => 0];
        if (isset($data) && isset($data['items'])) {
            foreach ($data['items'] as $v) {
                $explode = explode(':', $v['text']);
                $key1 = str_replace(' ', '', $explode[0]);
                $value = $explode[1] ?? '';
                switch ($key1) {
                    case '许可证编号':
                        $key = 'open_license';
                        break;
                    case '许可证有效期限':
                        $key = 'high_risk_deadline';
                        $value = isset(explode('-', $value)[1]) ? Helper::dateTimeFormat(explode('-', $value)[1], $format = 'Y-m-d', true) : '';
                        break;
                    case '经营场所地址':
                        $key = 'address';
                        break;
                    case '发证机构':
                        $key = 'issuing_authority';
                        break;
                    case '经营场所负责人':
                        $key = 'principal';
                        break;
                    case '许可项目(范围)':
                        $key = 'nature_business';
                        break;
                    case '社会体育指导人员和救助人员数量':
                        $key = 'address_person';
                        $value = Helper::findNum($value);
                        break;
                }
                if (isset($key)) {
                    $re[$key] = $value;
                }
                unset($key);
            }
        }
        return $re;
    }
}