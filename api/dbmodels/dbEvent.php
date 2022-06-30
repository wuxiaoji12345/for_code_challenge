<?php
/**
 * Created by wayne.
 * Date: 2019/4/10
 * Time: 12:02 PM
 */

namespace api\dbmodels;

use api\models\ScoreEnroll;
use common\helpers\Utils;
use common\models\Event;
use common\models\EventGroup;
use common\models\EventInfo;
use common\models\EventMembers;
use common\models\EventRelation;
use common\models\EventType;
use common\models\UserInfo;
use Yii;
use api\models\Banners;
use api\models\Match;
use api\models\MatchSession;
use api\models\RegisterDetail;
use api\models\RegisterInfo;
use api\models\RegisterRelation;
use api\models\RegisterType;
use phpDocumentor\Reflection\Types\Null_;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class dbEvent
{

    public static function getEventInfo($rgid, $typeid)
    {

        $typeinfo = EventType::findOne(['id' => $typeid]);
        if (empty($typeinfo)) {
            $GLOBALS['errormsg'] = '错误赛事组别';
            return false;
        }

        $matchinfo = Event::findOne(['id' => $typeinfo->matchid, 'status' => 1]);
        if (empty($matchinfo)) {
            $GLOBALS['errormsg'] = '错误赛事';
            return false;
        }

        $data = [];
        $data['categoryid'] = $matchinfo->category_id;
        $data['matchid']    = $typeinfo->matchid;
        $data['typeid']     = $typeinfo->id;
        $data['fees']       = $typeinfo->fees;
        $data['notice']     = $typeinfo->notice;
        $data['typename']   = $typeinfo->title;
        $data['title']   = $matchinfo->title;
        $data['starttime']   = $matchinfo->start_time;
        $data['endtime']     = $matchinfo->end_time;
        $data['imgurl']      = $matchinfo->imgurl;
        $data['icon']      = $matchinfo->icon;
        $data['mincount']    = $typeinfo->mincount;
        $data['maxcount']    = $typeinfo->maxcount;
        $data['fmincount']   = $typeinfo->fmincount;
        $data['fmaxcount']   = $typeinfo->fmaxcount;
        $data['needcheck']   = $typeinfo->needcheck;
        $data['type']      = $typeinfo->type;

        $data['disclaimer']   = 'https://' . $_SERVER['HTTP_HOST'] . '/event/eventintro?eventid=' . $typeinfo->matchid . '&type=1';


        $data['address']  = $matchinfo->address;

        //团队报名修改截止时间,赛前N天
        if ($data['type'] == MATCH_TYPE_TEAM) $data['submitendtime'] = $matchinfo->end_time - intval($typeinfo->registerend) * 86400;

        $data['hascode'] = 0;


        $rginfo = EventGroup::find()->select('groupcode')
            ->andFilterWhere(['id' => $rgid, 'typeid' => $typeid])->one();
        if ($rginfo)
            $data['groupcode']      = $rginfo->groupcode;

        return $data;
    }

    public static function getGidEvent($gid, $categoryid,  $page,$keywords=null){

        $query = Event::find()
            ->select('id, title, start_time, end_time, address, imgurl,reg_start_time,reg_end_time')
            ->andFilterWhere(['status'=>1,'publish'=>1]);

        if(!empty($categoryid)) {
            $query->andFilterWhere(['category_id'=>$categoryid]);
        }
        $query->andFilterWhere(['like','title',$keywords]);
        $total = $query->count();
        $limit = 20;
        $pages = ceil($total/$limit);
        $offset = ($page - 1)*$limit;

        $timenow = time();
        $orderby = [new \yii\db\Expression("case when reg_end_time>=$timenow then 1 else 0 end desc"), new \yii\db\Expression("case when reg_end_time>=$timenow then start_time end asc, case when reg_end_time<$timenow then start_time end desc")];

        $list = $query->orderBy($orderby)
            ->offset($offset)->limit($limit)->all();
        $data['total'] = $total;
        $data['page'] = $page;
        $data['pages'] = $pages;
        $data['list'] = $list;

        return $data;
    }

    public static function getTimingEvent($gid, $categoryid,  $page){

        $query = Event::find()
            ->select('id, title, start_time, end_time, address, imgurl,reg_start_time,reg_end_time')
            ->andFilterWhere(['status'=>1,'publish'=>1])
            ->andFilterWhere(['>','end_time',time()]);


        if(!empty($categoryid)) {
            $query->andFilterWhere(['category_id'=>$categoryid]);
        }
        $total = $query->count();

        $limit = 20;
        $pages = ceil($total/$limit);
        $offset = ($page - 1)*$limit;

        $timenow = time();
        $orderby = [new \yii\db\Expression("case when reg_end_time>=$timenow then 1 else 0 end desc"), new \yii\db\Expression("case when reg_end_time>=$timenow then start_time end asc, case when reg_end_time<$timenow then start_time end desc")];


        $list = $query->orderBy($orderby)
            ->offset($offset)->limit($limit)->asArray()->all();

        $data['total'] = $total;
        $data['page'] = $page;
        $data['pages'] = $pages;
        $data['list'] = $list;

        return $data;
    }

    public static function getEventTypeList($matchid, $gid){

        $matchinfo = Event::findOne(['id'=>$matchid, 'status'=>1]);
        if(empty($matchinfo)) {
            $GLOBALS['errormsg'] = '错误赛事id';
            return false;
        }

        $typelist = EventType::find()
            ->select('matchid, id as typeid,fees,title,amount,type,mincount,maxcount,needcheck')
            ->andFilterWhere(['matchid'=>$matchid])
            ->orderBy ('weight desc')->asArray()->all();

        $output['list'] = $typelist;
        return $output;

    }


    public static function getMyEventListPages($urid, $page=1, $app = 0){

        $limit = 20;


        $query =  EventRelation::find()->from (EventRelation::tableName(). ' as a')
            ->joinWith ('match as b', true)
            ->joinWith('info as c', true)
            ->andFilterWhere(['>','a.state',0])
            //->andWhere (['or','a.type!=3 and a.state=1','a.type=3'])
            ->andFilterWhere(['a.urid'=>$urid]);

        if(!empty($app)) {
            $query->andFilterWhere(['a.app'=>$app]);
        }

        $totalnum =  $query->count();
        $pages = ceil($totalnum / $limit);
        $offset = ($page -1)*$limit;
        $allinfo =$query->orderBy('a.id desc')->offset($offset)->limit($limit)->asArray()->all();
        $list = [];

        foreach ($allinfo as $one) {

            unset($tmp);
            if($one['match']['status'] == 1){
                if($one['state'] == EventRelation::PAY_RETURN){
                    $tmp['state'] = $one['state'];
                    $tmp['statetips'] = '已退款';
                }elseif($one['state'] == EventRelation::PAY_YES){
                    $tmp['state'] = $one['state'];
                    $tmp['statetips'] = '报名成功';
                }elseif($one['state'] == EventRelation::PAY_NO){
                    $tmp['state'] = $one['state'];
                    $tmp['statetips'] = '报名成功';
                }
            } else {
                $tmp['state'] = 99;
                $tmp['statetips'] = '比赛结束';
            }
            $tmp['rrid'] = $one['id'];
            $tmp['matchid'] = $one['matchid'];
            $tmp['typeid'] = $one['typeid'];
            $tmp['typename'] = $one['typename'];
            $tmp['address'] = $one['match']['city'].$one['match']['address'];
            $tmp['title'] = $one['match']['title'];
            $tmp['ischeck'] = $one['ischeck'];
            $tmp['endtime'] = $one['match']['end_time'];
            $tmp['starttime'] = $one['match']['start_time'];
            $tmp['imgurl'] = $one['match']['imgurl'];

            $info = [];
            if(!empty($one['info'])) {
                foreach ($one['info'] as $oneinfo) {
                    unset($tmpinfo);
                    $tmpinfo['name']  = $oneinfo['name'];
                    $tmpinfo['sex'] = $oneinfo['sex'];
                    $info[] = $tmpinfo;
                }
            }

            $tmp['infos'] = $info;

            $list[] = $tmp;

        }

        $data['total'] = $totalnum;
        $data['page'] = $page;
        $data['pages'] = $pages;
        $data['list'] = $list;

        return $data;

    }


    public static function getPreEventMemberList($urid,$rrid,$typeid){

        $prememberlist = EventInfo::find()->andFilterWhere(['rrid'=>$rrid,'typeid'=>$typeid])
            ->orderBy('state asc')->all();

        $list  = [];
        if($prememberlist)  {
            foreach($prememberlist as $k=>$v){
                $list[$k]['riid']   = $v->id;
                $list[$k]['typeid'] = $typeid;
                $list[$k]['rrid']   = $rrid;
                $list[$k]['memberid'] = $v->memberid;
                $list[$k]['state']      = $v->state;
                $list[$k]['attrs'] = json_decode ($v->registerinfos);
            }

        }
        return $list ? ['list'=>$list]:[];
    }

    public static function getOrderDetail($urid, $rrid) {
        $order = EventRelation::find()->select('matchid, order_no, typename, rgid, state, type, paytype, fees, paytime, ischeck')
        ->andFilterWhere(['id' => $rrid, 'urid' => $urid])->asArray()->one();
        if (empty($order)) {
            $GLOBALS['errormsg'] = '错误订单';
            return false;
        }

        $matchinfo = Event::find()->select('title, start_time, end_time, province, city, district, address,imgurl')
            ->andFilterWhere(['id' => $order['matchid']])->asArray()->one();
        if (empty($matchinfo)) {
            $GLOBALS['errormsg'] = '错误赛事信息';
            return false;
        }

        $output['matchinfo'] = $matchinfo;

        $output['order'] = $order;

        if ($order['type'] == 3 && !empty($order['rgid'])) {
            $groupinfo = EventGroup::find()->select('regname, groupcode, unit,leader,mobile,groupinfos')
                ->andFilterWhere(['id' => $order['rgid'], 'state' => 1])->asArray()->one();
            if (!empty($groupinfo)) {
                $output['groupinfo'] = $groupinfo;
            }
        }

        $allinfo = EventInfo::find()->select('name,mobile,sex,idtype,idnumber,birth,registerinfos')
            ->andFilterWhere(['rrid' => $rrid])
            ->asArray()->all();

        if (empty($allinfo)) {
            $output['users'] = [];
        } else {
            $output['users'] = $allinfo;
        }

        return $output;
    }


    public static function getEventTypeAttrTmpls($typeid){

        $typeinfo = EventType::find()->select('registerform')->andFilterWhere(['id'=>$typeid])->one();

        if($typeinfo){
            if(empty($typeinfo->registerform)) {
                $GLOBALS['errormsg'] = '报名表单错误';
                return false;
            }
            $list = json_decode($typeinfo->registerform,true);
            if(empty($list)) {
                $GLOBALS['errormsg'] = '报名表单格式错误';
                return false;
            }

        } else {
            $GLOBALS['errormsg'] = '错误类型';
            return false;
        }

        return $list ? ['list'=>$list]:[];

    }

    public static function eventState($input){
        if($input){
            $hasregisters   = EventRelation::find()->andFilterWhere(['matchid'=>$input['matchid'],'state'=>1])->count();
            $totalregisters = EventType::find()->andFilterWhere(['matchid'=>$input['matchid']])->sum('amount');

            if(time()<$input['reg_start_time']){
                return ['state' => REG_WAIT,'statetips'=>'报名未开始'];
            }elseif(time() >$input['end_time']){
                return ['state' => MATCH_END,'statetips'=>'比赛结束'];
            }elseif($hasregisters>=$totalregisters){
                return ['state' => REG_OVER,'statetips'=>'名额已满'];
            }elseif(time()>$input['reg_end_time']){
                return ['state' => REG_END,'statetips'=>'报名截止'];
            }
            return ['state' => REG_PRO,'statetips'=>'立即报名'];
        }

        return ['state' => REG_END,'statetips'=>'报名截止'];
    }


    public static function getEventDetail($matchid, $gid, $urid=null){

        $matchinfo = Event::find()
            ->select('id as matchid, category_id,title,reg_start_time, reg_end_time, start_time, end_time, tips,icon,imgurl,province,city,district,address, longitude, latitude')
            ->andFilterWhere(['id'=>$matchid, 'status'=>1])->asArray()->one();
        if(empty($matchinfo)) {
            $GLOBALS['errormsg'] = '错误赛事';
            return false;
        }

        $matchstate = self::eventState($matchinfo);
        $matchinfo['state'] = $matchstate['state'];
        $matchinfo['statetips'] = $matchstate['statetips'];

        $matchinfo['intro']= 'https://'.$_SERVER['HTTP_HOST'].'/event/eventintro?eventid='.$matchid;

        return $matchinfo;
    }

    private static function submitState($matchinfo, $typeinfo, $relationmodel = NULL)
    {
        if ($matchinfo) {
            $hasregisters   = EventRelation::find()
                ->andFilterWhere(['matchid' => $matchinfo->id, 'state' => 1])
                ->count();
                
            $totalregisters = EventType::find()->andFilterWhere(['matchid' => $matchinfo->id, 'status' => 1])->sum('amount');
            if (time() > $matchinfo->end_time - intval($typeinfo->registerend) * 86400) {
                return ['state' => REG_END, 'statetips' => '报名截止'];
            } elseif (time() > $matchinfo->end_time) {
                return ['state' => MATCH_END, 'statetips' => '比赛结束'];
            } elseif ($hasregisters >= $totalregisters && $relationmodel->state != 1) {
                return ['state' => REG_OVER, 'statetips' => '报名组名额已满' . $hasregisters];
            }
            return true;
        }

        return ['state' => MATCH_END, 'statetips' => '比赛结束'];
    }

    public static function hasRegister($matchid, $rgid, $idnumber)
    {

        $ret = EventInfo::find()->from(EventInfo::tableName() . ' as a')
            ->JoinWith('registerRelation as b', false)
            ->andFilterWhere(['a.matchid' => $matchid, 'a.idnumber' => $idnumber, 'a.state' => 1, 'b.state' => 1])
            ->andFilterWhere(['<>', 'a.rgid', $rgid])->one();

        if (empty($ret)) return false;
        return true;
    }

    public static function addEventMemberInfos($urid, $categoryid, $matchid, $gid, $rgid, $typeid, $rrid, $riids)
    {

        $userinfo = UserInfo::findOne(['urid' => $urid]);
        if (empty($userinfo)) {
            $GLOBALS['errormsg'] = '错误用户';
            return false;
        }

        $matchinfo = Event::findOne(['id' => $matchid, 'status' => 1]);
        if (empty($matchinfo)) {
            $GLOBALS['errormsg'] = '错误赛事id';
            return false;
        }

        $typeinfo = EventType::findOne(['id' => $typeid, 'matchid' => $matchid]);
        if (empty($typeinfo)) {
            $GLOBALS['errormsg'] = '错误赛事组别';
            return false;
        }

        $relationmodel = EventRelation::findOne(['id' => $rrid, 'urid' => $urid]);
        if (empty($relationmodel)) {
            $GLOBALS['errormsg'] = '报名信息错误';
            return false;
        }

        $matchState  = self::submitState($matchinfo, $typeinfo, $relationmodel);
        if (is_array($matchState) && $matchState['state'] != REG_PRO) {
            $GLOBALS['errormsg'] = $matchState['statetips'];
            return false;
        }

        $rridattr = json_decode($riids, true);
        if (empty($rridattr)) {
            $GLOBALS['errormsg'] = '没有参赛选手,提交失败!';
            return false;
        }

        $prememberlist =  EventInfo::find()->andFilterWhere(['in', 'id', $rridattr])->asArray()->all();

        if (empty($prememberlist)) {
            $GLOBALS['errormsg'] = '没有参赛选手,提交失败2!';
            return false;
        }

        $female = 0;
        $male   = 0;
        $foreigner = 0;


        if ($prememberlist) {
            foreach ($prememberlist as $k => $v) {

                if (!empty($typeinfo->agemax) || !empty($typeinfo->agemin)) {
                    if (empty($v['birth'])) {
                        $GLOBALS['errormsg'] = '请填写出生年月';
                        return false;
                    }

                    $birthtime = strtotime($v['birth']);
                    if (!empty($typeinfo->agemin))
                        $agemintime = strtotime($typeinfo->agemin);
                    else
                        $agemintime = strtotime('1800-01-01');

                    if (!empty($typeinfo->agemax)) {
                        $agemaxtime = strtotime($typeinfo->agemax);
                    } else {
                        $agemaxtime = strtotime('3000-01-01');
                    }
                    if ($birthtime < $agemintime || $birthtime > $agemaxtime) {
                        $GLOBALS['errormsg'] = '参赛选手年龄不符合所选组别年龄';
                        return false;
                    }
                }


                if ($v['sex'] == '女') {
                    $female++;
                } else {
                    $male++;
                }

                if ($v['nation'] == '外籍') {
                    $foreigner++;
                }

                //根据证件号码判断是否已报名
                if ($matchinfo->islimit == 1) {
                    if ($v['idtype'] && $v['idnumber']) {
                        if (self::hasRegister($matchid, $rgid, $v['idnumber'])) {
                            $GLOBALS['errormsg'] = "错误:选手:{$v['name']},证件号码:{$v['idnumber']},已经报名了本次活动,请确认后再提交!";
                            return false;
                        }
                    }
                }
            }
        }
        if ($female > $typeinfo->fmaxcount || $female < $typeinfo->fmincount) {

            if ($typeinfo->fmaxcount == 0 && $typeinfo->fmincount == 0) {
                $GLOBALS['errormsg'] = "错误:当前报名只限男性选手。报名名单中存在女性1!";
            } else {
                $GLOBALS['errormsg'] = "错误:要求女性选手:{$typeinfo->fmincount}-{$typeinfo->fmaxcount}名,提交名单中只有女性选手{$female}名!";
            }
            return false;
        }

        $connection = Yii::$app->db;
        $trans = $connection->beginTransaction();

        try {
            if ($prememberlist) {
                //dbMatchRegister::updateDbAllRegisterInfo(['state'=>REGISTER_NO],['matchid'=>$matchid,'typeid'=>$typeid,'rgid'=>$rgid]);
                EventInfo::updateAll(array('state' => 2), ['matchid' => $matchid, 'typeid' => $typeid, 'rgid' => $rgid]);
                foreach ($prememberlist as $k => $v) {
                    $ids[] = $v['id'];
                    $usercode[] = $typeid . '-' . $v['id'];
                }
                $res = self::batchUpdateRegisterInfosState($usercode, $ids);
            }

            if ($relationmodel->state == EventRelation::PAY_NOVALID) {
                $relationmodel->state = EventRelation::PAY_NO;
                $relationmodel->save();
            }
            $trans->commit();
        } catch (\Exception $e) {

            $trans->rollBack();
            $GLOBALS['errormsg'] = $e;
            return false;
        }

        return true;
    }

    public static function batchUpdateRegisterInfosState($usercode, $ids)
    {

        $data = '';
        $count = count($ids) - 1;
        foreach ($ids as $k => $v) {
            $data .= "($v,1,'{$usercode[$k]}')";
            if ($k < $count) $data .= ",";
        }

        $sql = "INSERT INTO swim_event_info (id,state,usercode) VALUES $data "
            . "ON DUPLICATE KEY UPDATE state=VALUES(state) , usercode=VALUES(usercode) ";

        $connection = Yii::$app->db;
        return $connection->createCommand($sql)->execute();
    }


    private static function saveMember($urid, $gid, $data, $memberid = null)
    {

        $caldata = ArrayHelper::map($data, 'key_name', 'value');

        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {

            if (!empty($memberid)) {
                $membermodel = EventMembers::findOne(['id' => $memberid, 'status' => 1]);
            }

            if (empty($membermodel)) {

                $idtype = empty($caldata['mv_idtype']) ? '' : $caldata['mv_idtype'];
                $idnumber = empty($caldata['mv_idnumber']) ? '' : $caldata['mv_idnumber'];
                if (!empty($idtype) && !empty($idnumber)) {
                    $membermodel = EventMembers::findOne(['urid' => $urid, 'idtype' => $idtype, 'idnumber' => $idnumber, 'status' => 1]);
                }

                if (empty($membermodel)) {
                    $membermodel = new EventMembers();
                    $membermodel->urid = $urid;
                }
            }

            $membermodel->name = empty($caldata['mv_name']) ? '' : $caldata['mv_name'];

            $membermodel->idtype = empty($caldata['mv_idtype']) ? '' : $caldata['mv_idtype'];
            $membermodel->idnumber = empty($caldata['mv_idnumber']) ? '' : $caldata['mv_idnumber'];
            $membermodel->mobile = empty($caldata['mv_mobile']) ? '' : $caldata['mv_mobile'];
            $membermodel->nation = empty($caldata['mv_nation']) ? '' : $caldata['mv_nation'];
            $membermodel->size = empty($caldata['mv_size']) ? '' : $caldata['mv_size'];
            $membermodel->avatar = empty($caldata['mv_avatar']) ? '' : $caldata['mv_avatar'];
            if (empty($membermodel->avatar)) {
                $membermodel->avatar = empty($caldata['mv_avatar2']) ? '' : $caldata['mv_avatar2'];
            }

            if (isset($membermodel->idtype) && $membermodel->idtype == '身份证') {

                $idinfo =  Utils::getIdNumberInfo($membermodel->idnumber);
                $membermodel->birth = empty($idinfo['birth']) ? '' : $idinfo['birth'];
                if (!empty($idinfo['sex']) && $idinfo['sex'] == 2) {
                    $membermodel->sex = '女';
                } else {
                    $membermodel->sex = '男';
                }
            } else {
                $membermodel->birth = empty($caldata['mv_birth']) ? '' : $caldata['mv_birth'];
                $membermodel->sex = empty($caldata['mv_sex']) ? '' : $caldata['mv_sex'];
            }

            $membermodel->memberinfos = json_encode($data);
            $membermodel->urid = $urid;
            $membermodel->gid = $gid;
            $membermodel->save();


            $transaction->commit();
        } catch (\Exception $ex) {
            $transaction->rollBack();


            $GLOBALS['errormsg'] = '保存用户信息卡错误';
            return false;
        }


        return $membermodel->id;
    }

    public static function addPreMembers($urid, $matchid, $gid, $rgid, $typeid, $rrid, $json)
    {

        $userinfo = UserInfo::findOne(['urid' => $urid]);
        if (empty($userinfo)) {
            $GLOBALS['errormsg'] = '错误用户';
            return false;
        }

        $matchinfo = Event::findOne(['id' => $matchid, 'status' => 1]);
        if (empty($matchinfo)) {
            $GLOBALS['errormsg'] = '错误赛事id';
            return false;
        }

        $typeinfo = EventType::findOne(['id' => $typeid, 'matchid' => $matchid]);
        if (empty($typeinfo)) {
            $GLOBALS['errormsg'] = '错误赛事组别';
            return false;
        }

        $relationmodel = EventRelation::findOne(['id' => $rrid, 'urid' => $urid]);
        if (empty($relationmodel)) {
            $GLOBALS['errormsg'] = '报名信息错误';
            return false;
        }

        $data = json_decode($json, true);
        if (empty($data)) {
            $GLOBALS['errormsg'] = '信息格式错误';
            return false;
        }


        if (time() > $matchinfo->end_time - intval($typeinfo->registerend) * 86400) {
            $GLOBALS['errormsg'] = '修改时间已结束';
            return false;
        }

        $connection     =   Yii::$app->db;
        $transaction    = $connection->beginTransaction();
        try {

            EventInfo::deleteAll(['typeid' => $typeid, 'rgid' => $rgid, 'rrid' => $rrid]);

            foreach ($data as $k => $v) {

                $caldata = ArrayHelper::map($v, 'key_name', 'value');
                //  检查身份证,性别是否正确
                if (!empty($caldata['mv_idtype']) && !empty($caldata['mv_idnumber']) && $caldata['mv_idtype'] == '身份证') {

                    $ret = Utils::validation_filter_id_card($caldata['mv_idnumber']);
                    if (empty($ret)) {
                        $transaction->rollBack();
                        $GLOBALS['errormsg'] = $caldata['mv_name'] . ':身份证格式错误';
                        return false;
                    }
                } else if (!empty($caldata['mv_idtype'])) {
                    if (empty($caldata['mv_idnumber'])) {
                        $transaction->rollBack();
                        $GLOBALS['errormsg'] = $caldata['mv_idtype'] . '不能为空';
                        return false;
                    }
                }
                // mv_name  mv_emergency_person  //  mv_mobile mv_emergency_person_phone
                //紧急联系人 姓名 电话
                if(!empty($caldata['mv_name'])&&
                    !empty($caldata['mv_emergency_person'])&&
                    $caldata['mv_name']==$caldata['mv_emergency_person']
                ){
                    $transaction->rollBack();
                    $GLOBALS['errormsg'] = '选手姓名和紧急联系人姓名不能重复';
                    return false;
                }
                // mv_name  mv_emergency_person  //  mv_mobile mv_emergency_person_phone
                //紧急联系人 姓名 电话
                if(!empty($caldata['mv_mobile'])&&
                    !empty($caldata['mv_emergency_person_phone'])&&
                    $caldata['mv_mobile']==$caldata['mv_emergency_person_phone']
                ){
                    $transaction->rollBack();
                    $GLOBALS['errormsg'] = '选手手机号和紧急联系人电话不能重复';
                    return false;
                }


                //先保存到成员表
                $memberid = self::saveMember($urid, $gid, $v);
                if (empty($memberid)) {
                    $transaction->rollBack();
                    $GLOBALS['errormsg'] = '保存信息错误1';
                    return false;
                }

                $newinfo = EventInfo::findOne(['rgid' => $rgid, 'typeid' => $typeid, 'memberid' => $memberid]);
                if (empty($newinfo)) {
                    $newinfo = new EventInfo();
                    $newinfo->create_time = time();
                }

                $newinfo->name = empty($caldata['mv_name']) ? '' : $caldata['mv_name'];
                $newinfo->idtype = empty($caldata['mv_idtype']) ? '' : $caldata['mv_idtype'];
                $newinfo->idnumber = empty($caldata['mv_idnumber']) ? '' : $caldata['mv_idnumber'];
                $newinfo->mobile = empty($caldata['mv_mobile']) ? '' : $caldata['mv_mobile'];
                $newinfo->nation = empty($caldata['mv_nation']) ? '' : $caldata['mv_nation'];
                $newinfo->size = empty($caldata['mv_size']) ? '' : $caldata['mv_size'];
                $newinfo->avatar = empty($caldata['mv_avatar']) ? '' : $caldata['mv_avatar'];
                if (empty($newinfo->avatar)) {
                    $newinfo->avatar = empty($caldata['mv_avatar2']) ? '' : $caldata['mv_avatar2'];
                }

                if (isset($newinfo->idtype) && $newinfo->idtype == '身份证') {

                    $idinfo =  Utils::getIdNumberInfo($newinfo->idnumber);
                    $newinfo->birth = empty($idinfo['birth']) ? '' : $idinfo['birth'];
                    if (!empty($idinfo['sex']) && $idinfo['sex'] == 2) {
                        $newinfo->sex = '女';
                    } else {
                        $newinfo->sex = '男';
                    }

                    if (!empty($caldata['mv_sex'])) {
                        if ($caldata['mv_sex'] != $newinfo->sex) {
                            $GLOBALS['errormsg'] = '身份证性别与所填性别不一致';
                            $transaction->rollBack();
                            return false;
                        }
                    }

                    if (!empty($caldata['mv_birth']) && !empty($newinfo->birth)) {
                        $birthtime2 = strtotime($caldata['mv_birth']);
                        $birthtime1 = strtotime($newinfo->birth);
                        if ($birthtime2 != $birthtime1) {
                            $GLOBALS['errormsg'] = '身份证生日与所填生日不一致';
                            $transaction->rollBack();
                            return false;
                        }
                    }
                } else {
                    $newinfo->birth = empty($caldata['mv_birth']) ? '' : $caldata['mv_birth'];
                    $newinfo->sex = empty($caldata['mv_sex']) ? '' : $caldata['mv_sex'];
                }

                $newinfo->registerinfos = json_encode($v);
                $newinfo->matchid = $matchid;
                $newinfo->rgid = $rgid;
                $newinfo->typeid = $typeid;
                $newinfo->rrid = $rrid;
                $newinfo->memberid = (int)$memberid;
                $newinfo->save();
            }

            $riids = EventInfo::find()->select('id')
                ->andFilterWhere(['rgid' => $rgid, 'typeid' => $typeid])->asArray()->all();

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $GLOBALS['errormsg'] = $e->getMessage();
            return false;
        }



        if ($riids) {
            return ['riids' => json_encode(ArrayHelper::getColumn($riids, 'id'))];
        }
        return false;
    }


    public static function addTeamPreMembers($urid, $gid, $matchid, $rgid, $typeid, $rrid, $json, $riid = null)
    {

        $userinfo = UserInfo::findOne(['urid' => $urid]);
        if (empty($userinfo)) {
            $GLOBALS['errormsg'] = '错误用户';
            return false;
        }

        $matchinfo = Event::findOne(['id' => $matchid, 'status' => 1]);
        if (empty($matchinfo)) {
            $GLOBALS['errormsg'] = '错误赛事id';
            return false;
        }

        $typeinfo = EventType::findOne(['id' => $typeid, 'matchid' => $matchid]);
        if (empty($typeinfo)) {
            $GLOBALS['errormsg'] = '错误赛事组别';
            return false;
        }
        $relationmodel = EventRelation::findOne(['id' => $rrid, 'state' => [EventRelation::PAY_YES, EventRelation::PAY_NO]]);
        if (empty($relationmodel)) {
            $GLOBALS['errormsg'] = '报名信息错误';
            return false;
        }

        $data = json_decode($json, true);
        if (empty($data)) {
            $GLOBALS['errormsg'] = '信息格式错误';
            return false;
        }

        if (time() > $matchinfo->end_time - intval($typeinfo->registerend) * 86400) {
            $GLOBALS['errormsg'] = '修改时间已结束';
            return false;
        }

        $connection     =   Yii::$app->db;
        $transaction    = $connection->beginTransaction();
        try {

            foreach ($data as $k => $v) {
                if (empty($v)) {
                    continue;
                }

                $caldata = ArrayHelper::map($v, 'key_name', 'value');
                if (!empty($caldata['mv_idtype']) && !empty($caldata['mv_idnumber']) && $caldata['mv_idtype'] == '身份证') {

                    $ret = Utils::validation_filter_id_card($caldata['mv_idnumber']);
                    if (empty($ret)) {
                        $GLOBALS['errormsg'] = $caldata['mv_name'] . ':身份证格式错误';
                        return false;
                    }
                }


                // mv_name  mv_emergency_person  //  mv_mobile mv_emergency_person_phone
                //紧急联系人 姓名 电话
                if(!empty($caldata['mv_name'])&&
                    !empty($caldata['mv_emergency_person'])&&
                    $caldata['mv_name']==$caldata['mv_emergency_person']
                ){
                    $transaction->rollBack();
                    $GLOBALS['errormsg'] = '选手姓名和紧急联系人姓名不能重复';
                    return false;
                }
                // mv_name  mv_emergency_person  //  mv_mobile mv_emergency_person_phone
                //紧急联系人 姓名 电话
                if(!empty($caldata['mv_mobile'])&&
                    !empty($caldata['mv_emergency_person_phone'])&&
                    $caldata['mv_mobile']==$caldata['mv_emergency_person_phone']
                ){
                    $transaction->rollBack();
                    $GLOBALS['errormsg'] = '选手手机号和紧急联系人电话不能重复';
                    return false;
                }


                //先保存到成员表
                $memberid = self::saveMember($urid, $gid, $v);
                if (empty($memberid)) {
                    $GLOBALS['errormsg'] = '保存信息错误';
                    return false;
                }

                $idtype = empty($caldata['mv_idtype']) ? '' : $caldata['mv_idtype'];
                $idnumber = empty($caldata['mv_idnumber']) ? '' : $caldata['mv_idnumber'];
                $sex = empty($caldata['mv_sex']) ? '' : $caldata['mv_sex'];
                $birth = empty($caldata['mv_birth']) ? '' : $caldata['mv_birth'];

                if (isset($idtype) && $idtype == '身份证') {
                    $idinfo = Utils::getIdNumberInfo($idnumber);
                    $sex = $idinfo['sex'] == 1 ? '男' : '女';
                    //判断格式
                    if (!empty($caldata['mv_sex'])) {
                        if ($caldata['mv_sex'] != $sex) {
                            $GLOBALS['errormsg'] = '身份证性别与所填性别不一致';
                            $transaction->rollBack();
                            return false;
                        }
                    }

                    if (!empty($caldata['mv_birth']) && !empty($idinfo['birth'])) {

                        $birthtime2 = strtotime($caldata['mv_birth']);
                        $birthtime1 = strtotime($idinfo['birth']);

                        if ($birthtime2 != $birthtime1) {
                            $GLOBALS['errormsg'] = '身份证生日与所填生日不一致';
                            $transaction->rollBack();
                            return false;
                        }


                        if (!empty($typeinfo->agemin))
                            $agemintime = strtotime($typeinfo->agemin);
                        else
                            $agemintime = strtotime('1800-01-01');

                        if (!empty($typeinfo->agemax)) {
                            $agemaxtime = strtotime($typeinfo->agemax);
                        } else {
                            $agemaxtime = strtotime('3000-01-01');
                        }

                        if ($birthtime1 < $agemintime || $birthtime1 > $agemaxtime) {
                            $GLOBALS['errormsg'] = '参赛选手年龄不符合所选组别年龄';
                            return false;
                        }
                    }


                    $birth = empty($idinfo['birth']) ? '' : $idinfo['birth'];
                    if (!empty($idinfo['sex']) && $idinfo['sex'] == 2) {
                        $sex = '女';
                        //性别判断
                        if ($typeinfo->fmaxcount == 0) {
                            $GLOBALS['errormsg'] = "错误:当前报名只限男性选手。报名名单中存在女性!";
                            return false;
                        }
                    }

                    // elseif(!empty($idinfo['sex']) && $idinfo['sex'] == 1) {
                    //     //判断团队男
                    //     if($typeinfo->type==3 && $typeinfo->maxcount == $typeinfo->fmaxcount){
                    //         $GLOBALS['errormsg'] = "错误:当前报名只限男性选手。报名名单中存在女性3!";
                    //         return false;
                    //     }

                    // }
                }




                if (empty($riid)) {
                    if ($matchinfo->islimit == 1) {
                        if (!empty($idtype) && !empty($idnumber)) {
                            $newinfo = EventInfo::find()
                                ->joinWith(['registerRelation' => function ($query) {
                                    $query->andWhere([EventRelation::tableName() . '.state' => 1]);
                                    return $query;
                                }])
                                ->andWhere([
                                    EventInfo::tableName() . '.matchid' => $matchid,
                                    EventInfo::tableName() . '.idtype' => $idtype,
                                    EventInfo::tableName() . '.idnumber' => $idnumber
                                ])
                                ->one();
                            if (!empty($newinfo)) {

                                $GLOBALS['errormsg'] = "错误:选手:{$caldata['mv_name']},证件号码:{$caldata['mv_idnumber']},已经报名了本次活动,请确认后再提交!";
                                return false;
                            }
                        }
                    }


                    $newinfo = new EventInfo();
                } else {
                    // 修改信息，需要进行额外判断，是否可以修改
                    $couldmodify = self::couldModify($riid, $typeinfo, $sex);
                    if (empty($couldmodify)) {
                        $GLOBALS['errormsg'] = "错误:要求女性选手:{$typeinfo->fmincount}-{$typeinfo->fmaxcount}名,修改后不符合;";
                        return false;
                    }

                    if ($matchinfo->islimit == 1) {
                        if (!empty($idtype) && !empty($idnumber)) {
                            $newinfo = EventInfo::find()
                                ->andFilterWhere(['matchid' => $matchid, 'idtype' => $idtype, 'idnumber' => $idnumber])
                                ->andFilterWhere(['!=', 'id', $riid])->one();
                            if (!empty($newinfo)) {
                                $GLOBALS['errormsg'] = "错误:选手:{$caldata['mv_name']},证件号码:{$caldata['mv_idnumber']},已经报名了本次活动,请确认后再提交!";
                                return false;
                            }
                        }
                    }
                    $newinfo = EventInfo::findOne(['id' => $riid]);
                    if (empty($newinfo)) {
                        $newinfo = new EventInfo();
                    }
                }

                $newinfo->name = empty($caldata['mv_name']) ? '' : $caldata['mv_name'];
                $newinfo->idtype = empty($caldata['mv_idtype']) ? '' : $caldata['mv_idtype'];
                $newinfo->idnumber = empty($caldata['mv_idnumber']) ? '' : $caldata['mv_idnumber'];
                $newinfo->mobile = empty($caldata['mv_mobile']) ? '' : $caldata['mv_mobile'];
                $newinfo->nation = empty($caldata['mv_nation']) ? '' : $caldata['mv_nation'];
                $newinfo->size = empty($caldata['mv_size']) ? '' : $caldata['mv_size'];
                $newinfo->avatar = empty($caldata['mv_avatar']) ? '' : $caldata['mv_avatar'];
                if (empty($newinfo->avatar)) {
                    $newinfo->avatar = empty($caldata['mv_avatar2']) ? '' : $caldata['mv_avatar2'];
                }
                $newinfo->birth = $birth;
                $newinfo->sex = $sex;
                $newinfo->registerinfos = json_encode($v);
                $newinfo->matchid = $matchid;
                $newinfo->rgid = $rgid;
                $newinfo->typeid = $typeid;
                $newinfo->rrid = $rrid;
                $newinfo->memberid = (int)$memberid;
                $ret = $newinfo->save();
                if (empty($ret)) {
                    $GLOBALS['errormsg'] = '保存信息错误';
                    return false;
                }
            }

            $riids = EventInfo::find()->select('id')
                ->andFilterWhere(['rgid' => $rgid, 'typeid' => $typeid])->asArray()->all();

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $GLOBALS['errormsg'] = '异常错误';
            return false;
        }

        if ($riids) {
            return ['riids' => json_encode(ArrayHelper::getColumn($riids, 'id'))];
        }

        return false;
    }

    private static function couldModify($riid, $typeinfo, $sex)
    {
        if ($typeinfo->type != 3) {
            return true;
        }

        $registerinfo = EventInfo::findOne(['id' => $riid]);
        if (empty($registerinfo)) {
            return true;
        }

        if ($registerinfo->state != 1) {
            return true;
        }

        if ($registerinfo->sex == $sex) {
            return true;
        }


        $femalenum = EventInfo::find()->andFilterWhere(['rgid' => $registerinfo->rgid])
            ->andFilterWhere(['sex' => '女', 'state' => 1])->count();

        if ($registerinfo->sex == '女') {
            $femalenum--;
        } else {
            $femalenum++;
        }
        if ($femalenum < $typeinfo->fmincount || $femalenum > $typeinfo->fmaxcount) {
            return false;
        }

        return true;
    }


    public static function getMyMatchListPages($urid, $page = 1, $app = 0, $apph5 = 0, $gid = 0)
    {
        $limit = 20;

        $query =  EventRelation::find()->from(EventRelation::tableName() . ' as a')
            ->joinWith('match as b', true)
            ->andFilterWhere(['>', 'a.state', 0])
            //->andWhere (['or','a.type!=3 and a.state=1','a.type=3'])
            ->andFilterWhere(['a.urid' => $urid]);

        if ($app == 12) {    //赛易云平台
            //$query->andFilterWhere(['or',['a.app'=>$app],['b.app'=>$app],['b.cloudshow'=>1]]);
        } else {
            $query->andFilterWhere(['a.gid' => $gid]);
        }

        /*
        if(!empty($apph5)) {
            $query->andFilterWhere(['or',['a.app'=>$apph5],['a.app'=>$app]]);
        } else if(!empty($app)) {
            if($app == 1) {
                $query->andFilterWhere(['or',['a.app'=>11],['a.app'=>$app]]);
            } else {
                $query->andFilterWhere(['a.app' => $app]);
            }
        }
        */

        $totalnum =  $query->count();
        $pages = ceil($totalnum / $limit);
        $offset = ($page - 1) * $limit;
        $allinfo = $query->orderBy('a.id desc')->offset($offset)->limit($limit)->asArray()->all();
        $list = [];

        foreach ($allinfo as $one) {

            unset($tmp);
            if ($one['match']['status'] == 1) {
                if ($one['state'] == EventRelation::PAY_RETURN) {
                    $tmp['state'] = $one['state'];
                    $tmp['statetips'] = '已退款';
                } elseif ($one['state'] == EventRelation::PAY_YES) {
                    $tmp['state'] = $one['state'];
                    $tmp['statetips'] = '报名成功';
                } elseif ($one['state'] == EventRelation::PAY_NO) {
                    $tmp['state'] = $one['state'];
                    $tmp['statetips'] = '报名成功';
                }
            } else {
                $tmp['state'] = 99;
                $tmp['statetips'] = '比赛结束';
            }
            $tmp['rrid'] = $one['id'];
            $tmp['rgid'] = $one['rgid'];
            $tmp['matchid'] = $one['matchid'];
            $tmp['typeid'] = $one['typeid'];
            $tmp['typename'] = $one['typename'];
            $tmp['address'] = $one['match']['city'] . $one['match']['address'];
            $tmp['title'] = $one['match']['title'];
            $tmp['ischeck'] = $one['ischeck'];
            $tmp['endtime'] = $one['match']['end_time'];
            $tmp['starttime'] = $one['match']['start_time'];
            $tmp['imgurl'] = $one['match']['imgurl'];
            $tmp['type'] = $one['type'];
            $tmp['ordertime'] = $one['create_time'];

            $list[] = $tmp;
        }

        $data['total'] = $totalnum;
        $data['page'] = $page;
        $data['pages'] = $pages;
        $data['list'] = $list;

        return $data;
    }


    public static function getPreRegisterMemberList($urid, $rrid, $rgid, $typeid)
    {

        $prememberlist = EventInfo::find()->andFilterWhere(['rgid' => $rgid, 'rrid' => $rrid, 'typeid' => $typeid])
            ->orderBy('state asc')->all();

        $list  = [];
        if ($prememberlist) {
            foreach ($prememberlist as $k => $v) {
                $list[$k]['riid']   = $v->id;
                $list[$k]['rgid']   = $rgid;
                $list[$k]['typeid'] = $typeid;
                $list[$k]['rrid']   = $rrid;
                $list[$k]['memberid'] = $v->memberid;
                $list[$k]['state']      = $v->state;
                $list[$k]['attrs'] = json_decode($v->registerinfos);
            }
        }
        return $list ? ['list' => $list] : [];
    }

    public static function addMembers($urid, $gid, $json, $memberid = null)
    {
        $data   = json_decode($json, true);
        if (!is_array($data)) {
            $GLOBALS['errormsg'] = '信息格式错误';
            return false;
        }
        $ret = self::saveMember($urid, $gid, $data, $memberid);
        return $ret;
    }

    public static function delRegisterPreMember($urid, $rrid, $riid)
    {
        $relationmode = EventRelation::findOne(['id' => $rrid, 'urid' => $urid]);
        if (empty($relationmode)) {
            $GLOBALS['errormsg'] = '错误的订单';
            return false;
        }

        $typeinfo = EventType::findOne(['id' => $relationmode->typeid]);
        if (empty($typeinfo)) {
            $GLOBALS['errormsg'] = '错误的组别';
            return false;
        }

        $matchinfo = Event::findOne(['id' => $relationmode->matchid]);
        if (empty($typeinfo)) {
            $GLOBALS['errormsg'] = '错误的赛事';
            return false;
        }

        $modifyendtime = $matchinfo->end_time - $typeinfo->registerend * 3600 * 24;
        if ($modifyendtime < time()) {
            $GLOBALS['errormsg'] = '已过修改日期';
            return false;
        }

        $infomodel = EventInfo::findOne(['id' => $riid, 'rrid' => $rrid]);
        if (empty($infomodel)) {
            $GLOBALS['errormsg'] = '选手信息不存在';
            return false;
        }

        $ret = $infomodel->delete();
        if (!$ret) {
            $GLOBALS['errormsg'] = '删除错误';
        }
        return  $ret;
    }

    public static function getRegisterGroupAttrTmpls($typeid)
    {

        $typeinfo =  EventType::findOne(['id' => $typeid]);
        if (empty($typeinfo)) {
            $GLOBALS['errormsg'] = '错误组别';
            return false;
        }

        $data['attrs'] = json_decode($typeinfo->groupform, true);
        return $data;
    }

    public static function editRegisterGroup($urid, $matchid, $rrid, $rgid, $typeid, $json)
    {
        $data   = json_decode($json, true);
        $caldata = ArrayHelper::map($data, 'key_name', 'value');
        if (empty($caldata) || !is_array($caldata)) {
            $GLOBALS['errormsg'] = 'json错误';
            return false;
        }

        $groupmodel = EventGroup::findOne(['id' => $rgid, 'matchid' => $matchid]);
        if (empty($groupmodel)) {
            $GLOBALS['errormsg'] = 'Group错误';
            return false;
        }

        $relationmodel = EventRelation::findOne(['id' => $rrid]);
        if (empty($relationmodel)) {
            $GLOBALS['errormsg'] = '错误订单';
            return false;
        }

        $connection     =   Yii::$app->db;
        $transaction    = $connection->beginTransaction();
        try {
            $transaction->commit();


            $groupmodel->urid = $urid;
            $groupmodel->matchid = $matchid;
            $groupmodel->typeid = $typeid;
            $groupmodel->unit = empty($caldata['mv_unit']) ? '' : $caldata['mv_unit'];
            $groupmodel->leader = empty($caldata['mv_leader']) ? '' : $caldata['mv_leader'];
            $groupmodel->mobile = empty($caldata['mv_leader_mobile']) ? '' : $caldata['mv_leader_mobile'];
            $groupmodel->grouptype = empty($caldata['mv_grouptype']) ? '' : $caldata['mv_grouptype'];
            $groupmodel->regname = empty($caldata['mv_regname']) ? '' : $caldata['mv_regname'];
            $groupmodel->groupinfos = $json;
            $ret = $groupmodel->save();

            if ($ret) {
                if ($relationmodel->state == EventRelation::PAY_NOVALID) {
                    $relationmodel->state = EventRelation::PAY_NO;
                    $relationmodel->save();
                }
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            $GLOBALS['errormsg'] = '异常错误';
            return false;
        }



        if ($ret) {
            return ['rgid' => $groupmodel->id];
        }

        $GLOBALS['errormsg'] = '修改失败';
        return false;
    }

    public static function getRegisterGroupInfo($rgid, $typeid)
    {

        $rginfo = EventGroup::findOne(['id' => $rgid, 'typeid' => $typeid]);
        if (empty($rginfo)) {
            $GLOBALS['errormsg'] = '错误队伍信息';
            return false;
        }

        $typeinfo = EventType::findOne(['id' => $typeid]);
        if (empty($typeinfo)) {
            $GLOBALS['errormsg'] = '错误组别';
            return false;
        }

        $list = [];
        if ($typeinfo && isset($rginfo->groupinfos)) {
            $list = json_decode($rginfo->groupinfos, true);
            if (empty($list) && $typeinfo->groupform) {
                $list = json_decode($typeinfo->groupform, true);
            }
        }

        return $list ? ['list' => $list] : [];
    }

    public static function getRegisterInfo($riid)
    {

        $registerinfo = EventInfo::findOne(['id' => $riid]);

        if (empty($registerinfo)) {
            $GLOBALS['errormsg'] = '错误选手信息';
            return false;
        }

        $data['riid'] = $riid;
        $data['rrid'] = $registerinfo->rrid;
        $data['rgid'] = $registerinfo->rgid;
        $data['typeid'] = $registerinfo->typeid;
        $data['memberid'] = $registerinfo->memberid;
        $data['matchid'] = $registerinfo->matchid;
        $data['attrs'] = json_decode($registerinfo->registerinfos, true);


        return $data;
    }

}