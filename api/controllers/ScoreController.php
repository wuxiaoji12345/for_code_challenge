<?php
/**
 * Created by wayne.
 * Date: 2019/1/30
 * Time: 5:33 PM
 */

namespace api\controllers;


use api\dbmodels\dbMatch;
use api\dbmodels\dbScore;
use api\models\Banners;
use api\models\Match;
use common\models\AdminOperationLog;
use SebastianBergmann\Environment\OperatingSystemTest;
use yii\log\Logger;

class ScoreController extends Controller
{
    public function actionSessioninfo()
    {
        $ssid = self::getJsonParamErr('ssid');
        $itemid = self::getJsonParam('itemid', 0);

        $data = dbScore::getSessioninfo($ssid, $itemid);
        if ($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }
        return self::dataOut($data);
    }

    public function actionSelectstart()
    {
        $ssid = self::getJsonParamErr('ssid');
        $itemid = self::getJsonParamErr('itemid');
        $groupnum = self::getJsonParamErr('groupnum');

        $data = dbScore::selectStart($ssid, $itemid, $groupnum);
        if ($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }
        return self::dataOut(array(), '选择成功');
    }

    public function actionSettime()
    {
        $ssid = self::getJsonParamErr('ssid');
        $itemid = self::getJsonParam('itemid', 0);
        $type = self::getJsonParamErr('type');  //1,开始；2，结束
        $groupnum = self::getJsonParam('groupnum', 0);
        $lane = self::getJsonParam('lane', 0);
        $timestamp = self::getJsonParamErr('time'); //毫秒

        if (empty($lane)) {
            if (empty($itemid) || empty($groupnum)) {
                return self::dataOut(array(), '总裁判必须选择项目和分组', self::OTHER_ERR);
            }
        }

        $data = dbScore::Settime($timestamp, $ssid, $itemid, $groupnum, $lane, $type);
        if ($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }
        $data = is_array($data) ? $data : [];
        //计算秒表成绩
        if($type==2)  dbScore::calc($ssid,2);
        return self::dataOut($data, '上传成功');

    }

    public function actionGetstartinfo()
    {
        $ssid = self::getJsonParamErr('ssid');
        $lane = self::getJsonParamErr('lane');

        $data = dbScore::Getstartinfo($ssid, $lane);
        if (empty($data)) {
            return self::dataOut(array());
        }
        return self::dataOut($data);
    }


    public function actionCalc()
    {


        $ssid = self::getJsonParamErr('ssid');
        $type = self::getJsonParam('type', 1);
        $data = dbScore::calc($ssid, $type);
        if (empty($data)) {
            return self::dataOut(array());
        }
        return self::dataOut($data);
    }


    public function actionShowscore()
    {
        $ssid = self::getJsonParamErr('ssid');
        $itemid = self::getJsonParamErr('itemid');
        $groupnum = self::getJsonParam('groupnum', 0);
        $unit = self::getJsonParam('unit', '');
        $gender = self::getJsonParam('gender', 0);

        $data = dbScore::getscore($ssid, $itemid, $groupnum, $unit, $gender);
        if ($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }
        return self::dataOut($data, '上传成功');
    }


    public function actionCreategroupscore()
    {
        $matchid = self::getJsonParamErr('matchid');
        $data = dbScore::calcGroupscore($matchid);
        if ($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }
        return self::dataOut($data, '生成成功');
    }

    public function actionGroupscore()
    {
        $matchid = self::getJsonParamErr('matchid');
        $data = dbScore::getGroupscore($matchid);
        if ($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }
        return self::dataOut($data, 'ok');
    }

    //修改成绩 or 新增

    /**
     * lito
     * @return mixed
     */
    public function actionEditScore()
    {

        $statesid = self::getJsonParamErr('statesid');
        $score = self::getJsonParamErr('score');
        $type = self::getJsonParam('type', 1);
        $state = self::getJsonParam('state', 1);
        $data = dbScore::editScore($statesid, $score, $type, $state);

        if ($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }
        return self::dataOut([], '操作成功');
    }

    public function actionGetCert()
    {
        $ssid = self::getJsonParamErr('ssid');
        $enrollid = self::getJsonParamErr('enrollid');
        $data = dbScore::genCert($ssid, $enrollid);
        if ($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }
        return self::dataOut($data, '操作成功');
    }

    public function actionChangeGroupStates()
    {
        $statesid = self::getJsonParamErr('statesid');
        $changeitemid = self::getJsonParamErr('changeitemid');
        $changegroupnum = self::getJsonParamErr('changegroupnum');

        $data = dbScore::changeGroupStates($statesid, $changeitemid, $changegroupnum);
        if ($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }
        return self::dataOut([], '操作成功');
    }


    public function actionUploadData()
    {

//        $data = self::getJsonParam('data');
//        $logModel = new AdminOperationLog();
//        $logModel->user_id = 1;
//        $logModel->path = 'score';
//        $logModel->method = 'post';
//        $logModel->input = json_encode($data,JSON_UNESCAPED_UNICODE);
//        $logModel->created_at = date('Y-m-d H:i:s');
//        $logModel->ip = '127.0.0.1';
//        if(!$logModel->save())
//        {
//            print_r($logModel->getErrors());exit;
//        }

        $data = self::getJsonParamErr('data');
        if(is_array($data))
            $data = json_encode($data);
        $data = json_decode($data,true);
        foreach ($data as $key=>$v){
            if(!isset($v['ssid']) || !isset($v['lane']) || !isset($v['time']) || !isset($v['end_time']) ) {
                self::dataOut(array(),'参数有误', self::OTHER_ERR);
            }

            //$ssid = $v['ssid'];
            $ssid = 38;
            $lane = $v['lane'];
            $timestamp = $v['time'];
            $timestamp = $v['time'];
            $end_time = $v['end_time'] + time()*1000;
            $start_time = isset($v['start_time'])?$v['start_time']:0;
            $uuid = isset($v['uuid'])?$v['uuid']:"";

            //格式化 时间  00:00'41".33
            $timestamp  =    str_replace(["'",'".'],":",$timestamp);
            $timestamp  =   explode(":",$timestamp);

            if(count($timestamp)!=4)  return self::dataOut(array(),'time参数有误', self::OTHER_ERR);
            $timestamp  =    ($timestamp[0]*3600+$timestamp[1]*60+$timestamp[2])*1000 + $timestamp[3]*10;
            $data = dbScore::uploadData($ssid, $lane, $timestamp, $end_time, $start_time, $uuid);
            if ($data === false) {
                return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
            }
            return self::dataOut([], '操作成功');
        }
    }

//calc($ssid,2);


//    public function actionCals()

    public function actionLastgroupscore()
    {
        $matchid = self::getJsonParamErr('matchid');

        $data = dbScore::Lastgroupscore($matchid);
        if ($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }
        return self::dataOut($data, 'OK', self::MV_SUCCESS);
    }


}