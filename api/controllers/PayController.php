<?php
/**
 * Created by wayne.
 * Date: 2019/4/11
 * Time: 3:23 PM
 */

namespace api\controllers;


use Yii;
use api\dbmodels\dbPay;
use common\helpers\Utils;

class PayController extends Controller
{

    public function actionRegisterprepayorder(){

        $urid = self::getJsonParamErr('urid');
        $matchid = self::getJsonParamErr('matchid');
        $typeid = self::getJsonParamErr('typeid');
        $gid = self::getJsonParamErr('gid');
        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $app = self::getJsonParamErr('app');
        $token =  self::getJsonParamErr('token');
        $check = self::checkUser($urid, $token);
        if(!$check) {
            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
        }


        $data = dbPay::registerPrePayOrder($urid,$matchid ,$typeid, $gid, $app);
        if($data == false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], $GLOBALS['errorcode']);
        }

        return self::dataOut($data);
    }



    public function actionRegisterpayorder(){

        $rrid       = self::getJsonParam('rrid');
        $paytype    = self::getJsonParam('paytype',PAY_FREE);
        $urid     = self::getJsonParamErr('urid');
        //$matchid    = self::getJsonParamErr('matchid');
        //$typeid     = self::getJsonParamErr('typeid');
        $paymethod  = self::getJsonParamErr('paymethod');
        $returnurl  = self::getJsonParamErr('returnurl');
        $speccode   = self::getJsonParam('speccode','');
        $seosource  = self::getJsonParam('seosource','');
        $code       = self::getJsonParam('code','');
        $app = self::getJsonParamErr('app');
        $token =  self::getJsonParamErr('token');
        $check = self::checkUser($urid, $token);
        if(!$check) {
            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
        }

        $gid = self::getJsonParamErr('gid');
        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $data = dbPay::payOrder($app, $urid,$gid, $rrid,$paytype,$paymethod,$returnurl,$code,$seosource,$speccode);
        if($data == false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }

        self::checkData($data);
        return self::dataOut($data);

    }

    public function actionWxpaynotify(){

        $res = dbPay::dueWxpayNotify();
        echo $res;
    }

    public function actionCheckin() {
        $urid = self::getJsonParamErr('urid');
        $token = self::getJsonParamErr('token');
        $itemid = self::getJsonParamErr('itemid');
        $check = self::checkUser($urid, $token);
        $app = self::getJsonParamErr('app');
        if(!$check) {
            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
        }
        $checkcode = self::getJsonParamErr('checkcode');
        $gid = self::getJsonParamErr('gid');
        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $key = Yii::$app->params['gidKey'];
        $orderid = Utils::ecbDecrypt($key, $checkcode);
        if(empty($orderid)) {
            return self::dataOut(array(), '错误的检录码', self::OTHER_ERR);
        }
        $data = dbPay::doCheckin($itemid, $urid, $gid, $orderid, $app);
        if($data == false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }

        self::checkData($data);
        return self::dataOut(array(), '检录成功');

    }

}