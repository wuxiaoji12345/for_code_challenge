<?php

namespace backend\controllers;

use common\models\AuthItem;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use yii\web\ServerErrorHttpException;

class AuthItemController extends BaseController
{
    public $modelClass = 'common\models\AuthItem';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'list',
        'metaEnvelope' => 'page'
    ];

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'authenticatior' => [
                    'tokenParam' => 'token',
                    'class' => QueryParamAuth::className(),
                ]
            ],
            [
                'verbFilter' => ['actions' => ['update' => ['POST']]],
            ],
            [
                'verbFilter' => ['actions' => ['delete' => ['POST']]],
            ]
        );
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        return $actions;
    }

    public function actionIndex()
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }
        $query = AuthItem::find()->asArray()
            ->where([
                'status' => 1
            ]);

        $query->orderBy(['id' => SORT_DESC]);

        if (!empty($requestParams['name'])) {
            $query->andWhere([
                'like', 'label', $requestParams['name'],
            ]);
        }

        if (!empty($requestParams['compon'])) {
            $query->andWhere([
                'like', 'component', $requestParams['compon'],
            ]);
        }

        return Yii::createObject([
            'class' => ActiveDataProvider::className(),
            'query' => $query,
            'pagination' => [
                'params' => $requestParams,
            ],
            'sort' => [
                'params' => $requestParams,
            ],
        ]);
    }

    public function actionCreate()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        $model = new AuthItem();
        $model->load($params, '');
        $model->hide = intval($model->hide);
        $model->create_time = time();
        $model->actions = trim($model->actions);
        $model->actions = str_replace(["；","，"],[";",","],$model->actions);
        if ($model->save()) {
            return $model;
        } else {
            throw new ServerErrorHttpException(implode(',', $model->getErrorSummary(true)));

        }
    }

    public function actionUpdate()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        if (!isset($params['id'])) {
            throw new ServerErrorHttpException('参数错误');
        }
        $model = AuthItem::findOne($params['id']);
        if (!isset($model) || ($model->status == 2)) {
            throw new ServerErrorHttpException('路由不存在');
        }
        $model->load($params, '');
        $model->hide = intval($model->hide);
        $model->actions = trim($model->actions);
        $model->actions = str_replace(["；","，"],[";",","],$model->actions);
        if ($model->save()) {
            return $model;
        } else {
            throw new ServerErrorHttpException(implode(',', $model->getErrorSummary(true)));
        }
    }

    public function actionDelete()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        if (!isset($params['id'])) {
            throw new ServerErrorHttpException('参数错误');
        }
        $model = AuthItem::findOne($params['id']);
        if (!isset($model) || ($model->status == 2)) {
            throw new ServerErrorHttpException('路由不存在');
        }
        $model->status = 2;
        if ($model->save()) {
            return $model;
        } else {
            throw new ServerErrorHttpException(implode(',', $model->getErrorSummary(true)));
        }
    }

    public function actionPidList()
    {
        $data = (new AuthItem())->pidList();

        return $data;
    }
}
