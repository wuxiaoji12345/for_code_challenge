<?php


namespace backend\controllers;


use backend\models\AddressCheckItem;
use backend\service\WorkbenchService;

class WorkbenchController extends Controller
{
    /**
     * 工作台-检查列表
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]
     */
    public function actionCheckStatusList() {
        $params = \Yii::$app->request->bodyParams;
        return WorkbenchService::checkStatusList($params);
    }

    /**
     * 后台接口-子类检查项批量新增
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function actionCheckItemAdd() {
        self::getArrayParamErr(['info_list']);
        $params = \Yii::$app->request->bodyParams;
        $params['level'] = 2;
        return self::checkResponse(WorkbenchService::checkItemAdd($params));
    }

    /**
     * 后台接口-父类检查项批量新增
     * @return int
     * @throws \yii\db\Exception
     */
    public function actionParentCheckItemAdd() {
        self::getArrayParamErr(['info_list']);
        $params = \Yii::$app->request->bodyParams;
        $params['level'] = 1;
        return self::checkResponse(WorkbenchService::checkItemAdd($params));
    }

    /**
     * 后台接口-检查项编辑
     * @return mixed
     */
    public function actionCheckItemEdit() {
        self::getArrayParamErr(['id']);
        $params = \Yii::$app->request->bodyParams;
        return self::checkResponse(WorkbenchService::checkItemEdit($params));
    }

    /**
     * 后台接口-检查项列表
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]
     */
    public function actionCheckItemList() {
        $params = \Yii::$app->request->bodyParams;
        return WorkbenchService::checkItemList($params);
    }

    /**
     * 后台接口-检查项单个删除
     * @return mixed
     */
    public function actionCheckItemDelete() {
        self::getArrayParamErr(['id']);
        $params = \Yii::$app->request->bodyParams;
        return self::checkResponse(AddressCheckItem::deleteStatus($params));
    }

    /**
     * 后台接口-父类检查项列表
     * @return array|string|\yii\db\ActiveRecord[]
     */
    public function actionCheckItemParentList() {
        $params = \Yii::$app->request->bodyParams;
        return WorkbenchService::checkItemParentList($params);
    }


}