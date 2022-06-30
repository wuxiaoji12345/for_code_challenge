<?php
/**
 * Created by wayne.
 * Date: 2019/4/10
 * Time: 12:02 PM
 */

namespace api\dbmodels;

use api\models\MatchSessionItem;
use api\models\MemberInfo;
use api\models\MemberScoreRecord;
use api\models\ScoreEnroll;
use api\models\ScoreGroup;
use api\models\ScoreStartcache;
use api\models\ScoreStates;
use api\models\TimingRawdata;
use common\helpers\Utils;
use common\models\TimingWatchRawdata;
use Yii;
use api\models\Banners;
use api\models\Match;
use api\models\MatchSession;
use api\models\RegisterDetail;
use api\models\RegisterInfo;
use api\models\RegisterRelation;
use api\models\RegisterType;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\db\Exception;


class dbScore
{

    public static function getSessioninfo($ssid, $itemid = 0)
    {
        $session = MatchSession::find()->select('matchid, name, stadium,start_time,province,city,district,address, lane')
            ->andFilterWhere(['id' => $ssid, 'status' => 1])
            ->asArray()->one();

        if (empty($session)) {
            $GLOBALS['errormsg'] = '错误场次id';
            return false;
        }
        $output['info'] = $session;

        $query = MatchSessionItem::find()->from(MatchSessionItem::tableName() . ' as a')
            ->joinWith('group as b', true)
            ->andFilterWhere(['a.ssid' => $ssid,'a.status' => 1]);

        if (!empty($itemid)) {
            $query->andFilterWhere(['a.id' => $itemid]);
        }
        $list = $query->orderBy('a.weight desc, a.distance asc, a.type asc, a.agemin asc, a.gender asc, b.groupnum asc')
            ->asArray()->all();


        for ($i = 0; $i < count($list); $i++) {
            $groupinfo = $list[$i]['group'];
            for ($j = 0; $j < count($groupinfo); $j++) {
                $enrolls = ScoreStates::find()->from(ScoreStates::tableName().' as a')
                    ->select('a.id,a.enrollname,a.enrollid,a.enrollgender,a.lane,b.unit,a.isvalued,a.score')
                    ->joinWith('enroll as b', false)
                    ->andFilterWhere(['a.itemid' => $groupinfo[$j]['itemid'], 'a.groupnum' => $groupinfo[$j]['groupnum']])
                    ->orderBy('a.lane asc')->asArray()->all();



                $list[$i]['group'][$j]['enrolls'] = $enrolls;
            }
        }

//        for ($i = 0; $i < count($list); $i++) {
//            if (empty($list[$i]['group'])) {
//                unset($list[$i]);
//            }
//        }

        $output['list'] = array_values($list);

        return $output;

    }


    public static function selectStart($ssid, $itemid, $groupnum)
    {
        $groupmodel = ScoreGroup::findOne(['ssid' => $ssid, 'itemid' => $itemid, 'groupnum' => $groupnum]);
        if (empty($groupmodel)) {
            $GLOBALS['errormsg'] = '错误分组id';
            return false;
        }

        $cachemodel = ScoreStartcache::findOne(['matchid' => $groupmodel->matchid, 'ssid' => $groupmodel->ssid]);
        if (empty($cachemodel)) {
            $cachemodel = new ScoreStartcache();
        }

        $sessname = MatchSession::findOne(['id' => $ssid]);
        $itemname = MatchSessionItem::findOne(['id' => $itemid]);
        $cachemodel->matchid = $groupmodel->matchid;
        $cachemodel->ssid = $groupmodel->ssid;
        $cachemodel->ssname = $sessname->name;
        $cachemodel->groupnum = $groupmodel->groupnum;
        $cachemodel->itemid = $groupmodel->itemid;
        $cachemodel->itemname = $itemname->name;
        $cachemodel->save();

        return true;
    }

