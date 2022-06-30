<?php

namespace backend\controllers;

use common\models\BkUser;
use common\helpers\Utils;
use Yii;
use yii\base\Exception;
use yii\db\ActiveRecordInterface;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class Controller extends \yii\web\Controller
{
    const MSG200 = "成功";
    const MSG202 = "参数错误";

    const EMPTY_DATA = "暂无数据";

    const MV_SUCCESS = "200";
    const TOKEN_ERR = "201";
    const PARAM_ERR = "202";
    const OTHER_ERR = "203";
    const GID_ERR = "204";
    const REPEAT_ERR = "205";

    const CHECK_USER = true;
    public $viewAction = 'view';
    public $tokenParam = 'token';

    public $auth = true;
    public $except = ['view','wechat-login','export-ticket-declaration-list'];

    public $enableCsrfValidation = false;

    public function __construct($id, $module, $config = array())
    {
        header('Content-Type: text/html;charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin');
        header('Access-Control-Expose-Headers: *');
        $GLOBALS['errormsg'] = '';
        $GLOBALS['errorcode'] = '';

        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
//        $auth = $behaviors['authenticator'];
        unset($behaviors['contentNegotiator']);
        $behaviors['corsFilter'] = [
            'class' => '\yii\filters\Cors',
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['X-Requested-With', 'x_requested_with'],
            ]
        ];
//        $behaviors['authenticator'] = $auth;
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
                if ($model->gid !== Yii::$app->user->identity->gid) {
                    throw new ServerErrorHttpException('非法操作');
                }
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
            $user = \backend\models\BkUser::findOne($urid);
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

    public static function dataOut($data, $message = self::MSG200, $status = self::MV_SUCCESS)
    {
        if (empty($status)) {
            $status = self::OTHER_ERR;
        }
        $out_data['status'] = $status;
        $out_data['message'] = $message;
        $out_data['data'] = is_array($data) ? $data : [$data];
        $out_data['sys_time'] = time();
        return $out_data;
    }

    public static function errorOut($message = self::MSG202, $status = self::PARAM_ERR)
    {
//        Yii::$app->response->statusText = 123;
        Yii::$app->response->statusCode = $status;

        Yii::$app->response->data = $message;
        Yii::$app->end();
    }

    /**
     * 获取用户输入的数据
     * @return array|mixed
     */
    public static function requestData()
    {
        $data = new \stdClass();
        $request = Yii::$app->request;
        if ($request->isPost) {
            if (is_array(json_decode($request->rawBody, true))) {
                $data = json_decode($request->rawBody, true);
            } else {
                $data = $request->post();
            }
        } elseif ($request->isGet) {
            $data = $request->get();
        }

        return $data;
    }

    /**
     * @param $key
     * @param string $default
     * @return string
     */
    public static function getJsonParam($key, $default = null)
    {
        $requestData = self::requestData();
        return array_key_exists($key, $requestData) ? $requestData[$key] : $default;
    }

    public static function getArrayParamErr(array $array)
    {
        foreach ($array as $key){
            self::getJsonParamErr($key);
        }
    }


    /**
     * @param $key
     * @param string $default
     * @param string $tips
     * @return string
     */
    public static function getJsonParamErr($key, $default = "", $tips = "")
    {
        $requestData = self::requestData();
        $value = array_key_exists($key, $requestData) ? $requestData[$key] : $default;

        if (empty($value) && ($value != 0 || $value == "")) {
            if (empty($tips)) {
                self::errorOut("{$key}上传错误");
            } else {
                self::errorOut($tips);
            }
        }
        return $value;
    }

    public function checkData($data, $message = self::EMPTY_DATA, $status = self::OTHER_ERR)
    {
        try {
            if (is_array($data)) {
                if (key_exists("code", $data)) {
                    $message = $data['msg'];
                    $status = $data['code'];
                } else {
                    if (!empty($data)) {
                        return true;
                    } else {
                        $status = self::MV_SUCCESS;
                    }
                }
            } else {
                if (!empty($data)) {
                    return true;
                }
            }
        } catch (Exception $e) {
            self::errorOut($message, $status);
        }
        self::errorOut($message, $status);
        return false;
    }

//    static public function checkUser($re_name = false, $re_model = false)
//    {
//        $headers = getallheaders();
//
//        if (!isset($headers['Authorization'])) {
//            return false;
//        }
//        $headerAuthorization = $headers['Authorization'];
//        $splitAuth = explode(' ', $headerAuthorization);
//        $token = isset($splitAuth[1]) ? $splitAuth[1] : '';
//        if (!isset($headers['Urid'])) {
//            return false;
//        }
//        $urid = $headers['Urid'];
//        if (empty($token) || empty($urid)) {
//            return false;
//        }
//        $modelUser = (new BkUser())->getByUridToken($urid, $token);
//        if (empty($modelUser)) {
//            return false;
//        } else {
//            return !$re_name ? !$re_model ? $modelUser->id : $modelUser : $modelUser->username;
//        }
//    }
//
//    public function actionCheckAccessTest()
//    {
//        $module = Yii::$app->controller->module->id;
//        $action = Yii::$app->controller->action->id;
//        $controller = Yii::$app->controller->id;
//        $route = "$module/$controller/$action";
//        $urid = self::checkUser();
//        $modelUser = BkUser::findOne($urid);
//        if (isset($modelUser)) {
//            \Yii::$app->user->setIdentity($modelUser);
//            if (\Yii::$app->user->can($route)) {
//                return true;
//            } else {
//                return false;
//            }
//        }
//
//        return false;
//    }
//
//    public function beforeAction($action)
//    {
//        if (Yii::$app->request->isOptions) {
//            return parent::beforeAction($action);
//        }
//        // 校验权限
//        if (self::checkUser() === false && static::CHECK_USER) {
//            self::errorOut('请登陆', self::TOKEN_ERR);
//        }
//        return parent::beforeAction($action);
//    }

//    public function afterAction($action, $result)
//    {
//        LOG::log(json_encode($result, JSON_UNESCAPED_UNICODE));
//        return parent::afterAction($action, $result);
//    }

    public function checkWhiteList()
    {
        $white_list = ['222.70.208.221', '139.196.53.223', '127.0.0.1'];
        $user_ip = Yii::$app->request->getUserIP();
        if (!in_array($user_ip, $white_list)) {
            return self::errorOut('没有权限', OTHER_ERR);
        }
    }

    public static function checkResponse($re)
    {
        if($re[0]){
            return $re[1];
        } else {
            self::errorOut($re[1]);
        }
    }

}