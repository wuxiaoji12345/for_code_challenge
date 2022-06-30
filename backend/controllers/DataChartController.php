<?php


namespace backend\controllers;


use backend\service\DataChartService;

class DataChartController extends Controller
{
    /**
     * 后台接口-数据图表-检查类型统计
     * @return array|string|\yii\db\ActiveRecord[]
     */
    public function actionCheckData() {
        $params = \Yii::$app->request->bodyParams;
        return DataChartService::checkData($params);
    }

    /**
     * 后台接口-数据图表-检查结果状态统计
     * @return array
     */
    public function actionCheckStatus() {
        $params = \Yii::$app->request->bodyParams;
        return DataChartService::checkStatus($params);
    }

    /**
     * 后台接口-数据图表-场馆证照状态统计
     * @return array
     */
    public function actionAddressLicenseStatus() {
        $params = \Yii::$app->request->bodyParams;
        return DataChartService::addressLicenseStatus($params);
    }

    /**
     * 后台接口-数据图表-人员证照状态统计
     * @return array
     */
    public function actionCertificateStatus() {
        $params = \Yii::$app->request->bodyParams;
        return DataChartService::certificateStatus($params);
    }

    /**
     * 后台接口-数据图表-检查频次
     * @return array
     */
    public function actionCheckFrequency() {
        self::getArrayParamErr(['time']);
        $params = \Yii::$app->request->bodyParams;
        return DataChartService::checkFrequency($params);
    }

    /**
     * 后台接口-数据图表-各区检查结果
     * @return array
     */
    public function actionAreaCheck() {
        return DataChartService::areaCheck();
    }

    /**
     * 后台接口-数据图表-项目异常
     * @return array
     */
    public function actionCheckItemException() {
        $params = \Yii::$app->request->bodyParams;
        return DataChartService::checkItemException($params);
    }

    /**
     * 后台接口-数据图表-场馆类型
     * @return array
     */
    public function actionAddressType() {
        return DataChartService::addressType();
    }

    /**
     * 后台接口-数据图表-各区场馆与检查员统计
     * @return array
     */
    public function actionAddressChecker() {
        return DataChartService::addressChecker();
    }

    /**
     * 后台接口-数据图表-泳池规模
     * @return int[]
     */
    public function actionPoolArea() {
        return DataChartService::poolArea();
    }

    /**
     * 后台接口-数据图表-泳池类型统计
     * @return array
     */
    public function actionPoolType() {
        return DataChartService::poolType();
    }

    /**
     * 后台接口-数据图表-高危证照过期时间统计
     * @return array
     */
    public function actionExpiredLicense() {
        return DataChartService::expiredLicense();
    }

    /**
     * 后台接口-数据图表-各类人员比例
     * @return array
     */
    public function actionPersonnelRatio() {
        $params = \Yii::$app->request->bodyParams;
        return DataChartService::personnelRatio($params);
    }

    /**
     * 后台接口-数据图表-各区人员
     * @return array
     */
    public function actionPersonnelArea() {
        return DataChartService::personnelArea();
    }

    /**
     * 后台接口-数据图表-救生员年龄分布
     * @return array|int[]
     */
    public function actionLifeguardAge() {
        return DataChartService::lifeguardAge();
    }

    /**
     * 后台接口-数据图表-检查员年龄分布
     * @return int[]
     */
    public function actionCheckerAge() {
        return DataChartService::checkerAge();
    }
}