    public static function calc($ssid,$type=1)
    {
        $session = MatchSession::findOne(['id' => $ssid]);
        if (empty($session)) {
            $GLOBALS['errormsg'] = '错误分组id';
            return false;
        }

        $allitems = MatchSessionItem::findAll(['ssid' => $ssid]);
        $itemnum = 0;
        $groupnum = 0;
        if (!empty($allitems)) {

            foreach ($allitems as $oneitem) {
                $itemnum++;
                $groups = ScoreGroup::findAll(['itemid' => $oneitem->id]);
                if (!empty($groups)) {
                    foreach ($groups as $onegroup) {
                        if($type==1){
                            self::Calcscore($ssid, $oneitem->id, $onegroup->groupnum, $oneitem->distance);
                        }else if($type==2){
                            self::CalcWatchscore($ssid, $oneitem->id, $onegroup->groupnum, $oneitem->distance);
                        }
                        $groupnum++;
                    }
                }

            }
        }

        return ["groupnum" => $groupnum, 'itemnum' => $itemnum];


    }

    public static function Calcscore($ssid, $itemid, $groupnum, $distance)
    {
        $groupmodel = ScoreGroup::findOne(['ssid' => $ssid, 'itemid' => $itemid, 'groupnum' => $groupnum]);
        if (empty($groupmodel)) {
            $GLOBALS['errormsg'] = '错误分组id';
            return false;
        }

        if (empty($groupmodel->starttime)) {
            $GLOBALS['errormsg'] = '缺少发枪时间';
            return false;
        }

        $scoremodel = ScoreStates::find()
            ->andFilterWhere(['itemid' => $itemid, 'groupnum' => $groupnum])
            ->andFilterWhere(['!=', 'isvalued', 1])
            ->all();

        if (empty($scoremodel))
            return true;

        foreach ($scoremodel as $onescore) {
            $data = TimingRawdata::find()->andFilterWhere(['ssid' => $ssid])
                ->andFilterWhere(['>', 'time', $groupmodel->starttime])
                ->andFilterWhere(['<', 'time', $groupmodel->endtime])
                ->andFilterWhere(['type' => 2])
                ->andFilterWhere(['lane' => $onescore->lane])
                ->orderBy('id asc')
                ->asArray()->all();

            if (!empty($data)) {
                foreach ($data as $onedata) {
                    $score = $onedata['time'] - $groupmodel->starttime;
                    if ($score / $distance < 100) {
                        continue;
                    }
                    $onescore->statevalue = $onedata['time'];
                    $onescore->statevalue_time = date('Y-m-d H:i:s', floor($onedata['time'] / 1000));
                    $onescore->score = $score;
                    $onescore->isvalued = 1;
                    $ret = $onescore->save();
                    break;
                }
            }

        }

        return true;
    }

    public static function CalcWatchscore($ssid, $itemid, $groupnum, $distance)
    {
        $groupmodel = ScoreGroup::findOne(['ssid' => $ssid, 'itemid' => $itemid, 'groupnum' => $groupnum]);

        if (empty($groupmodel)) {
            $GLOBALS['errormsg'] = '错误分组id';
            return false;
        }

        if (empty($groupmodel->starttime)) {
            $GLOBALS['errormsg'] = '缺少发枪时间';
            return false;
        }

        $scoremodel = ScoreStates::find()
            ->andFilterWhere(['itemid' => $itemid, 'groupnum' => $groupnum])
            ->andFilterWhere(['!=', 'isvalued', 1])
            ->all();

        if (empty($scoremodel))
            return true;



        foreach ($scoremodel as $onescore) {
            //找到最后一条
            $onedata = TimingWatchRawdata::find()
                ->andFilterWhere(['ssid' => $ssid,'status'=>1])
                ->andFilterWhere(['itemid' => $groupmodel->itemid])
                ->andFilterWhere(['>', 'end_time', $groupmodel->starttime])
                ->andFilterWhere(['<', 'end_time', $groupmodel->endtime])
                ->andFilterWhere(['lane' => $onescore->lane])
                ->orderBy('id desc')
                ->one();
            if (!empty($onedata)) {
                $score = $onedata->time;
                if ($score / $distance < 100) {
                    continue;
                }
                $onescore->statevalue = $onedata->end_time;
                $onescore->statevalue_time = date('Y-m-d H:i:s', floor($onedata->end_time / 1000));
                $onescore->score = $score;
                $onescore->isvalued = 1;
                $onescore->save();

                //跟新rawdata
                $onedata->status = 2;
                $onedata->save();





            }

        }

        return true;
    }

