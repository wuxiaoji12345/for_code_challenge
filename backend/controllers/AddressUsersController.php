<?php


namespace backend\controllers;


use api\dbmodels\dbUser;
use backend\service\AddressService;
use backend\service\AddressUserService;
use backend\service\WorkOrderService;

class AddressUsersController extends Controller
{
    /**
     * 后台接口-检察员列表
     * @return mixed
     */
    public function actionCheckerList() {
        $params = \Yii::$app->request->bodyParams;
        return AddressUserService::checkerList($params);
    }

    /**
     * 后台接口-新增或编辑检查员
     * @return mixed
     */
    public function actionCheckerAdd() {
        self::getArrayParamErr(['name','mobile','area_code','age','gender','effective_date',]);
        $params = \Yii::$app->request->bodyParams;
        return self::checkResponse(AddressUserService::checkerAdd($params));
    }

    /**
     * 后台接口-批量新增检查员
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionCheckerBatchAdd() {
        self::getArrayParamErr(['area_code','checker_info']);
        $params = \Yii::$app->request->bodyParams;
        return self::checkResponse(AddressUserService::checkerBatchAdd($params));
    }

    /**
     * 后台接口-单个或者批量删除检察员
     * @return mixed
     */
    public function actionCheckerDelete() {
        self::getArrayParamErr(['id']);
        $params = \Yii::$app->request->bodyParams;
        return AddressUserService::checkerDelete($params);
    }

    /**
     * 后台接口-检察员检查列表
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]|null
     */
    public function actionCheckList()
    {
        self::getArrayParamErr(['user_channel_id']);
        $params = \Yii::$app->request->bodyParams;
        return AddressService::checkList($params);
    }

    /**
     * 后台接口-三类人员列表
     * @return mixed|void
     */
    public function actionThreePersonnelList() {
        $params = \Yii::$app->request->bodyParams;
        return AddressUserService::threePersonnelList($params);
    }

    /**
     * 后台接口-新增或者编辑三类人员
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function actionThreePersonnelAdd() {
        self::getArrayParamErr(['address_id','address_name','name','gender','phone','type','age','certificate_info']);
        $params = \Yii::$app->request->bodyParams;
        return self::checkResponse(AddressUserService::threePersonnelAdd($params));
    }

    /**
     * 后台接口-三类人员详情
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]|null
     */
    public function actionThreePersonnelInfo() {
        self::getArrayParamErr(['id']);
        $params = \Yii::$app->request->bodyParams;
        return AddressUserService::threePersonnelInfo($params);
    }

    /**
     * 后台接口-批量新增三类人员
     * @return array|string|\yii\db\ActiveRecord[]
     */
    public function actionThreePersonnelBatchAdd() {
        self::getArrayParamErr(['address_id','address_name','three_personnel_info']);
        $params = \Yii::$app->request->bodyParams;
        return self::checkResponse(AddressUserService::threePersonnelBatchAdd($params));
    }

    /**
     * 后台接口-单个或者批量删除三类人员
     * @return int
     */
    public function actionThreePersonnelDelete() {
        self::getArrayParamErr(['id']);
        $params = \Yii::$app->request->bodyParams;
        return AddressUserService::threePersonnelDelete($params);
    }
}