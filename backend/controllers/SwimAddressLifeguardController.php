<?php

namespace backend\controllers;

use backend\models\Region;
use backend\service\AddressService;
use backend\service\LifeguardService;
use common\helpers\UploadOss;
use Yii;
use backend\models\Address;
use backend\models\Search\AddressSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * SwimAddressLifeguardController implements the CRUD actions for Address model.
 */
class SwimAddressLifeguardController extends \backend\controllers\Controller
{
    /**
     * 后台接口-新增或者编辑救生员
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function actionAdd()
    {
        self::getArrayParamErr(['name', 'mobile', 'swim_address_id',  'gender', 'certificate_info']);
        $params = \Yii::$app->request->bodyParams;
        return self::checkResponse(LifeguardService::add($params, false));
    }

    /**
     * 后台接口-批量新增救生员
     * @return mixed
     * @throws \yii\db\Exception
     */
    public function actionBatchAdd()
    {
        self::getArrayParamErr(['data','swim_address_id']);
        $params = \Yii::$app->request->bodyParams;
        return self::checkResponse(LifeguardService::add($params, true));
    }

    /**
     * 后台接口-删除救生员
     * @return bool|int
     */
    public function actionDelete()
    {
        self::getArrayParamErr(['id']);
        $params = \Yii::$app->request->bodyParams;
        return LifeguardService::delete($params);
    }


}
