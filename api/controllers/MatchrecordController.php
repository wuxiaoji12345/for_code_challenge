<?php
/**
 * Created by wayne.
 * Date: 2019/2/1
 * Time: 3:46 PM
 */

namespace api\controllers;


use api\models\dbEnrollGroupinfo;
use api\models\dbEnrollInfo;
use api\models\dbMatch;
use api\models\dbTimesTrack;
use api\models\dbTimingRawdata;
use api\models\dbTimingStates;
use common\models\EnrollGroupinfo;
use common\models\TimesTrack;
use common\models\TimingRawdata;
use common\models\TimingStates;

class MatchrecordController extends Controller
{

    /**
     *  拉取参赛信息，包括参赛者名字，磁条，号码簿对应关系
     *
     * @auther Wayne
     * @return json
     */
    public function actionEnrollinfo() {
        $matchid = self::getJsonParamErr('matchid');

        if(empty($matchid)) {
            return self::dataOut(array(), '错误赛事', self::OTHER_ERR);
        }

        $info = dbEnrollInfo::getDbRegisterInfoListBymatch($matchid);
        if(empty($info)) {
            return self::dataOut(array(), '空赛事', self::OTHER_ERR);
        }

        $alldata['info'] = $info;
        $groupdata = dbEnrollGroupinfo::getGroupinfoBymatch($matchid);
        $alldata['group'] = $groupdata;

        return self::dataOut($alldata,'OK', self::MV_SUCCESS);

    }


    public  function  actionSetstarttime()
    {
        $trackid = self::getJsonParam('trackid', 0);
        $matchid = self::getJsonParam('matchid', 0);
        $newtime = self::getJsonParam('starttime', 0);
        if(empty($trackid) || !is_numeric($trackid) || empty($newtime)) {
            return self::dataOut(array(), '错误参数', self::OTHER_ERR);
        }

        $track = TimesTrack::findOne(['id'=>$trackid]);
        if(empty($track)) {
            return self::dataOut(array(), '错误赛事', self::OTHER_ERR);
        }

        $timeold = strtotime('2017-01-01');

        if($newtime < $timeold*1000) {
            return self::dataOut(array(), '时间错误', self::OTHER_ERR);
        }

        $track->starttime = $newtime;
        $ret = $track->save();
        if($ret) {
            $ret = dbTimingStates::Createstate($matchid, $trackid);
            if($ret >= 0) {
                //$sret = $this->CreateRealScore($matchid, $trackid);
                //$sret2 = $this->CreateScore($matchid, $trackid);
                $ret = dbTimingStates::Calc($matchid, $trackid);
            }
        }

        return self::dataOut(array('num'=>$ret), 'OK', self::MV_SUCCESS);
    }



    public function actionGetgroupstate()
    {
        $trackid = self::getJsonParam('trackid', 0);
        $matchid = self::getJsonParam('matchid', 0);
        $groupnumber = self::getJsonParam('gnum', 0);

        if(empty($trackid) || empty($groupnumber)) {
            return self::dataOut(array(), '错误参数', self::OTHER_ERR);
        }

        $groupinfo = EnrollGroupinfo::findOne(['trackid'=>$trackid, 'groupnumber'=>$groupnumber]);

        $rule = dbTimesTrack::getRule($trackid);
        $cps = json_decode($rule['cps'],true);
        $readername = $cps[0]['name'];

        $rawmodel = TimingRawdata::find()->select('tagid, readerid, number, time, create_time');
        $rawmodel->andFilterWhere(['trackid'=>$trackid]);
        $rawmodel->andFilterWhere(['>','time', $rule['starttime']]);
        $numbermatch = $groupnumber.'%';

        if(empty($groupinfo)) {
            $rawmodel->andFilterWhere(['=','number',$groupnumber]);
        } else {
            $gchip = strtolower($groupinfo->chipid);
            $rawmodel->andFilterWhere(['or',['like','number',$numbermatch,false],['=','tagid',$gchip]]);
        }


        $rawmodel->orderBy('time asc');
        $data = $rawmodel->asArray()->all();

        $states = TimingStates::find()->andFilterWhere(['trackid'=>$trackid, 'scorename'=>$groupnumber, 'isvalued'=>1]);
        $states->orderBy('statenumber asc');
        $statedata = $states->asArray()->all();

        $unmatchstate = array();
        for ($j=0; $j<count($statedata); $j++) {
            $onestate = $statedata[$j];
            for($i=0;$i<count($data);$i++) {
                $statevalue = dbTimingStates::getStateValue($onestate);
                if($statevalue == $data[$i]['time']) {
                    $data[$i]['statenumber'] = $onestate['statenumber'];
                    if(!empty($onestate['statedistance']) && $j>0) {
                        if(!empty($statedata[$j]['speed'])) {
                            $data[$i]['speed'] = $statedata[$j]['speed'];
                        } else if($statedata[$j-1]['isvalued'] == 1) {
                            $laststatevalue = dbTimingStates::getStateValue($statedata[$j-1]);
                            $data[$i]['speed'] = intval(($statevalue-$laststatevalue)/$onestate['statedistance']);
                        }
                    }

                    break;
                }
            }

            if($i == count($data)) {
                $unmatchstate[] = $onestate;
            }

        }

        $output['trackid'] = $trackid;
        $output['matchid'] = $matchid;
        $output['gnum'] = $groupnumber;
        $output['raw'] = $data;
        $output['unmatch'] = $unmatchstate;

        return self::dataOut($output, 'OK', self::MV_SUCCESS);

    }


