<?php
/**
 * Created by wayne.
 * Date: 2019/1/5
 * Time: 10:03 PM
 */

namespace api\controllers;

use api\dbmodels\dbApi;
use api\dbmodels\dbUser;
use common\helpers\UploadOss;
use common\models\MatchImage;
use common\models\MatchImageConfig;
use Yii;
use yii\web\UploadedFile;

class WorkOrderController extends Controller
{
    /**
     * 工单主表列表
     * @return mixed
     */
    public function actionList() {
        self::getArrayParamErr(['channel_id','job_type']);
        $params = \Yii::$app->request->bodyParams;
        return self::dataOut(dbUser::workOrderList($params));
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
        self::getArrayParamErr(['channel_id', 'id','feedback_status','feedback_notes']);
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


}