<?php


namespace backend\controllers;


use api\dbmodels\dbUser;
use backend\service\WorkOrderService;

class WorkOrderController extends Controller
{
    /**
     * 后台接口-工单主表列表
     * @return mixed
     */
    public function actionList() {
        $params = \Yii::$app->request->bodyParams;
        return WorkOrderService::workOrderList($params);
    }

    /**
     * 子工单列表
     * @return mixed
     */
    public function actionInfo() {
        self::getArrayParamErr(['id']);
        $params = \Yii::$app->request->bodyParams;
        return self::dataOut(dbUser::workOrderInfo($params));
    }

    /**
     * 子工单历史
     * @return mixed
     */
    public function actionHistory() {
        self::getArrayParamErr(['id']);
        $params = \Yii::$app->request->bodyParams;
        return self::dataOut(dbUser::workOrderHistory($params));
    }

    /**
     * 处理工单
     * @return mixed|void
     */
    public function actionHandle() {
        self::getArrayParamErr(['id','status','handle_img','handle_notes']);
        $params = \Yii::$app->request->bodyParams;
        $re = dbUser::workOrderHandle($params);
        if($re[0]){
            return self::dataOut($re[1]);
        } else {
            return self::errorOut($re[1]);
        }
    }

    /**
     * 最终处理
     * @return mixed|void
     */
    public function actionFinalHandle() {
        self::getArrayParamErr(['id','feedback_status','feedback_notes']);
        $params = \Yii::$app->request->bodyParams;
        $re = dbUser::workOrderFinalHandle($params);
        if($re[0]){
            return self::dataOut($re[1]);
        } else {
            return self::errorOut($re[1]);
        }
    }

    /**
     * 检察员检查次数
     * @return mixed|void
     */
    public function actionCheckNum() {
        self::getArrayParamErr(['channel_id']);
        $params = \Yii::$app->request->bodyParams;
        return self::dataOut(dbUser::workOrderCheckNum($params));
    }

    /**
     * 后台接口-更改转办人
     * @return string
     */
    public function actionTransfer() {
        self::getArrayParamErr(['id','user_channel_id']);
        $params = \Yii::$app->request->bodyParams;
        WorkOrderService::transfer($params);
        return '';
    }

    /**
     * 后台接口-检查人员工单下拉框列表
     * @return array|string|\yii\db\ActiveRecord[]
     */
    public function actionCheckerInfoList() {
        return WorkOrderService::checkerInfoList();
    }

    /**
     * 后台接口-检查工单催办（目前就只有订单类型变为紧急）
     * @return array|string|\yii\db\ActiveRecord[]
     */
    public function actionUrge() {
        self::getArrayParamErr(['id']);
        $params = \Yii::$app->request->bodyParams;
        WorkOrderService::urge($params);
        return '';
    }

    /**
     * 后台接口-工单提交
     * @return string
     */
    public function actionHandleWorkOrder() {
        self::getArrayParamErr(['id','status','work_orders','channel_id']);
        $params = \Yii::$app->request->bodyParams;
        WorkOrderService::handleWorkOrder($params);
        return '';
    }
}