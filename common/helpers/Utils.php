<?php

/**
 * Created by wayne.
 * Date: 2019/1/8
 * Time: 4:48 PM
 */
namespace common\helpers;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use api\models\UserChannel;
use api\models\UserFormid;
use api\models\wxapp\Weixin;
use Yii;
class Utils
{
    static public function makeToken($n = 16)
    {
        $str = "0123456789abcdefghijklmnopqrstuvwxyz"; // 输出字符集
        // n 输出串长度
        $len = strlen($str) - 1;
        $s="";
        for ($i = 0; $i < $n; $i ++) {
            $s .= $str[rand(0, $len)];
        }
        return $s;
    }

    public static function genGroupCode(){
        $suffix = substr(time(),-7);
        $prex = rand(0, 99);
        return $prex.$suffix;
    }

    public static function build_order_no($k='sps'){
        return $k.date('YmdHis').mt_rand(10000,99999).substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);

    }

    static public function sendPhoneMessage($sign, $templatecode, $phone, $jsonParam) {
        $ossparams =   Yii::$app->params['oss'];
        $id         =   $ossparams['accessKeyId'];
        $key        =   $ossparams['accessKeySecret'];

        AlibabaCloud::accessKeyClient($id, $key)
            ->regionId('cn-hangzhou')
            ->asDefaultClient();

        $data['query']['PhoneNumbers'] = $phone;
        $data['query']['SignName'] = $sign;
        $data['query']['TemplateCode'] = $templatecode;
        $data['query']['TemplateParam'] = $jsonParam;

        try {
            $result = AlibabaCloud::rpc()->product('Dysmsapi')
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->options($data)->request();

            $ret = $result->toArray();
            if($ret['Code'] == 'OK') {
                return true;
            }

            return false;
        } catch (ClientException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
            return false;
        } catch (ServerException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
            return false;
        }
    }

    static private function getFormid($urid, $app) {
        $userinfo = UserChannel::findOne(['urid'=>$urid,'app'=>$app]);
        if(empty($userinfo)) return false;

        $output['openid'] = $userinfo->openid;

        $time6day = strtotime('-6 day');
        $formid = UserFormid::find()->andFilterWhere(['urid'=>$urid,'openid'=>$userinfo->openid,'status'=>1])
            ->andFilterWhere(['>', 'create_time', $time6day])
            ->orderBy('create_time asc')->limit(1)->one();

        if(!empty($formid)) {
            $output['formid'] = $formid->formid;
            $formid->status = 2;
            $formid->save();
        }

        return $output;
    }

    static public function sendWXMessage($urid, $app, $templateid, $msgdata, $page=null) {
        $userforminfo = self::getFormid($urid, $app);
        if(empty($userforminfo) || empty($userforminfo['formid']) || empty($msgdata)) {
            $GLOBALS['errormsg'] = 'wxmessage参数错误';
            return false;
        }

        $wxinfo = new Weixin();
        $msgdata['touser'] = $userforminfo['openid'];
        $msgdata['template_id'] = $templateid;
        $msgdata['form_id'] = $userforminfo['formid'];

        if(!empty($page))
            $msgdata['page'] = $page;

        $msg = json_encode($msgdata);
        $ret = $wxinfo->sendWxAppMsg($msg, $app);
        if($ret) {
            return $userforminfo['formid'];
        }
        return $ret;
    }
    static private function getOpenid($urid, $app) {
        $userinfo = UserChannel::findOne(['urid'=>$urid,'app'=>$app]);
        if(empty($userinfo)) return false;

        $output['openid'] = $userinfo->openid;
        return $output;
    }


    static public function sendWXMessageSubscribe($urid, $app, $templateid, $msgdata, $page=null) {
        $userforminfo = self::getOpenid($urid, $app);
        if(empty($userforminfo) || empty($msgdata)) {
            $GLOBALS['errormsg'] = 'wxmessage参数错误';
            Yii::warning('wxmessage参数错误', 'wxmsg');
            return false;
        }

        $wxinfo = new Weixin();
        $msgdata['touser'] = $userforminfo['openid'];
        $msgdata['template_id'] = $templateid;

        if(!empty($page))
            $msgdata['page'] = $page;

        $msg = json_encode($msgdata);
        $ret = $wxinfo->sendWxSubscribeMsg($msg, $app);

        return $ret;
    }

    static public function sendVerifyMessage($sign, $code, $phone, $productname)
    {

        $ossparams =   Yii::$app->params['oss'];
        $id         =   $ossparams['accessKeyId'];
        $key        =   $ossparams['accessKeySecret'];

        AlibabaCloud::accessKeyClient($id, $key)
            ->regionId('cn-hangzhou')
            ->asDefaultClient();

        $msgcode['code'] = $code;
        $msgcode['product'] = $productname;

        $data['query']['PhoneNumbers'] = $phone;
        $data['query']['SignName'] = $sign;
        $data['query']['TemplateCode'] = 'SMS_70310080';
        $data['query']['TemplateParam'] = json_encode($msgcode);


        try {
            $result = AlibabaCloud::rpc()->product('Dysmsapi')
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->options($data)->request();

            $ret = $result->toArray();
            if($ret['Code'] == 'OK') {
                return true;
            }

            return false;
        } catch (ClientException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
            return false;
        } catch (ServerException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
            return false;
        }
    }


    public static function urlsafe_b64decode($string) {

        $data = str_replace(array('-','_'),array('+','/'),$string);

        $mod4 = strlen($data) % 4;

        if ($mod4) {

            $data .= substr('====', $mod4);

        }
        return base64_decode($data);
    }



    public static function urlsafe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }

    public static function ecbEncrypt($key = "", $encrypt) {
        $encode = openssl_encrypt($encrypt,'des-ecb', $key);
        $encode = self::urlsafe_b64encode($encode);
        return $encode;
    }

    public static function ecbDecrypt($key = "", $decrypt) {
        $decrypt = self::urlsafe_b64decode($decrypt);
        $decoded = openssl_decrypt($decrypt,'des-ecb', $key);
        return self::trimEnd($decoded);
    }

    /*
     * 去掉填充的字符
     */

    private static function trimEnd($text) {
        $len = strlen($text);
        $c = $text[$len - 1];

        if (ord($c) == 0) {
            return rtrim($text, $c);
        }

        if (ord($c) < $len) {
            for ($i = $len - ord($c); $i < $len; $i++) {
                if ($text[$i] != $c) {
                    return $text;
                }
            }
            return substr($text, 0, $len - ord($c));
        }
        return $text;
    }


    // 计算身份证校验码，根据国家标准GB 11643-1999
    public static function idcard_verify_number($idcard_base){
        if(strlen($idcard_base)!=17){
            return false;
        }
        //加权因子
        $factor=array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);
        //校验码对应值
        $verify_number_list=array('1','0','X','9','8','7','6','5','4','3','2');
        $checksum=0;
        for($i=0;$i<strlen($idcard_base);$i++){
            $checksum += substr($idcard_base,$i,1) * $factor[$i];
        }
        $mod=$checksum % 11;
        $verify_number=$verify_number_list[$mod];
        return $verify_number;
    }

    private static function idcard_checksum18($idcard){
        if(strlen($idcard)!=18){
            return false;
        }
        $idcard_base=substr($idcard,0,17);
        if(self::idcard_verify_number($idcard_base)!=strtoupper(substr($idcard,17,1))){
            return false;
        }else{
            return true;
        }
    }

    // 将15位身份证升级到18位
    private static function idcard_15to18($idcard){
        if(strlen($idcard)!=15){
            return false;
        }else{
            // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
            if(array_search(substr($idcard,12,3),array('996','997','998','999')) !== false){
                $idcard=substr($idcard,0,6).'18'.substr($idcard,6,9);
            }else{
                $idcard=substr($idcard,0,6).'19'.substr($idcard,6,9);
            }
        }
        $idcard=$idcard.self::idcard_verify_number($idcard);
        return $idcard;
    }

    public static function  validation_filter_id_card($id_card){
        if(strlen($id_card)==18){
            return self::idcard_checksum18($id_card);
        }elseif((strlen($id_card)==15)){
            $id_card=self::idcard_15to18($id_card);
            return self::idcard_checksum18($id_card);
        }else{
            return false;
        }
    }

    /**
     * 根据身份证计算年龄
     * @param type $id
     * @return int
     */
    public static function getIdNumberInfo($id){

        //过了这年的生日才算多了1周岁
        if(empty($id)) return 0;
        $isMatched = self::validation_filter_id_card($id);
        if(!$isMatched)  return 0;

        $data['birth']  = date('Y-m-d', strtotime(substr($id,6,8)));
        $date   =strtotime(substr($id,6,8));

        $data['sex'] = substr($id,-2,1)%2 == 0 ? 2:1; //1-男;2-女

        //获得出生年月日的时间戳
        $today=strtotime('today');

        //获得今日的时间戳
        $diff=floor(($today-$date)/86400/365);
        $diff=floor(($today-$date-$diff/4*86400)/86400/365);

        //得到两个日期相差的大体年数
        //strtotime加上这个年数后得到那日的时间戳后与今日的时间戳相比
        $data['age'] = strtotime(substr($id,6,8).' +'.$diff.'years')>$today?($diff+1):$diff;
        $data['age'] = $data['age']>0?$data['age']:0;

        return $data;
    }

    public static function isAlpha(){
        if( strstr($_SERVER['HTTP_HOST'], 'alpha')){
            return true;
        }else{
            return false;
        }
    }

    static public function read_all_dir ( $dir )
    {
        $result = array();
        $handle = opendir($dir);
        if ( $handle )
        {
            while ( ( $file = readdir ( $handle ) ) !== false )
            {
                if ( $file != '.' && $file != '..' && substr($file, 0, 1)!='.' )
                {
                    $cur_path = $dir . DIRECTORY_SEPARATOR . $file;
                    if ( is_dir ( $cur_path ) )
                    {
                        $result['dir'][$cur_path] = self::read_all_dir ( $cur_path );
                    }
                    else
                    {
                        $result['file'][] = $cur_path;
                    }
                }
            }
            closedir($handle);
        }
        return $result;
    }

    private static $DIGITS = [
        '0', '1', '2', '3', '4', '5', '6', '7',
        '8', '9', 'A', 'B', 'C', 'D', 'E', 'F'
    ];

    public static function encode($data) {
        $l = strlen($data);
        $out = '';
        for ($i = 0; $i < $l; $i++) {
            $out .= self::$DIGITS[(0xF0 & ord($data[$i])) >> 4];
            $out .= self::$DIGITS[0x0F & ord($data[$i])];
        }
        return $out;
    }

    public static function decode($data) {
        $l = strlen($data);
        if (($l & 0x01) != 0) {
            echo 'error';exit;
        }
        $out = '';
        // two characters form the hex value.
        for ($j = 0; $j < $l; ) {
            $f = hexdec($data[$j]) << 4;
            $j++;
            $f = $f | hexdec($data[$j]);
            $j++;
            $out .= chr($f & 0xFF);
        }

        return $out;
    }

    public static function strEncrypt($key = "", $encrypt) {
        $data = openssl_encrypt($encrypt,'des-ecb', $key);
        return self::encode($data);
    }


    public static function strDecrypt($key = "", $decrypt) {
        $data = self::decode($decrypt);
        $decoded = openssl_decrypt($data,'des-ecb', $key);
        return $decoded;
    }
}