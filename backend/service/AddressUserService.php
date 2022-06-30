<?php


namespace backend\service;


use backend\models\AddressCheck;
use backend\models\AddressCheckDetail;
use backend\models\UserChannelExtra;
use common\libs\Helper;
use common\models\Address;
use common\models\AddressLifeguardCertificate;
use common\models\AddressThreePersonnel;
use common\models\BaseModel;
use common\models\CheckInfo;
use common\models\WorkOrder;
use common\models\WorkOrderHistory;
use common\models\WorkOrderIndex;
use yii\helpers\ArrayHelper;

class AddressUserService
{
    const PAGE_SIZE = 20;

    /**
     * 后台接口-检察员列表
     * @param $params
     * @param bool $is_bk_page
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]|null
     */
    public static function checkerList($params, $is_bk_page = true)
    {
        $page_info = Helper::makePageInfo($params, self::PAGE_SIZE);
        $where_data = [
            [
                [
                    'area_code' => 'area_code',
                ], '='
            ],
        ];
        $where = Helper::makeWhere($where_data, $params);
        $where = BaseModel::jurisdiction($where, true);
        $or_data = [
            [
                [
                    'search' => 'name',
                ], 'like'
            ],
            [
                [
                    'search' => 'mobile',
                ], 'like'
            ],
        ];
        $or_where = Helper::makeWhere($or_data, $params, true);
        $where[] = ['status' => CheckInfo::NORMAL_STATUS];
        if (!$is_bk_page) {
            $where[] = ['=', 'user_channel_id', 0];
        }
        $where[] = $or_where;
        $re = CheckInfo::findJoin('', [], ['*',], $where, true, true, 'create_time desc', '', '', [], $page_info);
        if ($re['list']) {
            $channel_ids = ArrayHelper::getColumn($re['list'], 'user_channel_id');
            $join = [
                [
                    'type' => 'LEFT JOIN',
                    'table' => Address::tableName() . ' a',
                    'on' => 'ac.swim_address_id = a.id'],
            ];
            $check_info = AddressCheck::findJoin('ac', $join, ['count(*) num', 'a.name', 'user_channel_id', 'ac.create_time'],
                ['user_channel_id' => $channel_ids], true, true, 'ac.create_time desc', 'user_channel_id', 'user_channel_id');
            foreach ($re['list'] as &$v) {
                $v['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $v['area_code'] = CheckInfo::AREA_CODE_CN[$v['area_code']];
                $v['real_mobile'] = $v['mobile'];
                $v['mobile'] = Helper::desensitization($v['mobile']);
                $v['real_id_card'] = $v['id_card'];
                $v['id_card'] = Helper::desensitization($v['id_card'], 6, -4, '********');
                $v['certificates_status'] = CheckInfo::CERTIFICATES_STATUS_CN[$v['certificates_status']];
                $v['gender'] = CheckInfo::GENDER_CN[$v['gender']];
                $v['check_num'] = $check_info[$v['user_channel_id']]['num'] ?? 0;
                $last = AddressCheck::findJoin('ac', $join, ['a.name', 'user_channel_id', 'ac.create_time'],
                    ['user_channel_id' => $v['user_channel_id']], true, false, 'ac.create_time desc');
                $v['last_address_name'] = $last['name'] ?? '';
                $v['last_check_time'] = isset($last['create_time']) ? date('Y/m/d', $last['create_time']) : '';
            }
        }
        return $re;
    }

    /**
     * 后台接口-新增或编辑检查员
     * @param $params
     * @return array
     */
    public static function checkerAdd($params)
    {
        $model = isset($params['id']) && $params['id'] ? CheckInfo::findOne(['id' => $params['id']]) : new CheckInfo();
        $model->load($params, '');
        if ($model->save()) {
            return [true, ''];
        } else {
            return [false, $model->getErrors()];
        }
    }

    /**
     * 后台接口-批量新增检查员
     * @param $params
     * @throws \yii\db\Exception
     */
    public static function checkerBatchAdd($params)
    {
        //要验证身份和手机号，改为模型存入
        foreach ($params['checker_info'] as $v) {
            $v['area_code'] = $params['area_code'];
            $v['status'] = CheckInfo::NORMAL_STATUS;
            $v['create_time'] = time();
            $model = new CheckInfo();
            $model->load($v, '');
            if (!$model->save()) return [false, array_values($model->getErrors())[0][0]];
        }
        return [true, ''];
//        CheckInfo::insertOrUpdate('', $params['checker_info'], true);
    }

    /**
     * 后台接口-单个或者批量删除检察员
     * @param $params
     * @return int
     */
    public static function checkerDelete($params)
    {
        return CheckInfo::updateAll(['status' => CheckInfo::ABNORMAL_STATUS], ['id' => $params]);
    }

    /**
     * 后台接口-三类人员列表
     * @param $params
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]|null
     */
    public static function threePersonnelList($params)
    {
        $page_info = Helper::makePageInfo($params, self::PAGE_SIZE);
        $where_data = [
            [
                [
                    'district' => 'a.district',
                    'type' => 'at.type',
                    'address_id' => 'at.address_id',
                ], '='
            ],
        ];
        $where = Helper::makeWhere($where_data, $params, false, true);
        $or_data = [
            [
                [
                    'search' => 'at.name',
                ], 'like'
            ],
            [
                [
                    'search' => 'at.phone',
                ], 'like'
            ],
            [
                [
                    'search' => 'a.name',
                ], 'like'
            ],
        ];
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => Address::tableName() . ' a',
                'on' => 'at.address_id = a.id'],

        ];
        $or_where = Helper::makeWhere($or_data, $params, true);
        $where[] = ['at.status' => CheckInfo::NORMAL_STATUS];
        $where[] = $or_where;
        $re = AddressThreePersonnel::findJoin('at', $join, ['at.*', 'a.name address_name', 'a.district'], $where, true, true, 'create_time desc', '', '', [], $page_info);
        if ($re['list']) {
            foreach ($re['list'] as &$v) {
                $v['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $v['type'] = AddressThreePersonnel::THREE_PERSONNEL_TYPE_CN[$v['type']];
                $v['real_phone'] = $v['phone'];
                $v['phone'] = Helper::desensitization($v['phone']);
                $v['real_id_card'] = $v['id_card'];
                $v['id_card'] = Helper::desensitization($v['id_card'], 6, -4, '********');
//                $v['certificates_status'] = CheckInfo::CERTIFICATES_STATUS_CN[$v['certificates_status']];
                $v['gender'] = $v['gender'] == AddressThreePersonnel::MAN ? AddressThreePersonnel::GENDER_CN[$v['gender']] : AddressThreePersonnel::GENDER_CN[2];
            }
        }
        return $re;
    }

