<?php
/**
 * Created by wayne.
 * Date: 2019/1/30
 * Time: 5:33 PM
 */

namespace api\controllers;


use api\dbmodels\dbMatch;
use api\dbmodels\dbPool;
use api\dbmodels\dbScore;
use api\models\Banners;
use api\models\Match;
use common\models\UserChannel;
use common\models\UserChannelExtra;

class PoolController extends Controller
{
    public function actionList() {
        $sid = self::getJsonParamErr('sid');

        $urid = self::getJsonParamErr('urid');
        $token =  self::getJsonParamErr('token');
        $check = self::checkUser($urid, $token);
        if(!$check) {
            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
        }

        $data = dbPool::getList($sid);
        if($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }

        return self::dataOut($data);
    }


    public function actionUpload() {
        $sid = self::getJsonParamErr('sid');
        $poid = self::getJsonParamErr('poid');
        $urid = self::getJsonParamErr('urid');
        $token =  self::getJsonParamErr('token');
        $check = self::checkUser($urid, $token);
        if(!$check) {
            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
        }

        $type = self::getJsonParamErr('type');
        $value = self::getJsonParamErr('value');

        $channel = UserChannel::findOne(['urid'=>$urid]);
        $extrainfo = UserChannelExtra::findOne(['user_channel_id'=>$channel->id]);
        if(empty($extrainfo) || $extrainfo->is_owner != $sid) {
            return self::dataOut(array(), '没有权限', self::OTHER_ERR);
        }

        $data = dbPool::Upload($urid, $extrainfo, $sid, $poid, $type, $value);
        if($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }

        return self::dataOut(array(), '上传成功');

    }

    public function actionQualitylist() {
        $sid = self::getJsonParamErr('sid');
        $poid = self::getJsonParamErr('poid');
        $urid = self::getJsonParamErr('urid');
        //$token =  self::getJsonParamErr('token');
        $page = self::getJsonParam('page', 1);
//        $check = self::checkUser($urid, $token);
//        if(!$check) {
//            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
//        }

        $channel = UserChannel::findOne(['urid'=>$urid]);
        $extrainfo = UserChannelExtra::findOne(['user_channel_id'=>$channel->id]);
        if(empty($extrainfo) || $extrainfo->is_owner != $sid) {
            return self::dataOut(array(), '没有权限', self::OTHER_ERR);
        }


        $data = dbPool::getQualitylist($urid, $sid, $poid, $page);
        if($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }

        return self::dataOut($data);

    }

 }