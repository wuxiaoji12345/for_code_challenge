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

class MatchController extends Controller
{

    public function actionBanners(){
        $position = self::getJsonParamErr('position');
        $datetime = date('Y-m-d H:i:s', time());
        $data = Banners::find()->select('imgurl, jumptype, jumpurl, jumpvalue, starttime, endtime, position')
            ->andFilterWhere(['position'=>$position,'status'=>1])
            ->andFilterWhere(['<=','starttime', $datetime])
            ->andFilterWhere(['>=', 'endtime', $datetime])
            ->orderBy('weight desc')->asArray()->all();
        self::checkData($data);
        return self::dataOut ($data);
    }


    public function actionIndex() {
        $categoryid = self::getJsonParam('categoryid',0);
        $gid = self::getJsonParamErr('gid');
        $page = self::getJsonParam('page', 1);
        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $data = dbMatch::getGidMatch($gid, $categoryid, $page);
        self::checkData($data,'暂无活动');
        return self::dataOut($data);
    }

    public function actionTiminglist() {
        $categoryid = self::getJsonParam('categoryid',0);
        $gid = self::getJsonParamErr('gid');
        $page = self::getJsonParam('page', 1);
        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $data = dbMatch::getTimingMatch($gid, $categoryid, $page);
        self::checkData($data,'暂无活动');
        return self::dataOut($data);
    }

    public function actionDetail(){
        $matchid    = self::getJsonParamErr('matchid',0);
        $urid = self::getJsonParam('urid');
        $gid = self::getJsonParamErr('gid');
        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $data = dbMatch::getMatchDetail($matchid, $gid, $urid);
        self::checkData($data);
        return self::dataOut($data);
    }

    public function actionRegistertypelist(){

        $matchid = self::getJsonParamErr('matchid');
        $gid = self::getJsonParamErr('gid');
        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }
        $data = dbMatch::getRegisterTypeList($matchid, $gid);

        if($data == false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }

        return self::dataOut($data);
    }

    public function actionSessionlist() {
        $matchid = self::getJsonParamErr('matchid');
        $gid = self::getJsonParamErr('gid');
        $gid = $this->checkGid($gid);
        $gender = self::getJsonParam('gender', 0);
        $birth = self::getJsonParam('birth');
        $typeid = self::getJsonParam('typeid');
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }
        $data = dbMatch::getSessionList($matchid,  $gender, $birth, $typeid);
        if($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }

        return self::dataOut($data);
    }

    public function actionSearchenroll() {
        $matchid = self::getJsonParamErr('matchid');
        $keyword = self::getJsonParamErr('keyword');
        $gid = self::getJsonParamErr('gid');
        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $data = dbMatch::searchenroll($matchid, $keyword);
        if($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }

        return self::dataOut($data);
    }

    public function actionMatchintro(){
        $matchid = self::getJsonParamErr('matchid');
        $type = self::getJsonParam('type',0);
        $query =  Match::find()
            ->andFilterWhere(['id'=>$matchid]);

        if(empty($type)) {
            $query->select('intro');
            $data = $query->asArray()->one();
            $body = $data['intro'];
        } else {
            $query->select('disclaimer');
            $data = $query->asArray()->one();
            $body = $data['disclaimer'];
        }


        $start = '<html>
                <head>
                  <meta charset="utf-8">
                  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0,user-scalable=0">
                  <style>
                    img{
                        width:auto !important;
                        max-width:100% !important;
                        height:auto !important;
                    }
                   </style> 
                    </head>
                   
                  <body>';

        $end = '<script type="text/javascript">
              window.onload = function() {
                var iframe = window.parent.document.getElementsByClassName("match-desc")[0];
                var height = Math.max(document.documentElement.scrollHeight, document.documentElement.offsetHeight, document.documentElement.clientHeight);
                if( iframe ) {
                  iframe.style.height = height + "px";
                }
              }
            </script></body></html>';

        echo $start.$body.$end;
        exit;
    }

 }