    public static function Settime($timestamp, $ssid, $itemid, $groupnum, $lane, $type)
    {
        $timecache = ScoreStartcache::findOne(['ssid' => $ssid]);
        //if (empty($itemid)) {
            $itemid = $timecache->itemid;
        //}

        //if (empty($groupnum)) {
            $groupnum = $timecache->groupnum;
        //}

        $groupmodel = ScoreGroup::findOne(['ssid' => $ssid, 'itemid' => $itemid, 'groupnum' => $groupnum]);
        if (empty($groupmodel)) {
            $GLOBALS['errormsg'] = '错误分组id';
            return false;
        }

        $matchmodel = Match::findOne(['id'=>$timecache->matchid]);
        if(time() > $matchmodel->end_time) {
            $GLOBALS['errormsg'] = '比赛已经结束';
            return false;
        }

        $iteminfo = MatchSessionItem::findOne(['id' => $itemid]);
        $distance = $iteminfo->distance;

        $rawdata = new TimingRawdata();
        $rawdata->matchid = $groupmodel->matchid;
        $rawdata->ssid = $ssid;
        $rawdata->itemid = $itemid;
        $rawdata->groupnum = $groupnum;
        $rawdata->lane = $lane;
        $rawdata->type = $type;
        $rawdata->time = $timestamp;
        $rawdata->status = 1;
        $rawdata->create_time = time();
        $ret = $rawdata->save();

        if ($ret) {
            if (empty($lane)) {
                if ($type == 1) {
                    $groupmodel->starttime = $timestamp;
                    $groupmodel->save();
                } else if ($type == 2) {
                    $groupmodel->endtime = $timestamp;
                    $groupmodel->save();
                    //self::Calcscore($ssid, $itemid, $groupnum, $distance);
                }

            } else {
                if ($type == 1) {
                    $scoremodel = ScoreStates::findOne(['itemid' => $itemid, 'groupnum' => $groupnum, 'lane' => $lane]);
                    if (!empty($scoremodel)) {
                        $scoremodel->startvalue = $timestamp;
                        $scoremodel->save();
                    }

                } else if ($type == 2) {

                    $scoremodel = ScoreStates::findOne(['itemid' => $itemid, 'groupnum' => $groupnum, 'lane' => $lane]);
                    if (!empty($scoremodel)) {
                        if (!empty($groupmodel->starttime)) {
                            $scoremodel->score = $timestamp - $groupmodel->starttime;
                            $score = $scoremodel->score;
                        } else {
                            $score = $timestamp - $scoremodel->startvalue;
                        }

                        $speed = $score / $distance;
                        if ($speed < 100) {
                            $GLOBALS['errormsg'] = '配速过快';
                            return false;
                        }

                        $scoremodel->statevalue = $timestamp;
                        $scoremodel->statevalue_time = date('Y-m-d H:i:s', floor($timestamp / 1000));
                        $scoremodel->isvalued = 1;
                        $scoremodel->save();


                        return ScoreStates::find()->asArray()->andWhere(['id' => $scoremodel->id])->one();


                    }

                }

            }


            return true;
        }

        $GLOBALS['errormsg'] = '保存错误';
        return false;

    }

    public static function Getstartinfo($ssid, $lane)
    {
        $info = ScoreStartcache::find()
            ->andFilterWhere(['ssid' => $ssid])->asArray()->one();

        if (!empty($info)) {
            $userinfo = ScoreStates::find()->select('enrollid,enrollgender,enrollname')
                ->andFilterWhere(['itemid' => $info['itemid'], 'groupnum' => $info['groupnum']])
                ->andFilterWhere(['lane' => $lane])
                ->asArray()->one();

            $info['enrollinfo'] = $userinfo;


        }

        return $info;
    }

