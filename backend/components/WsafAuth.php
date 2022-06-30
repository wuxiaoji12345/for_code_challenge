<?php

namespace backend\components;

use backend\models\Match;
use function Clue\StreamFilter\fun;
use linslin\yii2\curl\Curl;
use Yii;
use yii\web\ServerErrorHttpException;

class WsafAuth
{

    public $coreurl = 'https://wsaf.sagsh.cn/WSIdentityServer/core';
    public $coreurl2 = 'https://ssgl.sagsh.cn/CSM.Api';
    public $token;
    public $curl;


    function __construct($token = '')
    {
        if ($token) {
            $this->token = $token;
        } elseif (!Yii::$app->user->isGuest) {
            $this->token = Yii::$app->user->identity->getAuthKey();
        } else {
            throw new ServerErrorHttpException('Token不能为空');
        }
        $curl = new Curl();
        $curl->setHeaders(['Authorization' => "Bearer " . $this->token, 'Content-Type' => 'application/json']);

        $this->curl = $curl;
    }

    public function getUserInfo()
    {
        $requesturl = $this->coreurl . "/connect/userinfo";
        $this->curl->setHeader('Content-Length', 0);
        return   $this->curl->post($requesturl,false);

    }


    public function wsafMatchs()
    {
        $requesturl = $this->coreurl2 . "/Matches/PageFind";
        $gid = Yii::$app->user->identity->gid;
        $handleMatchs = function ($params, $requesturl, $gid, $existids, $handleMatchs) {
            $this->curl->setRawPostData(json_encode($params));
            $this->curl->setOption(CURLOPT_POST, 1);
            $res = $this->curl->post($requesturl, false);

            if (isset($res['IsSuccess']) && ($res['IsSuccess'])) {
                $matchList = $res['Result']['Data'];
                $newMatch = [];
                $newMatchKey = ['wsaf_match_id', 'title', 'imgurl', 'intro', 'disclaimer', 'reg_start_time', 'reg_end_time', 'start_time', 'end_time',
                    'province', 'city', 'district', 'address', 'longitude', 'latitude', 'create_time', 'matchtype', 'gid', 'app', 'userid','activities_quantity'];
                foreach ($matchList as $key => $v) {
                    if (!in_array($v['Id'], $existids)) {
                        array_push($newMatch, [
                            $v['Id'],
                            $v['Name'],
                            'https://ssgl.sagsh.cn/CSM.Api/filesystem/' . $v['PostUrl'],
                            $v['ContestsDesc'],
                            $v['Proof'],
                            strtotime($v['ApplyStartTime']),
                            strtotime($v['ApplyEndTime']),
                            strtotime($v['MatchStartTime']),
                            strtotime($v['MatchEndTime']),
                            $v['Province'],
                            $v['City'],
                            $v['DistrictDesc'],
                            $v['Venue'],
                            $v['Longitude'],
                            $v['Latitude'],
                            time(),
                            2,
                            $gid,
                            12,
                            Yii::$app->user->id,
                            $v['ParticipateNum']
                        ]);
                    }
                }

                return Yii::$app->db->createCommand()->batchInsert(Match::tableName(), $newMatchKey, $newMatch)->execute();
            } else {
                Yii::error(json_encode($res), 'wsaf');
                return false;
//                throw  new ServerErrorHttpException("赛事同步失败,请重新授权登录后再试");
            }
        };

        $params = ['PageNumber' => 1, 'PageSize' => 500];
        $wasfids = Match::find()->select(['wsaf_match_id'])->andWhere(['gid' => $gid, 'matchtype' => 2])->asArray()->all();
        $existids = [];
        foreach ($wasfids as $key => $v) {
            array_push($existids, $v['wsaf_match_id']);
        }
        return $handleMatchs($params, $requesturl, $gid, $existids, $handleMatchs);


    }

}