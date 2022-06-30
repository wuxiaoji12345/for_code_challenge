<?php

/**
 * Created by wayne.
 * Date: 2019/2/1
 * Time: 1:27 PM
 */

namespace api\dbmodels;


use common\models\Pool;
use common\models\PoolQuality;
use common\models\UserInfo;

class dbPool
{

    public static function getList($sid){

        $list = Pool::find()->select('sid, id as poid, name')
            ->andFilterWhere(['sid'=>$sid, 'status'=>1])
            ->orderBy('weight desc')
            ->asArray()->all();

        return $list;

    }

    public static function getPoolinfo($sid) {
        $list = self::getList($sid);
        for($i=0; $i<count($list); $i++) {
            $one = $list[$i];
            $poid = $one['poid'];
            for ($j=1; $j<=5; $j++) {
                $onetype = PoolQuality::find()
                    ->select('checkname, value, type, create_time')
                    ->andFilterWhere(['type'=>$j])
                    ->andFilterWhere(['poid'=>$poid])
                    ->orderBy('create_time desc')->limit(1)
                    ->asArray()->one();
                $list[$i]['quality'][] = $onetype;
            }
        }

        return $list;
    }

    public static function Upload($urid, $extrainfo, $sid, $poid, $type, $value) {
        if(!is_numeric($type) || !is_numeric($value)) {
            $GLOBALS['errormsg'] = '数值格式错误';
            return false;
        }

        if($type<1 || $type>5) {
            $GLOBALS['errormsg'] = '错误水质类型';
            return false;
        }

        $poolinfo = Pool::findOne(['id'=>$poid, 'sid'=>$sid, 'status'=>1]);
        if(empty($poolinfo)) {
            $GLOBALS['errormsg'] = '错误的泳池';
            return false;
        }

        $quality = new PoolQuality();
        $quality->poid = $poid;
        $quality->create_time = time();
        $quality->cdate = date('Y-m-d', $quality->create_time);
        $quality->type = $type;
        $quality->value = $value;

        if(!empty($extrainfo->realname)) {
            $quality->checkname = $extrainfo->realname;
        } else {
            $userinfo = UserInfo::findOne(['urid'=>$urid]);
            if(!empty($userinfo)) {
                $quality->checkname = $userinfo->nickname;
            }
        }

        $ret = $quality->save();

        return $ret;
    }

    public static function getQualitylist($urid, $sid, $poid, $page) {

        $limit = 20;
        if(empty($page)) {
            $page = 1;
        }

        $poolinfo = Pool::find()->select('sid, name')
            ->andFilterWhere(['id'=>$poid, 'sid'=>$sid, 'status'=>1])
            ->asArray()->one();
        if(empty($poolinfo)) {
            $GLOBALS['errormsg'] = '错误的泳池';
            return false;
        }

        $query = PoolQuality::find()->select('checkname, type, value, create_time')
            ->andFilterWhere(['poid'=>$poid]);
        $total = $query->count();
        $pages = ceil($total / $limit);
        $offset = ($page - 1)*$limit;

        $list = $query->limit($limit)->offset($offset)
            ->orderBy('id desc')->asArray()->all();

        $output['poolinfo'] = $poolinfo;
        $output['page'] = $page;
        $output['pages'] = $pages;
        $output['total'] = $total;
        $output['list'] = $list;

        return $output;
    }

}