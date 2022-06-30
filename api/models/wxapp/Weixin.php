<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace api\models\wxapp ;

use common\helpers\CurlTools;
use common\models\WxToken;
use Yii;
/**
 * Description of Weixin
 *
 * @author Eagle
 * @date    2017-12-17 12:29:37
 */
class Weixin {
    private $wx_gettoken_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=';
    private $wx_refreshtoken_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=';
    private $wx_userinfo_url = 'https://api.weixin.qq.com/sns/userinfo?access_token=';
    

    /*
    * 获取用户的微信信息
    */
    private function getWxChatInfo($accesstoken,$openid){
        $url     =   $this->wx_userinfo_url.$accesstoken."&openid=".$openid."&lang=zh_CN"; 
        $result  =    json_decode( apiTools::Curl($url),true);
        $data = '';
        if( !MvVd::keyExist ('errcode', $result)){
            $data = $result;
        }
        return $data;
        
    }
    
    /**
     * 刷新token
     * @param type $appid
     * @param type $refresh_token
     * @return type
     */
    private function refreshAccessToken($appid,$refresh_token){
        $url =   $this->wx_refreshtoken_url.$appid."&refresh_token=".$refresh_token ."&grant_type=refresh_token";
        $result  =    json_decode(apiTools::Curl($url),true);
        
        return $result;
    }
    /**
     * 获取token
     * @param type $appid
     * @param type $secret
     * @param type $code
     * @return type
     */
    private function getAccessToken($appid,$secret,$code){
        $url     =   $this->wx_gettoken_url.$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code";
        $result  =   json_decode(apiTools::Curl($url),true);
        return $result;
    }
   
    /**
     * 保存token
     * @param type $userid
     * @param type $result
     * @return type
     */
    private function saveToken($userid,$result){

        $models = WalksWxToken::findModel(['userid'=>$userid]);

        $info['userid']     = $userid;
        $info['openid']     = (string)$result['openid'];
        $info['wx_token']   = (string)$result['access_token'];
        $info['expires_in'] = $result['expires_in'];
        $info['refresh_token'] = $result['refresh_token'];

        if($models && $models->load ($info) && $models->save ()){
            return $models->attributes;
        }
        
        return $info;
    }

    static public function getAccessToken1($app){
        $url = 'http://api.mdc.movecloud.cn/app/wechat-token?app='.$app;
        $ret = CurlTools::Curl($url);
        if(empty($ret)) {
            return false;
        }

        $retobj = json_decode($ret, true);
        if(empty($retobj)) {
            return false;
        }

        if($retobj['status'] != '200') {
            return false;
        }

        if(empty($retobj['data']) || empty($retobj['data']['token'])) {
            return false;
        }

        return $retobj['data']['token'];
        /*
        $savedtoken = WxToken::findOne(['app'=>$app, 'type'=>1]);
        if(!empty($savedtoken)) {
            if($savedtoken->create_time + $savedtoken->expires_in/2 > time() ){
                return $savedtoken->wx_token;
            }

        } else {
            $savedtoken = new WxToken();
            $savedtoken->app = $app;
            $savedtoken->type = 1;
        }

        $sysparams = Yii::$app->params;


        $appid  = $sysparams['appids'][$app]['appid'];
        $secret  = $sysparams['appids'][$app]['secret'];

        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";

        $result=json_decode( CurlTools::Curl($url),true);
        if(empty($result) | isset($result['errcode'])) {
            return false;
        }

        if(!empty($result['access_token'])){
            $savedtoken->wx_token = $result['access_token'];
            $savedtoken->create_time = time();
            $savedtoken->expires_in = $result['expires_in'];
            $savedtoken->save();
            return $result['access_token'];
        }

        return false;
        */
    }


    static public function getWXACodeUnlimit($scene,$page, $type, $width='430px',$auto_color=false,$is_hyaline=false){

        $token = self::getAccessToken1($type);

        if(empty($token))
            return false;

        $postdata['scene'] = $scene;
        $postdata['page'] = $page;
        $postdata['width'] = $width;

        $postdata = json_encode($postdata);
        $sendurl = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token={$token}";

        return  CurlTools::postCurl($sendurl,$postdata);

    }


    public function sendWxAppMsg($msg,$app){

        $token = self::getAccessToken1($app);
        if(empty($token)) {
            $GLOBALS['errormsg'] = '获取token失败';
            return false;
        }

        $sendurl = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token={$token}";
        $result  =    json_decode(CurlTools::postCurl($sendurl,$msg),true);
        if(isset($result['errcode'])) {
            $GLOBALS['errormsg'] = 'msg error:'.$result['errcode'];
            return false;
        }

        return true;

    }


    public function sendWxSubscribeMsg($msg,$app){

        $token = self::getAccessToken1($app);
        if(empty($token)) {
            $GLOBALS['errormsg'] = '获取token失败';
            Yii::warning('获取token失败', 'wxmsg');

            return false;
        }

        $sendurl = "https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token={$token}";
        $result  =    json_decode(CurlTools::postCurl($sendurl,$msg),true);
        if(isset($result['errcode'])) {
            $GLOBALS['errormsg'] = 'msg error:'.$result['errcode'];
            Yii::warning('msg error:'.$result['errcode'], 'wxmsg');

            return false;
        }
        return true;

    }

    static public function decryptedData($encryptedData, $sessionkey, $iv, $app=1){
        $sysparams = Yii::$app->params;
        $appid  = $sysparams['appids'][$app]['appid'];

        $pc = new wxBizDataCrypt($appid,$sessionkey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        if ($errCode == 0) {
            return ArrayUtils::toArray(json_decode($data));
        }
        return false;
    }
    
}