    public static function getscore($ssid, $itemid, $groupnum, $unit, $gender)
    {
        $sessionitem = MatchSessionItem::find()->select('name, gender, distance, type')
            ->andFilterWhere(['id' => $itemid, 'ssid' => $ssid])->asArray()->one();
        if (empty($sessionitem)) {
            $GLOBALS['errormsg'] = '错误项目';
            return false;
        }


        if (empty($unit)) {
            $query = ScoreStates::find()->select('groupnum, lane, enrollgender, enrollname, score, isvalued')
                ->andFilterWhere(['itemid' => $itemid])
                ->andFilterWhere(['!=', 'isvalued', 0]);

            if (!empty($groupnum)) {
                $query->andFilterWhere(['groupnum' => $groupnum]);
            }

            if ($gender == 1 || $gender == 2) {
                $query->andFilterWhere(['enrollgender' => $gender]);
            }

            $data = $query->orderBy('isvalued asc, score asc')->asArray()->all();

            $output['info'] = $sessionitem;
            $output['list'] = $data;
            return $data;
        }

        if (!empty($unit)) {
            $scorearray = ScoreEnroll::find()->select('id')
                ->andFilterWhere(['ssid' => $ssid, 'itemid' => $itemid, 'unit' => $unit])
                ->asArray()->all();

            if (empty($scorearray)) {
                $output['info'] = $sessionitem;
                $output['list'] = [];
                return $output;
            }
            $scoreids = ArrayHelper::getColumn($scorearray, 'id');
            $query = ScoreStates::find()->select('groupnum, lane, enrollgender, enrollname, score, isvalued')
                ->andFilterWhere(['itemid' => $itemid])
                ->andFilterWhere(['!=', 'isvalued', 0])
                ->andFilterWhere(['in', 'enrollid', $scoreids]);

            if ($gender == 1 || $gender == 2) {
                $query->andFilterWhere(['enrollgender' => $gender]);
            }

            $data = $query->orderBy('isvalued asc, score asc')->asArray()->all();

            $output['info'] = $sessionitem;
            $output['list'] = $data;
            return $data;
        }
    }

    /**
     * @param $enrollid
     * @param $score
     * @return bool
     */
    public static function editScore($statesid, $score, $type = 1, $state)
    {


        $states = ScoreStates::findOne($statesid);

        if (empty($states)) {
            $GLOBALS['errormsg'] = '错误选手';
            return false;
        } else {
            //修改
            $tranns = Yii::$app->db->beginTransaction();
            try {
                if ($type == 1) {
                    //修改 states
                    $states->score = $score;
                    $states->isvalued = 1;
                    if (!$states->save()) throw new Exception('信息修改失败');
                    //插入 rawdata
                    $rawData = new TimingRawdata();
                    $rawData->matchid = $states->matchid;

                    $scoreEnroll = ScoreEnroll::findOne($states->enrollid);
                    if (!empty($scoreEnroll)) $rawData->ssid = $scoreEnroll->ssid;

                    $rawData->itemid = $states->itemid;
                    $rawData->groupnum = $states->groupnum;
                    $rawData->lane = $states->lane;
                    $rawData->type = 3;
                    $rawData->time = $score;
                    $rawData->create_time = time();
                    if (!$rawData->save()) throw new Exception('RAW跟新失败');
                } else if ($type == 2) {
                    $states->isvalued = $type;
                    $stateList = [1, 2, 3, 4,999];
                    if (in_array($state, $stateList)) {
                        if($state==999){
                            //恢复出厂
                            $states->isvalued = 0;
                            $states->score = null;
                            $states->statevalue = null;
                            $states->statevalue_time = null;
                            //$states->update_time = null;
                        }else{
                            $states->isvalued = $state;
                        }


                        if (!$states->save()) throw new Exception('状态修改失败');

                    } else {
                        throw new Exception('参数有误');
                    }


                }


                $tranns->commit();
                return true;
            } catch (Exception $e) {
                $tranns->rollBack();
                $GLOBALS['errormsg'] = $e->getMessage();
                return false;
            }

        }
    }

