<?php


namespace backend\service;


use common\libs\Helper;
use common\models\Address;
use common\models\AddressCoach;
use common\models\AddressContactPerson;
use common\models\AddressLifeguard;
use common\models\AddressLifeguardCertificate;
use common\models\AddressThreePersonnel;

class LifeguardService
{
    const PAGE_SIZE = 20;

    /**
     * 编辑方法
     * @param $params
     * @param $is_array
     * @return array
     */
    public static function add($params, $is_array)
    {
        if ($is_array) {
            foreach ($params['data'] as $v) {
                $v['swim_address_id'] = $params['swim_address_id'];
                $model = new AddressLifeguard();
                $model->load($v, '');
                if (!$model->save()) return [false, array_values($model->getErrors())[0][0]];
                $v['lifeguard_id'] = $model->id;
                $cert_model = new AddressLifeguardCertificate();
                $cert_model->load($v, '');
                if (!$cert_model->save()) return [false, $cert_model->getErrors()];
            }
            return [true, ''];
        } else {
            $model = isset($params['id']) && $params['id'] ? AddressLifeguard::findOne(['id' => $params['id']]) : new AddressLifeguard();
            $model->load($params, '');
            if ($model->save()) {
                foreach ($params['certificate_info'] as $v) {
                    $cert_model = AddressLifeguardCertificate::findOne(['practice_certificate_code' => $v['practice_certificate_code'],
                        'status' => AddressLifeguardCertificate::NORMAL_STATUS]) ?: new AddressLifeguardCertificate();
                    $v['lifeguard_id'] = $model->id;
                    $cert_model->load($v, '');
                    if (!$cert_model->save()) return [false, $cert_model->getErrors()];
                }
                return [true, ''];
            } else {
                return [false, $model->getErrors()];
            }

        }
    }

    /**
     * 后台接口-删除救生员
     * @param $params
     * @return int
     */
    public static function delete($params)
    {
        AddressLifeguard::updateAll(['status' => AddressLifeguard::ABNORMAL_STATUS], ['id' => $params['id']]);
        return '';
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
        $re = Address::findJoin('a', $join, ['a.*', 'cp.name contact_name', 'cp.name landline_phone',], ['a.id' => $params['id'], 'a.status' => Address::NORMAL_STATUS], true, false);
        if ($re) {
            $re['high_risk_status'] = Address::TIME_LIMIT_CN[$re['high_risk_status']];
            $re['create_time'] = date('Y-m-d', $re['create_time']);
        }
        $re['three_personnel_num'] = AddressCoach::find()->where(['status' => AddressCoach::NORMAL_STATUS, 'address_id' => $re['address_id']])->count();
        return $re;
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
        $where = Helper::makeWhere($where_data, $params);
        $where[] = ['al.status' => Address::NORMAL_STATUS];
        $v = AddressLifeguard::findJoin('al', $join, ['al.*', 'a.name address_name'], $where, true, false);
        if ($v) {
            $v['type'] = AddressLifeguard::POST_TYPE_CN[$v['type']];
            $v['practice_certificate_url'] = $v['practice_certificate_url'] ? explode(',', $v['practice_certificate_url']) : [];
            $v['mobile'] = Helper::desensitization($v['mobile']);
            $v['cert_type'] = AddressLifeguard::CERT_TYPE_CN[$v['cert_type']];
            $v['gender'] = AddressLifeguard::GENDER_CN[$v['gender']];
            $v['create_time'] = date('Y-m-d', $v['create_time']);
            $v['age'] = $v['id_card'] ? date('Y') - substr($v['id_card'], 6, 4) : '未知';
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
                ], '='
            ],
        ];
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => Address::tableName() . ' a',
                'on' => 'al.swim_address_id = a.address_id'],
        ];
        $where = Helper::makeWhere($where_data, $params);
        $where[] = ['al.status' => Address::NORMAL_STATUS];
        $re = AddressLifeguard::findJoin('al', $join, ['al.*', 'a.name address_name'], $where, true, true, 'update_time desc', '', '', [], $page_info);
        if ($re['list']) {
            foreach ($re['list'] as &$v) {
                $v['type'] = AddressLifeguard::POST_TYPE_CN[$v['type']];
                $v['mobile'] = Helper::desensitization($v['mobile']);
                $v['cert_type'] = AddressLifeguard::CERT_TYPE_CN[$v['cert_type']];
                $v['gender'] = AddressLifeguard::GENDER_CN[$v['gender']];
                $v['create_time'] = date('Y-m-d', $v['create_time']);
                $v['age'] = $v['id_card'] ? date('Y') - substr($v['id_card'], 6, 4) : '未知';
            }
        }
        return $re;
    }
}