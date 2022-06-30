<?php


namespace api\controllers;


use common\libs\Helper;
use common\models\Address;
use common\models\AddressCoach;
use common\models\AddressContactPerson;
use common\models\AddressFitnessCardSignin;
use common\models\AddressLifeguard;
use common\models\AddressSwimmersInfo;
use common\models\AddressThreePersonnel;
use common\models\AddressTrainingExperience;
use common\models\AddressWaterQuality;
use common\models\BaseModel;
use common\models\CheckInfo;
use GuzzleHttp\Client;
use yii\db\Exception;

class TianjianController extends Controller
{
    /**
     * 游泳馆信息
     * @return mixed
     */
    public function actionPool()
    {
        $params = \Yii::$app->request->bodyParams;
//        $params = \Yii::$app->request->getRawBody();
//        $params = json_decode($params, true);
        //ocr获取图片信息
        if (!empty($params['licenseUrl'])) {
            $data = Helper::ocr($params['licenseUrl']);
            if (isset($data['items'])) {
                foreach ($data['items'] as $v) {
                    if (mb_substr($v['text'], 0, 7) == '许可证有效期限') {
                        $time = explode('-', $v['text'])[1];
                        $time = Helper::dateTimeFormat($time, 'Y-m-d', true);
                    }
                }
            }
        }
        $address = Address::findOne(['address_id' => $params['id']]) ?? new Address();
        $params['high_risk_deadline'] = $time ?? '';
        $params['high_risk_status'] = isset($time) && $time > date('Y-m-d') ? 1 : 2;
        $params['address_id'] = $params['id'];
        $params['type'] = $params['swimType'] ?? '';
        $params['name'] = $params['spName'];
        $params['avatar'] = $params['spAvatar'];
        $params['license_url'] = $params['licenseUrl'];
        $params['province'] = $params['areaProvince'];
        $params['city'] = $params['areaCity'];
        $params['district'] = $params['areaRegion'];
        $params['address'] = $params['areaStreet'];
        $params['travel_information'] = $params['travelInformation'];
        $params['trade_situation'] = $params['tradeSituation'] ?: 01;
        $params['swim_service_type'] = $params['swimServiceType'];
        $params['water_acreage'] = $params['waterAcreage'];
        $params['open_license'] = $params['openLicense'];
        $params['open_object'] = $params['openObject'];
        $params['last_access'] = $params['lastAccess'];
        $params['latitude'] = $params['latitude'] ?: 0;
        $params['longitude'] = $params['longitude'] ?: 0;
        $params['disabled'] = $params['disabled'] ?: 0;
        $params['collapse_flag'] = $params['collapseFlag'] ?: 0;
        $params['approval_status'] = $params['approvalStatus'] ?: '0';
        $params['status'] = isset($params['collapseFlag']) ? ($params['collapseFlag'] == 1 ? 2 : 1) : 1;
        $params['create_time'] = isset($params['createdTime']) ? substr($params['createdTime'], 0, 10) : time();

        $address->load($params, '');
        if ($address->save()) {
            return self::dataOut('保存成功');
        } else {
            return self::errorOut($address->getErrors());
        }
    }

    /**
     * 泳馆救生员/教练信息
     * @return mixed|void
     */
    public function actionCoach()
    {
        $params = \Yii::$app->request->bodyParams;
        $model = AddressLifeguard::findOne(['lifeguard_id' => $params['id']]) ?? new AddressLifeguard();
        $params['lifeguard_id'] = $params['id'];
        //通过天健的id反查场馆的id
        $address = Address::findOneArray(['address_id' => $params['swimPoolId']]);
        $params['swim_address_id'] = $address['id'] ?? 0;
        $params['tianjian_pool_id'] = $params['swimPoolId'];
        $params['type'] = $params['personType'] ?? 1;
        $params['mobile'] = $params['phone'];
        $params['gender'] = ($params['gender'] == 0) ? AddressLifeguard::WOMEN : $params['gender'];
        $params['cert_level'] = $params['level'];
        $params['last_access'] = $params['lastAccess'];
        $params['create_time'] = time();
        $model->load($params, '');
        if ($model->save()) {
            return self::dataOut('保存成功');
        } else {
            return self::errorOut($model->getErrors());
        }
    }

    /**
     * 泳馆联系人信息
     * @return mixed|void
     */
    public function actionContactPerson()
    {
        $params = \Yii::$app->request->bodyParams;
        $model = AddressContactPerson::findOne(['contact_id' => $params['id']]) ?? new AddressContactPerson();
        $params['contact_id'] = $params['id'];
        $params['address_id'] = $params['swimPoolId'];
        $params['landline_phone'] = $params['landlinePhone'];
        $params['is_default'] = $params['isDefault'];
        $params['last_access'] = $params['lastAccess'];
        $params['create_time'] = time();
        $model->load($params, '');
        if ($model->save()) {
            return self::dataOut('保存成功');
        } else {
            return self::errorOut($model->getErrors());
        }
    }