    public static function genCert($ssid, $enrollid)
    {
        $session = MatchSession::findOne($ssid);
        if (empty($session)) {
            $GLOBALS['errormsg'] = '错误分组';
            return false;
        }

        if (empty($session->cert_template)) {
            $GLOBALS['errormsg'] = '证书准备中,请稍后再试';
            return false;
        }
        $enrollinfo = ScoreEnroll::findOne($enrollid);
        if (empty($enrollinfo)) {
            $GLOBALS['errormsg'] = '选手信息有误';
            return false;
        }
        // name itemname score
        $score = '';
        //2,DNF, 3,DNS 4 DQ 犯规
        switch ($enrollinfo->scorestate->isvalued * 1) {
            case 1:
                $score = $enrollinfo->scorestate->score;
                $time = gmstrftime('%M:%S', intval($score / 1000));
                $score = $time . "." . sprintf("%03d", $score % 1000);;
                break;
            case 2:
                $score = 'DNF';
                break;
            case 3:
                $score = 'DNS';
                break;
            case 4:
                $score = 'DQ';
                break;
        }
        $output['cert'] = sprintf(
            $session->cert_template,
            Utils::urlsafe_b64encode($enrollinfo->name),
            Utils::urlsafe_b64encode($enrollinfo->sessionitem->name),
            Utils::urlsafe_b64encode($score)
        );
        return $output;

    }

    //修改选手组别
    public static function changeGroupStates($statesid, $changeitemid, $changegroupnum)
    {

        $ssmodel = ScoreStates::findOne($statesid);
        if (empty($ssmodel)) {
            $GLOBALS['errormsg'] = '选手信息有误';
            return false;
        }
        $itemmodel = MatchSessionItem::findOne($changeitemid);
        if (empty($itemmodel)) {
            $GLOBALS['errormsg'] = '项目信息有误';
            return false;
        }

        $groupmodel = ScoreGroup::findOne(['itemid' => $changeitemid, 'groupnum' => $changegroupnum]);
        if (empty($groupmodel)) {
            $GLOBALS['errormsg'] = '目标分组有误';
            return false;
        }
        $states = ScoreStates::find()->select(['max(lane) as mlane'])->asArray()->andWhere(['itemid' => $changeitemid, 'groupnum' => $changegroupnum])->one();
        $newssmodel = new ScoreStates();
        $newssmodel->lane = $states['mlane'] + 1;
        $newssmodel->matchid = $ssmodel->matchid;
        $newssmodel->itemid = $changeitemid;
        $newssmodel->groupnum = $changegroupnum;
        $newssmodel->enrollid = $ssmodel->enrollid;
        $newssmodel->enrollgender = $ssmodel->enrollgender;
        $newssmodel->enrollname = $ssmodel->enrollname;
        $newssmodel->groupid = $groupmodel->id;
        $newssmodel->isvalued = 1;

        if ($newssmodel->save()) {
            return true;
        } else {
            $GLOBALS['errormsg'] = array_values($newssmodel->getErrorSummary(true));
            return false;
        }


    }

    public static function getGroupscore($matchid) {
        $matchmodel = Match::findOne(['id'=>$matchid]);
        if(empty($matchmodel)) {
            $GLOBALS['errormsg'] = '错误赛事';
            return false;
        }

        $output['title']  = $matchmodel->title;


        $group = ['小学','初中','高中'];
        $score = array();
        foreach ($group as $onegroup) {

            $maleitems = array();
            $malelist = MatchSessionItem::find()->select('id')
                ->andFilterWhere(['matchid'=>$matchid,'gender'=>1])
                ->andFilterWhere(['like','name', $onegroup])->asArray()->all();
            foreach ($malelist as $onelist) {
                $maleitems[] = $onelist['id'];
            }

            $scoremale = ScoreEnroll::find()->select('unit, sum(point) as point')
                ->andFilterWhere(['matchid'=>$matchid])
                ->andFilterWhere(['in','itemid',$maleitems])
                ->groupBy('unit')
                ->orderBy('sum(point) desc')->asArray()->all();
            $output1['title'] = $onegroup.'男子团体';
            $output1['list'] = $scoremale;
            $score[] = $output1;

            $femaleitems = array();
            $femalelist = MatchSessionItem::find()->select('id')
                ->andFilterWhere(['matchid'=>$matchid,'gender'=>1])
                ->andFilterWhere(['like','name', $onegroup])->asArray()->all();
            foreach ($femalelist as $onelist) {
                $femaleitems[] = $onelist['id'];
            }

            $scorefemale = ScoreEnroll::find()->select('unit, sum(point) as point')
                ->andFilterWhere(['matchid'=>$matchid])
                ->andFilterWhere(['in','itemid',$femaleitems])
                ->groupBy('unit')
                ->orderBy('sum(point) desc')->asArray()->all();
            $output2['title'] = $onegroup.'女子团体';
            $output2['list'] = $scorefemale;
            $score[] = $output2;

        }

        $output['score'] = $score;
        return $output;
    }

