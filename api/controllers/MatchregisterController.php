<?php
/**
 * Created by wayne.
 * Date: 2019/4/11
 * Time: 3:23 PM
 */

namespace api\controllers;



use api\dbmodels\dbMatch;
use api\dbmodels\dbUser;

class MatchregisterController extends Controller
{

    public function actionMymemberlist(){
        $urid    = self::getJsonParamErr('urid');
        $page   = self::getJsonParam('page',1);
        $gid = self::getJsonParamErr('gid');
        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $token =  self::getJsonParamErr('token');
        $check = self::checkUser($urid, $token);
        if(!$check) {
            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
        }

        $data = dbUser::getMyMemberList($urid,$gid, $page);

        self::checkData($data);

        return self::dataOut($data);
    }


    public function actionAddmember() {
        $json     = self::getJsonParamErr('json');
        $urid   = self::getJsonParamErr('urid');
        $memberid = self::getJsonParam('memberid');
        $token = self::getJsonParamErr('token');
        $check = self::checkUser($urid, $token);
        if(!$check) {
            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
        }

        $gid = self::getJsonParamErr('gid');
        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $data = dbUser::addMembers($urid, $gid, $json, $memberid);
        if($data == false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }

        return self::dataOut (array(),'保存成功');
    }


    public function actionDeletemember() {
        $urid = self::getJsonParamErr('urid');
        $memberid = self::getJsonParamErr('memberid');
        $token = self::getJsonParamErr('token');
        $check = self::checkUser($urid, $token);
        if(!$check) {
            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
        }
        $gid = self::getJsonParamErr('gid');
        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $data = dbUser::delMembers($urid, $memberid);
        if(empty($data)) {
            return self::errorOut('删除失败');
        }
        return self::dataOut(array(),'删除成功');
    }




    public function actionSubmitpremembers(){

        $json     = self::getJsonParamErr('json');
        $rrid     = self::getJsonParamErr('rrid');
        $urid   = self::getJsonParamErr('urid');
        $items = self::getJsonParamErr('items');

        $gid = self::getJsonParamErr('gid');
        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $token = self::getJsonParamErr('token');
        $check = self::checkUser($urid, $token);
        if(!$check) {
            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
        }

        $data = dbUser::addPreMembers($urid,$rrid,$gid, $json, $items);
        if($data == false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }
        return self::dataOut($data);

    }

    public function actionSubmitmemberinfos(){
        $matchid  = self::getJsonParamErr('matchid');
        $categoryid = self::getJsonParam('categoryid');
        //$typeid   = self::getJsonParam('typeid');
        $rrid     = self::getJsonParamErr('rrid');
        $urid   = self::getJsonParamErr('urid');
        $riids    = self::getJsonParamErr('riids','请选择选手后提交');
        $gid = self::getJsonParamErr('gid');

        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }
        $token = self::getJsonParamErr('token');
        $check = self::checkUser($urid, $token);
        if(!$check) {
            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
        }

        $data = dbUser::checkRegisterMemberInfos($urid,$matchid,$gid, $rrid,$riids);

        if($data == false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }
        return self::dataOut(array(), '选手信息已提交成功', self::MV_SUCCESS);
    }










    public function actionSubmitteampremembers(){

        $matchid  = self::getJsonParamErr('matchid');
        $json     = self::getJsonParamErr('json');
        //$typeid   = self::getJsonParamErr('typeid');
        $rrid     = self::getJsonParamErr('rrid');
        $urid   = self::getJsonParamErr('urid');
        $riid   = self::getJsonParam('riid');
        $gid = self::getJsonParamErr('gid');
        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $data = dbUser::addTeamPreMembers($urid,$gid,$matchid,$rrid,$json,$riid);
        if($data == false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }
        return self::dataOut($data);

    }

    public function actionTypeattrtmpls(){

        $typeid  = self::getJsonParamErr('typeid');
        $gid = self::getJsonParamErr('gid');
        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $data = dbMatch::getRegisterTypeAttrTmpls($typeid);
        if($data == false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }
        self::checkData($data);
        return self::dataOut($data);
    }


//    public function actionMatchinfo(){
//
//        $typeid = self::getJsonParamErr('typeid');
//        $rgid = self::getJsonParamErr('rgid');
//        $gid = self::getJsonParamErr('gid');
//        $gid = $this->checkGid($gid);
//        if(empty($gid)) {
//            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
//        }
//        $data = dbMatch::getMatchInfo($rgid,$typeid);
//        if($data == false) {
//            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
//        }
//        self::checkData($data);
//        return self::dataOut($data);
//    }


    public function actionMymatchindex(){
        $urid = self::getJsonParamErr('urid');
//        $token = self::getJsonParamErr('token');
//        $check = self::checkUser($urid, $token);
//        if(!$check) {
//            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
//        }
        $page = self::getJsonParam('page',1);
        $data = dbMatch::getMyMatchListPages($urid,$page);

        self::checkData($data,'暂无比赛');
        return self::dataOut($data);
    }


    public function actionOrderdetail() {
        $urid = self::getJsonParamErr('urid');
        $token = self::getJsonParamErr('token');
        $check = self::checkUser($urid, $token);
        if(!$check) {
            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
        }
        $rrid = self::getJsonParamErr('rrid');

        $data = dbMatch::getOrderDetail($urid, $rrid);
        if($data == false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }

        return self::dataOut($data);

    }

    public function actionPrememberlist(){
        $typeid  = self::getJsonParamErr('typeid');
        $rrid    = self::getJsonParamErr('rrid');
        $urid    = self::getJsonParamErr('urid');
        $token = self::getJsonParamErr('token');
        $check = self::checkUser($urid, $token);
        if(!$check) {
            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
        }

        $data = dbMatch::getPreRegisterMemberList($urid,$rrid,$typeid);

        self::checkData($data);

        return self::dataOut($data);
    }



    public function actionEditregisteruser(){
        $password  = self::getJsonParam('password');
        $urid = self::getJsonParamErr('urid');
        $mobile = self::getJsonParamErr('mobile');
        $verifycode = self::getJsonParamErr('verifycode');
        $app = self::getJsonParamErr('app');
        $gid = self::getJsonParamErr('gid');
        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $data = dbMatch::editUserRegister($urid,$mobile,$password,$verifycode,$gid,$app);
        if($data == false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }
        self::checkData($data,'保存失败');
        return self::dataOut($data);
    }



}