<?php


namespace backend\controllers;


use backend\service\AddressService;
use backend\service\DataChartService;
use backend\service\LargeScreenService;
use backend\service\WorkOrderService;

class LargeScreenController extends Controller
{
    public $except = ['*'];
    /**
     * 大屏-数据概览
     * @return array
     */
    public function actionPersonnelRatio() {
        $params = \Yii::$app->request->bodyParams;
        return LargeScreenService::personnelRatio($params);
    }

    /**
     * 大屏-检查概览
     * @return array
     */
    public function actionCheckData() {
        $params = \Yii::$app->request->bodyParams;
        return LargeScreenService::checkData($params);
    }

    /**
     * 大屏-检查频次（周）
     * @return array
     */
    public function actionCheckFrequency() {
        $params = \Yii::$app->request->bodyParams;
        $params['time'] = 'week';
        return DataChartService::checkFrequency($params);
    }

    /**
     * 大屏-游泳场馆信息列表
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]|null
     */
    public function actionList()
    {
        $params = \Yii::$app->request->bodyParams;
        return AddressService::getList($params,true);
    }

    /**
     * 大屏-客流数
     * @return array
     */
    public function actionPassengerFlow() {
        $params = \Yii::$app->request->bodyParams;
        return LargeScreenService::passengerFlow($params);
    }

    /**
     * 大屏-客流总数与场馆总数
     * @return array
     */
    public function actionPassengerAddressStatic() {
        $params = \Yii::$app->request->bodyParams;
        return LargeScreenService::passengerAddressStatic($params);
    }

    /**
     * 大屏-客流按区统计（客流明细）
     * @return array
     */
    public function actionPassengerFlowArea() {
        $params = \Yii::$app->request->bodyParams;
        return LargeScreenService::passengerFlowArea($params);
    }

    /**
     * 大屏-开放检查屏-检查结果状态
     * @return array
     */
    public function actionCheckStatus() {
        $params = \Yii::$app->request->bodyParams;
        return DataChartService::checkStatus($params);
    }

    /**
     * 大屏-开放检查屏-场馆证照状态
     * @return array
     */
    public function actionAddressLicenseStatus() {
        $params = \Yii::$app->request->bodyParams;
        return DataChartService::addressLicenseStatus($params);
    }

    /**
     * 大屏-开放检查屏-人员证照状态
     * @return array
     */
    public function actionCertificateStatus() {
        $params = \Yii::$app->request->bodyParams;
        return DataChartService::certificateStatus($params);
    }

    /**
     * 大屏-开放检查大屏-人员证照有效率列表
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]|null
     */
    public function actionCertificateQualifiedRate() {
        $params = \Yii::$app->request->bodyParams;
        return LargeScreenService::certificateQualifiedRate($params);
    }

    /**
     * 大屏-开放检查大屏-项目异常
     * @return int[]
     */
    public function actionWorkOrderStatus() {
        $params = \Yii::$app->request->bodyParams;
        return LargeScreenService::workOrderStatus($params);
    }

    /**
     * 大屏-开放检查屏-工单主表列表
     * @return mixed
     */
    public function actionOrderList() {
        $params = \Yii::$app->request->bodyParams;
        return WorkOrderService::workOrderList($params);
    }

    /**
     * 大屏-开放检查大屏-项目异常
     * @return array
     */
    public function actionCheckItemException() {
        $params = \Yii::$app->request->bodyParams;
        return DataChartService::checkItemException($params);
    }

    /**
     * 大屏-开放检查大屏-场馆合格率
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]|null
     */
    public function actionAddressQualifiedRate() {
        $params = \Yii::$app->request->bodyParams;
        return LargeScreenService::addressQualifiedRate($params);
    }

    /**
     * 大屏-开放检查大屏-场所证照有效率列表
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]|null
     */
    public function actionAddressLicenseQualifiedRate() {
        $params = \Yii::$app->request->bodyParams;
        return LargeScreenService::addressLicenseQualifiedRate($params);
    }

    /**
     * 大屏-检查人员检查次数与待审核工单
     * @return array
     */
    public function actionCheckerCheckInfo() {
        $params = \Yii::$app->request->bodyParams;
        return LargeScreenService::checkerCheckInfo($params);
    }

    /**
     * 大屏-游泳日常管理大屏-场馆开放趋势
     * @return array
     */
    public function actionAddressInfo() {
        $params = \Yii::$app->request->bodyParams;
        return DataChartService::addressInfo($params);
    }

    /**
     * 大屏-游泳日常管理大屏-累计客流明细
     * @return int[]
     */
    public function actionCumulativePassenger() {
        $params = \Yii::$app->request->bodyParams;
        return DataChartService::cumulativePassenger($params);
    }
}