    public function actionSetstate()
    {
        $matchid = self::getJsonParam('matchid',0);
        $trackid = self::getJsonParam('trackid', 0);
        $statenumber = self::getJsonParam('statenumber', 0);
        $groupnumber = self::getJsonParam('groupnumber', 0);
        $settime = self::getJsonParam('settime', 0);
        $clear = self::getJsonParam('clearstate',0);
        if(empty($trackid) || empty($groupnumber) || empty($statenumber) || empty($settime)) {
            return self::dataOut(array(), '错误赛事', self::OTHER_ERR);
        }

        $rule = dbTimesTrack::getRule($trackid);
        if(empty($rule)) {
            return self::dataOut(array(), '缺少赛道', self::OTHER_ERR);
        }

        $params = ['trackid'=>$trackid,'scorename'=>$groupnumber,'statenumber'=>$statenumber];
        $model = TimingStates::findOne($params);
        if(empty($model)) {
            return self::dataOut(array(), '无此赛事状态', self::OTHER_ERR);
        }

        $model->statevalue = $settime;
        $model->statevalue_m = $settime;
        $model->isvalued = 1;

        $query= TimingStates::find()->andFilterWhere(['trackid'=>$trackid, 'scorename'=>$groupnumber]);
        $query->andFilterWhere(['<','statenumber',$statenumber])->orderBy('statenumber desc');
        $allstate = $query->asArray()->all();
        $model->speed = '';
        if(!empty($allstate)) {
            $distance = $model->statedistance;
            for($i=0; $i<count($allstate); $i++) {
                $onestate = $allstate[$i];
                if($onestate['isvalued'] == 1 && $onestate['statenumber'] != $clear) {
                    $onevalue = dbTimingStates::getStateValue($onestate);
                    $model->speed = intval(($settime - $onevalue)/$distance);
                    break;
                }else if($statenumber == 1) {
                    $model->speed = intval(($settime - $rule['starttime'])/$distance);
                } else {
                    $distance += $onestate['statedistance'];
                }
            }

        }


        $model->save();
        $setparams = ['isvalued'=>0,'statevalue1'=>0,'statevalue2'=>0,'statevalue_h'=>0, 'statevalue_m'=>0, 'speed'=>0, 'statevalue'=>0];
        $where = "trackid=$trackid and scorename = '$groupnumber' and statenumber >$statenumber";

        $ret = TimingStates::updateAll($setparams, $where);
        dbTimingStates::calconegroup($matchid, $trackid, $groupnumber, $rule['starttime'], $rule['scoretype']);
        return self::dataOut(array('num'=>$ret), '修改成功', self::MV_SUCCESS);
    }


    /**
     * 根据赛事系统id活动赛事系列信息.
     *
     *
     * @auther Wayne
     * @return 成功返回状态码200和插入条数,异常返回状态码203
     **/
    public function actionAdddata () {
        $rawdata = self::getJsonParam('raw','');
        if(empty($rawdata)) {
            return self::dataOut(array(), '空数据', self::OTHER_ERR);
        }

        $rawdata = json_decode($rawdata, true);
        if(empty($rawdata) || !is_array($rawdata)) {
            return self::dataOut(array(), '空记录', self::OTHER_ERR);
        }

        $matchid = $rawdata[0][0];
        $trackid = $rawdata[0][1];
        $match = dbMatch::getDbMatchDetail($matchid);
        if(empty($match) ) {
            return self::dataOut(array(), '没有相关赛事', self::OTHER_ERR);
        }

        $param = array('matchid','trackid','readerid','tagid','portid','mode','time','create_time','number');
        $num = dbTimingRawdata::batchInsert($param, $rawdata);

        /*
         * 采集机是否直通，不计算
         */
        $rule = dbTimesTrack::getRule($trackid);
        if($rule['bypass'] == 1) {
            $result = array(
                'num' => $num
            );
            return self::dataOut($result);
        }

        foreach ($rawdata as $onedata) {
            $tracks[$onedata[0]][$onedata[1]] = 1;
        }

        if(!empty($tracks) && count($tracks)>0 ) {
            foreach ($tracks as $matchid => $value) {
                foreach ($value as $trackid => $v) {
                    //echo "(".$match.",".$track.")";
                    if (empty($matchid) || empty($trackid)) {
                        continue;
                    }

                    dbTimingStates::Calc($matchid, $trackid);
                }
            }
        }

        $result = array(
            'num' => $num
        );
        return self::dataOut($result);
    }

}