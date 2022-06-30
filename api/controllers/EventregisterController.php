<?php
/**
 * Created by wayne.
 * Date: 2019/4/11
 * Time: 3:23 PM
 */

namespace api\controllers;



use api\dbmodels\dbEvent;
use api\dbmodels\dbEventUser;
use api\dbmodels\dbMatch;
use api\dbmodels\dbUser;

class EventregisterController extends Controller
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

        $data = dbEventUser::getMyMemberList($urid,$gid, $page);

        self::checkData($data);

        return self::dataOut($data);
    }

    public function actionSubmitmemberinfos(){
        $matchid  = self::getJsonParamErr('matchid');
        $categoryid = self::getJsonParam('categoryid');
        $rgid     = self::getJsonParam('rgid');
        $typeid   = self::getJsonParam('typeid');
        $rrid     = self::getJsonParamErr('rrid');
        $urid   = self::getJsonParamErr('urid');
        $riids    = self::getJsonParamErr('riids','请选择选手后提交');
        $gid = self::getJsonParamErr('gid');

        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $data = dbEvent::addEventMemberInfos($urid,$categoryid,$matchid,$gid, $rgid,$typeid,$rrid,$riids);

        if($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }
        return self::dataOut(array(), '选手信息已提交成功', self::MV_SUCCESS);
    }


    public function actionSubmitpremembers(){

        $rgid     = self::getJsonParamErr('rgid');
        $matchid  = self::getJsonParamErr('matchid');
        $json     = self::getJsonParamErr('json');
        $typeid   = self::getJsonParamErr('typeid');
        $rrid     = self::getJsonParamErr('rrid');
        $urid   = self::getJsonParamErr('urid');
        $gid = self::getJsonParamErr('gid');
        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $data = dbEvent::addPreMembers($urid,$matchid,$gid,$rgid,$typeid,$rrid,$json);
        if($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }
        return self::dataOut($data);

    }

    public function actionSubmitteampremembers(){

        $rgid     = self::getJsonParamErr('rgid');
        $matchid  = self::getJsonParamErr('matchid');
        $json     = self::getJsonParamErr('json');
        $typeid   = self::getJsonParamErr('typeid');
        $rrid     = self::getJsonParamErr('rrid');
        $urid   = self::getJsonParamErr('urid');
        $riid   = self::getJsonParam('riid');
        $gid = self::getJsonParamErr('gid');
        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $data = dbEvent::addTeamPreMembers($urid,$gid,$matchid,$rgid,$typeid,$rrid,$json,$riid);
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

        $data = dbEvent::getEventTypeAttrTmpls($typeid);
        if($data == false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }
        self::checkData($data);
        return self::dataOut($data);
    }


    public function actionMatchinfo(){

        $typeid = self::getJsonParamErr('typeid');
        $rgid = self::getJsonParamErr('rgid');
        $gid = self::getJsonParamErr('gid');
        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }
        $data = dbEvent::getEventInfo($rgid,$typeid);
        if($data == false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }
        self::checkData($data);
        return self::dataOut($data);
    }


    public function actionMymatchindex(){
        $urid = self::getJsonParamErr('urid');
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

        $app = self::getJsonParam('app',0);
        $page = self::getJsonParam('page',1);
        $apph5 = self::getJsonParam('apph5',0);
        $data = dbEvent::getMyMatchListPages($urid,$page, $app, $apph5, $gid);

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

        $data = dbEvent::getOrderDetail($urid, $rrid);
        if($data == false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }

        return self::dataOut($data);

    }

    public function actionPrememberlist(){
        $rgid    = self::getJsonParamErr('rgid');
        $typeid  = self::getJsonParamErr('typeid');
        $rrid    = self::getJsonParamErr('rrid');
        $urid    = self::getJsonParamErr('urid');
        $token = self::getJsonParamErr('token');
        $check = self::checkUser($urid, $token);
        if(!$check) {
            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
        }

        $data = dbEvent::getPreRegisterMemberList($urid,$rrid,$rgid,$typeid);

        self::checkData($data);

        return self::dataOut($data);
    }

    public function actionAddmember() {
        $json     = self::getJsonParamErr('json');
        $urid   = self::getJsonParamErr('urid');
        $memberid = self::getJsonParam('memberid');
        $gid = self::getJsonParamErr('gid');
        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $data = dbEvent::addMembers($urid, $gid, $json, $memberid);
        if($data == false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }

        return self::dataOut (array(),'保存成功');
    }

    public function actionDelpremember(){
        $urid = self::getJsonParamErr('urid');
        $token = self::getJsonParamErr('token');
        $check = self::checkUser($urid, $token);
        if(!$check) {
            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
        }

        $riid = $this->getJsonParamErr('riid');
        $rrid = $this->getJsonParamErr('rrid');


        $data = dbEvent::delRegisterPreMember($urid, $rrid,$riid);

        if($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }
        return self::dataOut(array(),'删除成功');

    }

    public function actionDeletemember() {
        $urid = self::getJsonParamErr('urid');
        $memberid = self::getJsonParamErr('memberid');
        $token = self::getJsonParamErr('token');
        $check = self::checkUser($urid, $token);
        if(!$check) {
            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
        }

        $data = dbEventUser::delMembers($urid, $memberid);

        if(empty($data)) {
            return self::errorOut('删除失败');
        }

        return self::dataOut(array(),'删除成功');
    }

    public function actionGroupattrtmpls(){

        $typeid  = self::getJsonParamErr('typeid');

        $data = dbEvent::getRegisterGroupAttrTmpls($typeid);

        if($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }
        return self::dataOut($data);

    }

    public function actionEditregistergroup(){

        $rgid       = self::getJsonParamErr('rgid');
        $json       = self::getJsonParamErr('groupjson');
        $typeid     = self::getJsonParamErr('typeid');
        $matchid    = self::getJsonParamErr('matchid');
        $urid     = self::getJsonParamErr('urid');
        $rrid = self::getJsonParamErr('rrid');

        $data = dbEvent::editRegisterGroup($urid,$matchid,$rrid,$rgid,$typeid,$json);

        if($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }

        return self::dataOut($data);

    }

    public function actionGroupinfo(){

        $rgid   = self::getJsonParamErr('rgid');
        $typeid = self::getJsonParamErr('typeid');

        $data = dbEvent::getRegisterGroupInfo($rgid,$typeid);

        if($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }

        return self::dataOut($data);
    }

    public function actionRegisterinfo(){

        $riid = self::getJsonParamErr('riid');

        $data = dbEvent::getRegisterInfo($riid);

        if($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }

        return self::dataOut($data);

    }

}