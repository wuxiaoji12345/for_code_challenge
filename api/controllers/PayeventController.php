<?php
/**
 * Created by wayne.
 * Date: 2019/4/11
 * Time: 3:23 PM
 */

namespace api\controllers;


use api\dbmodels\dbEventPay;
use Yii;
use api\dbmodels\dbPay;
use common\helpers\Utils;

class PayeventController extends Controller
{

    public function actionRegisterprepayorder(){

        $urid = self::getJsonParamErr('urid');
        $matchid = self::getJsonParamErr('matchid');
        $typeid = self::getJsonParamErr('typeid');
        $gid = self::getJsonParamErr('gid');
        $apph5 = self::getJsonParam('apph5');
        $invitecode = self::getJsonParam('invitecode');
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

        $data = dbEventPay::registerPrePayOrder($urid,$matchid ,$typeid, $gid, $app, $apph5,$invitecode);
        if($data == false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], $GLOBALS['errorcode']);
        }

        return self::dataOut($data);
    }

    public function actionRegisterpayorder(){

        $rrid       = self::getJsonParam('rrid');
        $paytype    = self::getJsonParam('paytype',PAY_FREE);
        $urid     = self::getJsonParamErr('urid');
        $matchid    = self::getJsonParamErr('matchid');
        $typeid     = self::getJsonParamErr('typeid');
        $paymethod  = self::getJsonParamErr('paymethod');
        $returnurl  = self::getJsonParamErr('returnurl');
        $speccode   = self::getJsonParam('speccode','');
        $seosource  = self::getJsonParam('seosource','');
        $code       = self::getJsonParam('code','');
        $app = self::getJsonParamErr('app');
        $apph5 = self::getJsonParam('apph5');
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

        $data = dbEventPay::payOrder($app, $urid,$gid, $matchid, $typeid, $rrid,$paytype,$paymethod,$returnurl,$code,$seosource,$speccode,$apph5);
        if($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }

        return self::dataOut($data);

    }


    public function actionWxpaynotify(){

        $res = dbEventPay::dueWxpayNotify();
        echo $res;
    }

    public function actionAlipaynotify(){

        $res = dbEventPay::dueAlipayNotify();

        echo $res;
    }

}