    public static function calcGroupscore($matchid) {
        $allscore = ScoreStates::find()->andFilterWhere(['matchid'=>$matchid,'isvalued'=>1])
            ->orderBy('itemid asc, score asc')->asArray()->all();

        $itemid = 0; $rank = 0;
        $num = 0;
        for($i=0; $i<count($allscore); $i++)  {
            $thescore = $allscore[$i];
            if($thescore['itemid'] != $itemid) {
                $rank = 1;
                $itemid = $thescore['itemid'];
            } else {
                $rank ++ ;
            }

            $ret = dbScore::setGroupscore($thescore['enrollid'], $rank, $thescore['itemid']);
            if($ret) {
                $num++;
            }
        }

        return ['num'=>$num];

    }



    public static function setPTSAscore($enrollid, $rank, $itemid) {
        $enrollinfo = ScoreEnroll::findOne(['id'=>$enrollid]);
        if(empty($enrollinfo) || empty($enrollinfo->idcard)) {
            $GLOBALS['errormsg'] = '选手信息错误';
            return false;
        }

        $iteminfo = MatchSessionItem::findOne(['id'=>$itemid]);
        if(empty($iteminfo)) {
            $GLOBALS['errormsg'] = '项目错误';
            return false;
        }

        $matchinfo = Match::findOne(['id'=>$iteminfo->matchid]);
        if(empty($matchinfo)) {
            $GLOBALS['errormsg'] = '赛事错误';
            return false;
        }

        $starttime = date('Y-m-d 23:59:59',$matchinfo->start_time);


        $connection     =   Yii::$app->db;
        $transaction    = $connection->beginTransaction();
        try {


            $membermodel = MemberInfo::findOne(['idnumber'=>$enrollinfo->idcard]);
            if(empty($membermodel)) {
                $membermodel = new MemberInfo();
                $membermodel->name = $enrollinfo->name;
                $membermodel->idnumber = $enrollinfo->idcard;

                $ret = Utils::validation_filter_id_card($enrollinfo->idcard);
                if($ret) {
                    $membermodel->idtype = '身份证';
                } else {
                    $prefix = substr($enrollinfo->idcard, 0, 1);
                    if($prefix == 'H' || $prefix == 'M') {
                        $membermodel->idtype = '港澳台通行证';
                    } else {
                        $membermodel->idtype = '护照';
                    }
                }


                $membermodel->score = 0;
                $membermodel->create_time = time();
                $membermodel->avatar = '';
                $membermodel->save();
            }

            if(empty($membermodel->id)) {
                $GLOBALS['errormsg'] = '个人信息卡错误';
                $transaction->rollBack();
                return false;
            }


            $scorerecord = MemberScoreRecord::findOne(['memberid'=>$membermodel->id, 'itemid'=>$itemid]);
            if(empty($scorerecord)) {
                $scorerecord = new MemberScoreRecord();
                $scorerecord->memberid = $membermodel->id;
                $scorerecord->itemid = $itemid;
                $scorerecord->matchid = $iteminfo->matchid;
                $scorerecord->ssid = $iteminfo->ssid;
                $scorerecord->type = 1;
            }

            $scorerecord->create_time = strtotime($starttime);

            switch ($rank) {
                case 1:
                    $scorerecord->value = 9;
                    break;
                case 2:
                    $scorerecord->value = 7;
                    break;
                case 3:
                    $scorerecord->value = 6;
                    break;
                case 4:
                    $scorerecord->value = 5;
                    break;
                case 5:
                    $scorerecord->value = 3;
                    break;
                case 6:
                    $scorerecord->value = 2;
                    break;
                case 7:
                    $scorerecord->value = 1;
                    break;
                default:
                    $scorerecord->value = 0;
                    break;

            }

            $scorerecord->description = $iteminfo->name.":第".$rank."名";
            $scorerecord->status = 1;
            $scorerecord->save();

            $totalscore = MemberScoreRecord::find()
                ->andFilterWhere(['memberid'=>$membermodel->id, 'status'=>1])->sum('value');
            $membermodel->score = $totalscore;
            $membermodel->save();

            $transaction->commit();
        } catch (\Exception $e) {
            $GLOBALS['errormsg'] = '异常错误';
            $transaction->rollBack();
            return false;
        }

        return true;
    }


