<?php

namespace backend\controllers;

use Yii;
use common\models\AuthRole;
use yii\data\ActiveDataProvider;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use yii\web\ServerErrorHttpException;

class AuthRoleController extends BaseController
{
    public $modelClass = 'common\models\AuthRole';
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
        $query = AuthRole::find()->asArray()
            ->where([
                'status' => 1
            ]);

        $query->orderBy(['id' => SORT_DESC]);

        if (isset($requestParams['name']) && !empty($requestParams['name'])) {
            $query->andWhere([
                'like', 'name', $requestParams['name'],
            ]);
        }
        if(!$this->isAdmin())
        {
            $userModel = $this->getLoginUser();
            $query->andWhere([
                '=', 'gid',$userModel->gid,
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
        $model = new AuthRole();
        $model->load($params, '');
        $model->create_time = time();
        $model->gid = $this->getGid();
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
        $model = AuthRole::findOne($params['id']);
        if (!isset($model) || ($model->status == 2)) {
            throw new ServerErrorHttpException('角色不存在');
        }
        $model->load($params, '');
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
        $model = AuthRole::findOne($params['id']);
        if (!isset($model) || ($model->status == 2)) {
            throw new ServerErrorHttpException('角色不存在');
        }
        $model->status = 2;
        if ($model->save()) {
            return $model;
        } else {
            throw new ServerErrorHttpException(implode(',', $model->getErrorSummary(true)));
        }
    }
}