    /**
     * 从业人员信息
     * @return mixed|void
     */
    public function actionThreePersonnel()
    {
        $params = \Yii::$app->request->bodyParams;
        foreach ($params as &$v) {
            $v = $v ?? '';
        }
        $model = AddressThreePersonnel::findOne(['personnel_id' => $params['id']]) ?? new AddressThreePersonnel();
        $params['personnel_id'] = $params['id'];
        $params['id_card'] = $params['idCard'] ?? '';
        $params['card_no'] = $params['cardNo'] ?? '';
        $params['date_of_issuance'] = $params['dateOfIssuance'] ?? '';
        $params['date_of_issuance_end'] = $params['dateOfIssuanceEnd'] ?? '';
        $params['id_card_image'] = $params['idCardImage'] ?? '';
        $params['account_address'] = $params['accountAddress'] ?? '';
        $params['type'] = $params['personType'] ?? 1;
        $params['card_status'] = $params['cardStatus'] ?? '';
        $params['work_year'] = $params['workYear'] ?? 0;
        $params['service_area'] = $params['serviceArea'] ?? '';
        $params['last_access'] = $params['lastAccess'] ?? 0;
        $params['create_time'] = time();
        $model->load($params, '');
        if ($model->save()) {
//            if ($params['personType'] == 4) {
//                $area_code = array_flip(CheckInfo::AREA_CODE_CN);
//                $check_model = new CheckInfo();
//                $check_model->load($params, '');
//                $check_model->area_code = $area_code[$params['service_area']] ?? 0;
//                $check_model->gender = $params['gender'] == '男' ? CheckInfo::MAN : CheckInfo::WOMEN;
//                $check_model->mobile = $params['phone']??'';
//                $check_model->name = $params['name']??'';
//                if ($check_model->save()) {
//                    return self::dataOut('保存成功');
//                } else {
//                    return self::errorOut($check_model->getErrors());
//                }
//            }
            return self::dataOut('保存成功');
        } else {
            return self::errorOut($model->getErrors());
        }
    }

    /**
     * 从业人员培训记录
     * @return mixed|void
     */
    public function actionTrainingExperience()
    {
        $params = \Yii::$app->request->bodyParams;
        foreach ($params as &$v) {
            $v = $v ?? '';
        }
        $model = AddressTrainingExperience::findOne(['experience_id' => $params['id']]) ?? new AddressTrainingExperience();
        $params['experience_id'] = $params['id'];
        $params['three_personnel_id'] = $params['threePersonnelId'] ?? 0;
        $params['card_no'] = $params['cardNo'] ?? '';
        $params['id_card'] = $params['idCard'] ?? '';
        $params['learning_date'] = $params['learningDate'] ?? '';
        $params['learning_content'] = $params['learningContent'] ?? '';
        $params['address_str'] = $params['swimPoolStr'] ?? '';
        $params['type'] = isset($params['personType']) && $params['personType'] ? $params['personType'] : 1;
        $params['last_access'] = $params['lastAccess'] ?? 0;
        $params['create_time'] = time();
        $model->load($params, '');
        if ($model->save()) {
            return self::dataOut('保存成功');
        } else {
            return self::errorOut($model->getErrors());
        }
    }

    /**
     * 泳馆水质信息
     * @return mixed|void
     */
    public function actionWaterQuality()
    {
        $params = \Yii::$app->request->bodyParams;
        $model = new AddressWaterQuality();
        $params['address_id'] = $params['swimPoolId'];
        $params['address_name'] = $params['swimPoolName'] ?? '';
        $params['device_no'] = $params['deviceNo'] ?? '';
        $params['sampling_point'] = $params['samplingPoint'] ?? '';
        $params['create_time'] = time();
        $params['update_time'] = $params['updateTime'] ?? time();
        $model->load($params, '');
        if ($model->save()) {
            return self::dataOut('保存成功');
        } else {
            return self::errorOut($model->getErrors());
        }
    }

    /**
     * 泳客信息统计
     * @return mixed|void
     */
    public function actionSwimmersInfo()
    {
        self::getArrayParamErr(['stat_date']);
        $params = \Yii::$app->request->bodyParams;
        $model = new AddressSwimmersInfo();
        $params['create_time'] = time();
        $model->load($params, '');
        if ($model->save()) {
            return self::dataOut('保存成功');
        } else {
            return self::errorOut($model->getErrors());
        }
    }

    /**
     * 泳客健康承诺入场记录
     * @return mixed|void
     */
    public function actionFitnessCardSignin()
    {
        $params = \Yii::$app->request->bodyParams;
        $area = array_values(BaseModel::AREA_CODE_CN);
        if(in_array($params['areaRegion'],$area)){
            $model = new AddressFitnessCardSignin();
            $params['signin_id'] = $params['id'];
            $params['swim_pool_id'] = $params['swimPoolId'];
            $params['address_name'] = $params['spName'];
            $params['district'] = $params['areaRegion'];
            $params['last_access'] = $params['lastAccess'];
            $params['create_time'] = substr($params['signinTime'], 0, 10);
            $params['date'] = date('Y-m-d',$params['create_time']);
            $model->load($params, '');
            if ($model->save()) {
                return self::dataOut('保存成功');
            } else {
                return self::errorOut($model->getErrors());
            }
        } else {
            return self::errorOut('非上海地区数据');
        }
    }


}