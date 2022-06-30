<?php
/**
 * Created by wayne.
 * Date: 2019/1/5
 * Time: 10:03 PM
 */

namespace api\controllers;

use api\dbmodels\dbUser;
use backend\models\Address;
use backend\models\User;
use common\helpers\Utils;
use common\libs\Helper;
use common\models\AuthAssignment;
use common\models\AuthRole;
use common\models\BkUser as McBkUser;
use common\models\CheckInfo;
use common\models\LoginForm;
use common\models\MatchImage;
use common\models\MatchImageConfig;
use common\models\UserChannelExtra;
use Yii;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

class UserController extends Controller
{

    public function actionSafelogin()
    {
        $username = self::getJsonParamErr('username');
        $password = self::getJsonParamErr('password');

        $model = new LoginForm();
        $requestData['username'] = $username;
        $requestData['password'] = $password;

        $model->setAttributes($requestData);
        if($model->validate()) {
            $data['userid'] = $model->getUserID();
            $data['token'] = 'movetoken';
            return self::dataOut($data);
        }

        return self::errorOut('用户名或密码错误');

    }


    /**
     * 小程序的登录
     * @return mixed
     * @throws ServerErrorHttpException
     */
    public function actionNewLoginwx() {
        $model = new LoginForm();
        $requestData = Yii::$app->request->bodyParams;
        $model->setAttributes($requestData);
        if ($model->login()) {
            $model = McBkUser::findByUsername($requestData['username']);
            $data = dbUser::wxLoginNew($model);
            if(empty($data)) {
                return self::dataOut(array(), '登陆失败', self::OTHER_ERR);
            }
            //记录操作日志
            Helper::RecordOperationLog(['operation_id'=>$model->id,'operation_name'=>$model->username,'operation_model'=>'登录模块','operation_event'=>'用户后台登录']);
            return self::dataOut($data);
        } else {
            throw new ServerErrorHttpException("用户名或密码错误");
        }
    }

    public function actionLoginwx() {
        $wxcode    = self::getJsonParamErr('wxcode');
        $userinfo = self::getJsonParamErr('userinfo');
        $signature = self::getJsonParam('signature');
        $encryptedData = self::getJsonParam('encryptedData');
        $iv = self::getJsonParam('iv');
        $app = self::getJsonParam('app', 1);
        $gid = self::getJsonParamErr('gid');
        $fromurid = self::getJsonParam('fromurid', 0);
        $appurid = self::getJsonParam('appurid');
        $extappid = self::getJsonParam('extappid');
        $dist = self::getJsonParam('dist', 0);

        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), '错误企业', self::GID_ERR);
        }

        $data = dbUser::wxLogin($wxcode, $encryptedData, $iv, $userinfo, $gid, $fromurid, $app, $dist);
        if(empty($data)) {
            return self::dataOut(array(), '登陆失败', self::OTHER_ERR);
        }
        return self::dataOut($data);
    }

    public function actionFormsave() {
        $urid = self::getJsonParamErr('urid');
        $token =  self::getJsonParamErr('token');
        $check = self::checkUser($urid, $token);
        if(!$check) {
            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
        }
        $app = self::getJsonParamErr('app', 1);
        $formid = self::getJsonParamErr('formid');

        $data = dbUser::saveformID($urid, $app, $formid);
        if(empty($data)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }else {
            return self::dataOut(array(), 'OK');
        }
    }

    public function actionMymembers() {
        $urid = self::getJsonParamErr('urid');
        $token =  self::getJsonParamErr('token');
        $check = self::checkUser($urid, $token);
        if(!$check) {
            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
        }
        $app = self::getJsonParamErr('app', 1);
        $page =  self::getJsonParam('page', 1);

        $data = dbUser::mymemberlist($urid, $page, $app);
        if(empty($data)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }else {
            return self::dataOut($data, 'OK');
        }
    }

    public function actionAddmember() {
        $urid = self::getJsonParamErr('urid');
        $token =  self::getJsonParamErr('token');
        $check = self::checkUser($urid, $token);
        if(!$check) {
            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
        }
        $app = self::getJsonParamErr('app', 1);
        $idnumber =  self::getJsonParamErr('idnumber');
        $idtype = self::getJsonParamErr('idtype');
        $name = self::getJsonParamErr('name');

        $data = dbUser::addMember($urid, $idnumber, $idtype, $name);
        if($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }else {
            return self::dataOut(array(), '添加成功');
        }
    }

    public function actionDelmember() {
        $urid = self::getJsonParamErr('urid');
        $token =  self::getJsonParamErr('token');
        $check = self::checkUser($urid, $token);
        if(!$check) {
            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
        }
        $app = self::getJsonParamErr('app', 1);
        $memberid = self::getJsonParamErr('memberid');
        $data = dbUser::delMember($urid, $memberid);
        if($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }else {
            return self::dataOut(array(), '删除成功');
        }

    }

    public function actionMemberscores() {
        $memberid = self::getJsonParamErr('memberid');
        $urid = self::getJsonParamErr('urid');
        $token =  self::getJsonParamErr('token');
        $check = self::checkUser($urid, $token);
        if(!$check) {
            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
        }
        $app = self::getJsonParamErr('app', 1);
        $page =  self::getJsonParam('page', 1);

        $data = dbUser::memberscores($memberid, $page, $app);
        if(empty($data)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }else {
            return self::dataOut($data, 'OK');
        }
    }

    /**
     * 通过验证码赋予权限
     * @return mixed|void
     */
    public function actionGetJurisdiction() {
        self::getArrayParamErr(['channel_id','type','invitation_code']);
        $params = \Yii::$app->request->bodyParams;
        $re = dbUser::getJurisdiction($params);

        if($re[0]){
            return self::dataOut($re[1]);
        } else {
            return self::errorOut($re[1]);
        }
    }
}