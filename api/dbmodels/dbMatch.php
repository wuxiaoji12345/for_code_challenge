<?php
/**
 * Created by wayne.
 * Date: 2019/4/10
 * Time: 12:02 PM
 */

namespace api\dbmodels;

use api\models\ScoreEnroll;
use common\helpers\Utils;
use Yii;
use api\models\Banners;
use api\models\Match;
use api\models\MatchSession;
use api\models\RegisterDetail;
use api\models\RegisterInfo;
use api\models\RegisterRelation;
use api\models\RegisterType;
use yii\db\Expression;

class dbMatch
{

    public static function getMatchlist($page) {

        $limit = 20;
        $query = Match::find()->andFilterWhere(['status'=>1]);
        $total = $query->count();
        $pages = ceil($total/$limit);
        $offset = ($page - 1) * $limit;
        $data = $query->offset($offset)->limit($limit)->asArray()->all();
        $output['page'] = $page;
        $output['pages'] = $pages;
        $output['list'] = $data;
        return $output;
    }

    public static function getGidMatch($gid, $categoryid,  $page){

        $query = Match::find()
            ->select('id, title, start_time, end_time, address, imgurl,reg_start_time,reg_end_time')
            ->andFilterWhere(['status'=>1,'publish'=>1]);

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

    public static function getTimingMatch($gid, $categoryid,  $page){

        $query = Match::find()
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

    public static function getRegisterTypeList($matchid, $gid){

        $matchinfo = Match::findOne(['id'=>$matchid, 'status'=>1]);
        if(empty($matchinfo)) {
            $GLOBALS['errormsg'] = '错误赛事id';
            return false;
        }

        $typelist = RegisterType::find()
            ->select('matchid, id as typeid,fees,title,amount,type,mincount,maxcount,needcheck')
            ->andFilterWhere(['matchid'=>$matchid])
            ->orderBy ('weight desc')->asArray()->all();

        $output['list'] = $typelist;
        return $output;

    }

    public static function getSessionList($matchid, $gender, $birth, $typeid=null){
        $query = MatchSession::find()->select('a.id, a.start_time,a.province,a.city, a.district, a.address, a.stadium,a.register_count')
            ->from(MatchSession::tableName().' as a')
            ->joinWith(['items as b'=>function($query) use($gender, $typeid){
                $query->orderBy('b.weight desc, b.distance asc, b.type asc, b.agemin asc, b.gender asc');
                if(!empty($gender)) {
                    $query->onCondition(['or', ['b.gender' => $gender], ['b.gender' => 3]]);
                }
                if(!empty($typeid)) {
                    $query->onCondition(['b.typeid'=>$typeid]);
                }

                $query->andWhere(['b.status' => 1]);
            }], true)
            ->andFilterWhere(['a.matchid'=>$matchid, 'a.status'=>1]);

        if(!empty($gender)) {
            $query->andFilterWhere(['or',['b.gender'=>$gender],['b.gender'=>3]]);
        }

        $sessions = $query->asArray()->all();

        if(empty($sessions)) {
            return [];
        }
        $output = [];
        foreach($sessions as $k=>$v){

            unset($one);
            $one['id'] = $v['id'];
            $one['start_time'] = $v['start_time'];
            $one['province'] = $v['province'];
            $one['city'] = $v['city'];
            $one['district'] = $v['district'];
            $one['address'] = $v['address'];
            $one['stadium'] = $v['stadium'];
            $one['register_count'] = $v['register_count'];

            foreach ($v['items'] as $value) {
                unset($oneitem);
                $oneitem['ssid'] = $one['id'];
                $oneitem['id'] = $value['id'];
                $oneitem['name'] = $value['name'];
                $oneitem['type'] = $value['type'];
                $oneitem['gender'] = $value['gender'];
                $oneitem['distance'] = $value['distance'];
                $oneitem['agemin'] = $value['agemin'];
                $oneitem['agemax'] = $value['agemax'];

                if(!empty($birth)) {
                    $check = dbUser::judgeAge($oneitem['agemin'], $oneitem['agemax'], strtotime($birth));
                    if(empty($check)) continue;
                }

                $one['items'][] = $oneitem;
            }
            $output[] = $one;
        }

        return $output;
    }


    public static function getMyMatchListPages($urid, $page=1, $app = 0){

        $limit = 20;


        $query =  RegisterRelation::find()->from (RegisterRelation::tableName(). ' as a')
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
                if($one['state'] == RegisterRelation::PAY_RETURN){
                    $tmp['state'] = $one['state'];
                    $tmp['statetips'] = '已退款';
                }elseif($one['state'] == RegisterRelation::PAY_YES){
                    $tmp['state'] = $one['state'];
                    $tmp['statetips'] = '报名成功';
                }elseif($one['state'] == RegisterRelation::PAY_NO){
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


    public static function getPreRegisterMemberList($urid,$rrid,$typeid){

        $prememberlist = RegisterInfo::find()->andFilterWhere(['rrid'=>$rrid,'typeid'=>$typeid])
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
        $order = RegisterRelation::find()->select('matchid, order_no, typename, state, type, paytype, fees, paytime, ischeck, regname, groupcode, leader,leadermobile,groupinfos')
            ->andFilterWhere(['id'=>$rrid, 'urid'=>$urid])->asArray()->one();

        if(empty($order)) {
            $GLOBALS['errormsg'] = '错误订单';
            return false;
        }

        $matchinfo = Match::find()->select('title, start_time, end_time, province, city, district, address')
            ->andFilterWhere(['id'=>$order['matchid']])->asArray()->one();
        if(empty($matchinfo)) {
            $GLOBALS['errormsg'] = '错误赛事信息';
            return false;
        }

        $matchinfo['disclaimer'] = 'https://'.$_SERVER['HTTP_HOST'].'/match/matchintro?matchid='.$order['matchid'].'&type=1';



        $output['matchinfo'] = $matchinfo;

        $output['order'] = $order;

        $allinfo = RegisterInfo::find()->select('name,mobile,sex,idtype,idnumber,birth,registerinfos')
            ->andFilterWhere(['rrid'=>$rrid])
            ->asArray()->all();

        if(empty($allinfo)) {
            $output['users'] = [];
        } else {
            $output['users'] = $allinfo;
        }

        $sessions = RegisterDetail::find()->select('ssid, start_time,stadium,province,city,district,itemid1,itemname1,itemid2,itemname2,check_state1,check_state2')
            ->andFilterWhere(['rrid'=>$rrid])
            ->asArray()->all();

        $output['sessions'] = $sessions;

        $key = Yii::$app->params['gidKey'];
        $checkcode = Utils::ecbEncrypt($key, $rrid);
        $output['checkcode'] = $checkcode;

        return $output;
    }


    public static function getRegisterTypeAttrTmpls($typeid){

        $typeinfo = RegisterType::find()->select('registerform')->andFilterWhere(['id'=>$typeid])->one();

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

    public static function matchState($input){
        if($input){
            $hasregisters   = RegisterRelation::find()->andFilterWhere(['matchid'=>$input['matchid'],'state'=>1])->count();
            $totalregisters = RegisterType::find()->andFilterWhere(['matchid'=>$input['matchid']])->sum('amount');

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


    public static function getMatchDetail($matchid, $gid, $urid=null){

        $matchinfo = Match::find()
            ->select('id as matchid, category_id,title,reg_start_time, reg_end_time, start_time, end_time, tips,icon,imgurl,province,city,district,address, longitude, latitude')
            ->andFilterWhere(['id'=>$matchid, 'status'=>1])->asArray()->one();
        if(empty($matchinfo)) {
            $GLOBALS['errormsg'] = '错误赛事';
            return false;
        }

        $matchstate = self::matchState($matchinfo);
        $matchinfo['state'] = $matchstate['state'];
        $matchinfo['statetips'] = $matchstate['statetips'];

//        $matchinfo['favor'] = 0;
//        if(!empty($urid)) {
//            $myfavorite = MatchFavorite::findOne(['matchid'=>$matchid, 'urid'=>$urid]);
//            if(!empty($myfavorite)) {
//                $matchinfo['favor'] = $myfavorite->state;
//            }
//        }

        $matchinfo['intro']= 'https://'.$_SERVER['HTTP_HOST'].'/match/matchintro?matchid='.$matchid;

        return $matchinfo;
    }

    public static function searchenroll($matchid, $keyword) {
        $ssinfo = ScoreEnroll::find()->from(ScoreEnroll::tableName().' as a')
            ->select('a.ssid, b.name as sessionname, b.stadium, b.start_time')
            ->joinWith('session as b', false)
            ->andFilterWhere(['a.matchid' => $matchid, 'a.idcard'=>$keyword])
            ->groupBy('a.ssid')->orderBy('a.ssid asc')
            ->asArray()->all();

        if(empty($ssinfo)) {
            return [];
        }

        $iteminfo = ScoreEnroll::find()->from(ScoreEnroll::tableName().' as a')
            ->select('a.ssid, a.itemid, a.id,a.name, b.name as itemname, b.gender, b.type, b.distance, c.groupnum, c.lane, c.score, c.isvalued')
            ->joinWith('sessionitem as b', false)
            ->joinWith('scorestate as c', false)
            ->andFilterWhere(['a.matchid' => $matchid, 'a.idcard'=>$keyword])
            ->orderBy('a.itemid asc')
            ->asArray()->all();

        foreach ($iteminfo as $oneitem) {
            $ssid = $oneitem['ssid'];
            for ($i=0; $i<count($ssinfo); $i++) {
                if($ssinfo[$i]['ssid'] == $ssid) {
                    $ssinfo[$i]['items'][] = $oneitem;
                    break;
                }
            }
        }

        return $ssinfo;
    }
}