    public static function setGroupscore($enrollid, $rank, $itemid) {
        $enrollinfo = ScoreEnroll::findOne(['id'=>$enrollid]);
        if(empty($enrollinfo) || empty($enrollinfo->idcard)) {
            $GLOBALS['errormsg'] = '选手信息错误';
            return false;
        }

        $iteminfo = MatchSessionItem::findOne(['id'=>$itemid]);
        if(empty($iteminfo)) {
            $GLOBALS['errormsg'] = '项目错误';
            return false;
        }

        $matchinfo = Match::findOne(['id'=>$iteminfo->matchid]);
        if(empty($matchinfo)) {
            $GLOBALS['errormsg'] = '赛事错误';
            return false;
        }

        $connection     =   Yii::$app->db;
        $transaction    = $connection->beginTransaction();
        try {

            switch ($rank) {
                case 1:
                    $enrollinfo->point = 9;
                    break;
                case 2:
                    $enrollinfo->point = 7;
                    break;
                case 3:
                    $enrollinfo->point = 6;
                    break;
                case 4:
                    $enrollinfo->point = 5;
                    break;
                case 5:
                    $enrollinfo->point = 3;
                    break;
                case 6:
                    $enrollinfo->point = 2;
                    break;
                case 7:
                    $enrollinfo->point = 1;
                    break;
                default:
                    $enrollinfo->point = 0;
                    break;

            }

            if(strstr($iteminfo->name, '接力') != false) {
                $enrollinfo->point = $enrollinfo->point*2;
            }

            $enrollinfo->save();
            $transaction->commit();
        } catch (\Exception $e) {
            $GLOBALS['errormsg'] = '异常错误';
            $transaction->rollBack();
            return false;
        }

        return true;
    }


