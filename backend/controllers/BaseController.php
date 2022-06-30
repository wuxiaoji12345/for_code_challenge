<?php

namespace backend\controllers;

use common\models\BkUser;
use common\helpers\Utils;
use yii\db\ActiveRecordInterface;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Site controller
 */
class BaseController extends ActiveController
{

    public $viewAction = 'view';
    public $tokenParam = 'token';

    public $auth = true;
    public $except = ['view'];


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $auth = $behaviors['authenticator'];
        unset($behaviors['contentNegotiator']);
        $behaviors['corsFilter'] = [
            'class' => '\yii\filters\Cors',
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['X-Requested-With', 'x_requested_with'],
            ]
        ];
        $behaviors['authenticator'] = $auth;
        if ($this->auth || Yii::$app->getRequest()->getMethod() !== 'OPTIONS') {
            $behaviors['authenticator'] = [
                'class' => CompositeAuth::className(),
                'except'=>$this->except,
                'authMethods' => [
                    HttpBearerAuth::class,
                    [
                        'class'=>QueryParamAuth::className(),
                        'tokenParam'=>'token'
                    ],
                ],
            ];

        }
        return $behaviors;

    }


    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'list',
        'metaEnvelope' => 'page'
    ];


    /**
     * @param $id
     * @return mixed|ActiveRecordInterface
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        /* @var $modelClass ActiveRecordInterface */
        $modelClass = $this->modelClass;
        $keys = $modelClass::primaryKey();
        if (count($keys) > 1) {
            $values = explode(',', $id);
            if (count($keys) === count($values)) {
                $model = $modelClass::findOne(array_combine($keys, $values));
            }
        } elseif ($id !== null) {
            $model = $modelClass::findOne($id);
        }

        if (isset($model)) {
            return $model;
        }

        throw new NotFoundHttpException("Object not found: $id");
    }


    /**
     * 返回模型
     *
     * @param $id
     * @return \yii\db\ActiveRecord
     */
    protected function findSModel($id)
    {
        /* @var $model \yii\db\ActiveRecord */
        if (empty($id) || empty(($model = $this->modelClass::findOne($id)))) {
            $model = new $this->modelClass;
            return $model->loadDefaultValues();
        }

        return $model;
    }


    /**
     * 通用gid检查
     * @param $action
     * @param null $model
     * @param array $params
     * @throws ServerErrorHttpException
     */
    public function gidcheckAccess($action, $model = null, $params = [])
    {

        switch ($action) {
            case 'update':
            case 'delete':
//                if ($model->gid !== Yii::$app->user->identity->gid) {
//                    throw new ServerErrorHttpException('非法操作');
//                }
                break;
        }
    }

    /**
     * @param $model
     * @param $id
     * @throws ServerErrorHttpException
     */
    public function publicDelete($id,$model='')
    {
        if (!$model) $model = $this->findModel($id);
        $this->gidcheckAccess($this->action->id, $model);
        $model->status = 2;
        if ($model->save() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        } elseif ($model->hasErrors()) {
            throw new ServerErrorHttpException(implode(',', $model->getErrorSummary(true)));

        }
        Yii::$app->getResponse()->setStatusCode(204);
    }

    /**
     * @param $model
     * @return mixed
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function publicUpdate($id, $model = '')
    {
        if (!$model) $model = $this->findModel($id);
        $this->gidcheckAccess($this->action->id, $model);
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        if ($model->save() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        } elseif ($model->hasErrors()) {
            throw new ServerErrorHttpException(implode(',', $model->getErrorSummary(true)));
        }
        return $model;
    }

    /**
     * 获取登录用户表数据
     */
    public function getLoginUser()
    {
        $urid = \Yii::$app->user->id;
        if($urid)
        {
            $user = BkUser::findOne($urid);
            return $user;
        }
        return [];
    }

    public function isAdmin()
    {
        $user = $this->getLoginUser();
        if($user)
        {
            $role =  $user->role;
            $preg = '/\d+/ius';
            preg_match_all($preg,$role,$out);
            if(isset($out[0]) && $out[0])
            {
                if(in_array(2,$out[0]))
                    return true;
            }
        }
        return false;
    }
    public function getGid()
    {
        $user = $this->getLoginUser();
        if($user)
        {
            return $user->gid;
        }else{
            return "";
        }
    }

    public function encodeGid($gid) {
        $key = Yii::$app->params['gidKey'];
        $ciphertext = Utils::ecbEncrypt($key, $gid);

        return $ciphertext;
    }

    public function decodeGid($cipher) {
        $key = Yii::$app->params['gidKey'];
        $gid = Utils::ecbDecrypt($key, $cipher);
        return $gid;
    }
}