    /**
     * 后台接口-新增或者编辑三类人员
     * @param $params
     * @return array
     * @throws \yii\db\Exception
     */
    public static function threePersonnelAdd($params)
    {
        $model = isset($params['id']) && $params['id'] ? AddressThreePersonnel::findOne(['id' => $params['id']]) : new AddressThreePersonnel();
        $model->load($params, '');
        $transaction = \Yii::$app->db->beginTransaction();
        if ($model->save()) {
            $has_id = [];
            $not_has_id = [];
            foreach ($params['certificate_info'] as $v) {
                if(empty($v['cert_type'])){
                    $transaction->rollBack();
                    return [false, '三类至少填一本证书！'];
                }
                $v['three_personnel_id'] = $model->id;
                $v['create_time'] = time();
                if (isset($v['id'])) {
                    if (!$v['id']) {
                        unset($v['id']);
                    } else {
                        $has_id[] = $v;
                        continue;
                    }
                }
                $not_has_id[] = $v;
            }
            if ($has_id) AddressLifeguardCertificate::insertOrUpdate('', $has_id, true);
            if ($not_has_id) AddressLifeguardCertificate::insertOrUpdate('', $not_has_id, true);
            $transaction->commit();
            return [true, ''];
        } else {
            $transaction->rollBack();
            return [false, array_values($model->getErrors())[0][0]];
        }
    }

    /**
     * 后台接口-批量新增三类人员
     * @param $params
     * @return array
     */
    public static function threePersonnelBatchAdd($params)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        foreach ($params['three_personnel_info'] as $v) {
            $v['address_id'] = $params['address_id'];
            $v['address_name'] = $params['address_name'];
            $model = new AddressThreePersonnel();
            $model->load($v, '');
            if ($model->save()) {
                if(empty($v['cert_type'])){
                    $transaction->rollBack();
                    return [false, '三类至少填一本证书！'];
                }
                $v['three_personnel_id'] = $model->id;
                $cert_model = new AddressLifeguardCertificate();
                $cert_model->load($v, '');
                if (!$cert_model->save()) {
                    $transaction->rollBack();
                    return [false, array_values($cert_model->getErrors())[0][0]];
                }
            } else {
                $transaction->rollBack();
                return [false, array_values($model->getErrors())[0][0]];
            }
        }
        $transaction->commit();
        return [true, ''];
    }

    /**
     * 后台接口-三类人员详情
     * @param $params
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]|null
     */
    public static function threePersonnelInfo($params)
    {
        $page_info = Helper::makePageInfo($params, self::PAGE_SIZE);
        $where_data = [
            [
                [
                    'id' => 'at.id',
                ], '='
            ],
        ];
        $where = Helper::makeWhere($where_data, $params);

        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => Address::tableName() . ' a',
                'on' => 'at.address_id = a.id'],

        ];
        $with = [['addressLifeguardCertificates' => function ($query) {
            $query->select(['three_personnel_id', 'practice_certificate_code',
                'certificate_effective_date', 'recent_training_date', 'practice_certificate_url', 'cert_type', 'cert_level', 'id']);
        }]];
        $re = AddressThreePersonnel::findJoin('at', $join, ['at.*', 'a.name address_name', 'a.district'], $where, true, true, 'create_time desc', '', '', $with, $page_info);
        if ($re['list']) {
            foreach ($re['list'] as &$v) {
                $v['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $v['type'] = AddressThreePersonnel::THREE_PERSONNEL_TYPE_CN[$v['type']];
//                $v['phone'] = Helper::desensitization($v['phone']);
                $v['id_card'] = Helper::desensitization($v['id_card'], 6, -4, '********');
//                $v['certificates_status'] = CheckInfo::CERTIFICATES_STATUS_CN[$v['certificates_status']];
                $v['gender'] = $v['gender'] == AddressThreePersonnel::MAN ? AddressThreePersonnel::GENDER_CN[$v['gender']] : AddressThreePersonnel::GENDER_CN[2];
                $v['certificates_info'] = $v['addressLifeguardCertificates'];
                unset($v['addressLifeguardCertificates']);
            }
        }
        return $re;
    }

    /**
     * 后台接口-单个或者批量删除三类人员
     * @param $params
     * @return int
     */
    public static function threePersonnelDelete($params)
    {
        return AddressThreePersonnel::updateAll(['status' => AddressThreePersonnel::ABNORMAL_STATUS], ['id' => $params]);
    }
}