    static public function Lastgroupscore($matchid) {
        $matchinfo = Match::findOne(['id'=>$matchid]);
        if(empty($matchinfo)) {
            $GLOBALS['errormsg'] = '错误赛事';
            return false;
        }

        $lastgroup = ScoreGroup::find()->andFilterWhere(['matchid'=>$matchid])
            ->andFilterWhere(['>','starttime', 0])
            ->orderBy('starttime desc')->asArray()->all();

        $output['matchname'] = $matchinfo->title;

        if(empty($lastgroup)) {
            return $output;
        }



        $scores = ScoreStates::find()->select('enrollname,lane, enrollgender,score,isvalued')
            ->andFilterWhere(['matchid'=>$matchid, 'groupid'=>$lastgroup[0]['id']])
            ->orderBy([new \yii\db\Expression('case when isvalued=1 then 0 else 1 end'), 'score'=>SORT_ASC])->asArray()->all();
        if(!empty($scores)) {
            $newscore = array();
            $valued = false;
            for($i=0; $i<count($scores); $i++) {
                $onescore = $scores[$i];
                if($onescore['isvalued'] == 1)   {
                    $valued = true;
                }

                $onescore['rank'] = $i+1;
                $newscore[$onescore['lane']-1] = $onescore;
            }

            $count = ScoreGroup::find()->andFilterWhere(['itemid'=>$lastgroup[0]['itemid']])->count();
            $output['score1']['list'] = $newscore;
            $output['score1']['groupnum'] = $lastgroup[0]['groupnum'];
            $output['score1']['grouptotalnum'] = $count;
            $itemmodel = MatchSessionItem::findOne(['id'=>$lastgroup[0]['itemid']]);
            $output['score1']['itemname'] = $itemmodel->name;

        }


        $scores2 = ScoreStates::find()->select('enrollname,lane, enrollgender,score,isvalued')
            ->andFilterWhere(['matchid'=>$matchid, 'groupid'=>$lastgroup[1]['id']])
            ->orderBy([new \yii\db\Expression('case when isvalued=1 then 0 else 1 end'), 'score'=>SORT_ASC])->asArray()->all();
        if(!empty($scores2)) {
            $newscore2 = array();
            $valued = false;
            for($i=0; $i<count($scores2); $i++) {
                $onescore2 = $scores2[$i];
                if($onescore2['isvalued'] == 1)   {
                    $valued = true;
                }

                $onescore2['rank'] = $i+1;
                $newscore2[$onescore2['lane']-1] = $onescore2;
            }

            $count2 = ScoreGroup::find()->andFilterWhere(['itemid'=>$lastgroup[1]['itemid']])->count();
            $output['score2']['list'] = $newscore2;
            $output['score2']['groupnum'] = $lastgroup[1]['groupnum'];
            $output['score2']['grouptotalnum'] = $count2;
            $itemmodel2 = MatchSessionItem::findOne(['id'=>$lastgroup[1]['itemid']]);
            $output['score2']['itemname'] = $itemmodel2->name;

        }



        /*
        foreach ($lastgroup as $group) {
            $scores = ScoreStates::find()->select('enrollname,lane, enrollgender,score,isvalued')
                ->andFilterWhere(['matchid'=>$matchid, 'groupid'=>$group['id']])
                ->orderBy([new \yii\db\Expression('case when isvalued=1 then 0 else 1 end'), 'score'=>SORT_ASC])->asArray()->all();
            if(empty($scores)) {
                continue;
            }

            $newscore = array();
            $valued = false;
            for($i=0; $i<count($scores); $i++) {
                $onescore = $scores[$i];
                if($onescore['isvalued'] == 1)   {
                    $valued = true;
                }

                $onescore['rank'] = $i+1;
                $newscore[$onescore['lane']-1] = $onescore;
            }

            if(empty($valued)) continue;

            $count = ScoreGroup::find()->andFilterWhere(['itemid'=>$group['itemid']])->count();

            $itemmodel = MatchSessionItem::findOne(['id'=>$group['itemid']]);
            $output['itemname'] = $itemmodel->name;
            $output['score'] = $newscore;
            //$output['matchname'] = $matchinfo->title;
            $output['groupnum'] = $group['groupnum'];
            $output['grouptotalnum'] = $count;
            return $output;
        }
        */


        return $output;
    }



    /**
     * 秒表成绩上传
     */
    public static function uploadData($ssid, $lane, $timestamp, $end_time, $start_time = '', $uuid = '')
    {

        $session = MatchSession::findOne($ssid);
        if (empty($session)) {
            $GLOBALS['errormsg'] = '错误分组';
            return false;
        }
        if (is_numeric($lane) && $lane > 0 && $lane <= $session->lane) {

            $requestData = ['ssid' => $ssid, 'lane' => $lane, 'time' => $timestamp, 'end_time' => $end_time, 'start_time' => $start_time, 'uuid' => $uuid];
            $timecache = ScoreStartcache::findOne(['ssid' => $ssid]);
            if (!empty($timecache)) {
                $requestData['matchid'] = $timecache->matchid;
                $requestData['itemid'] = $timecache->itemid;
                $requestData['groupnum'] = $timecache->groupnum;
            } else {
                //错误数据或者 总裁忘记按时间了
                $requestData['itemid'] = 0;
                $requestData['groupnum'] = 0;
            }
            $rawmodel = new TimingWatchRawdata();
            $rawmodel->load($requestData, '');
            if (!$rawmodel->save()) {
                $GLOBALS['errormsg'] = implode(',', $rawmodel->getErrorSummary(true));
                return false;
            }
            //同步模式  直接计算
//            if (!$async)  self::calc($ssid,2);
            return true;

        } else {
            $GLOBALS['errormsg'] = '错误的泳道';
            return false;
        }


    }

}