<?php
/**
 * Created by PhpStorm.
 * User: living
 * Date: 12/29/15
 * Time: 4:56 PM
 */

namespace common\models;

use common\models\mgt\ghOrderDetailMgt;
use common\models\model\Log;
use yii\log\Logger;
use Yii\web\Cookie;
use Yii;

class Tools
{

    /**
     * 判断手机号是否正确
     * @param $phone_number
     * @return bool
     */
    public static function checkPhoneNumberValidate($phone_number){
        //@2017-11-25 14:25:45 https://zhidao.baidu.com/question/1822455991691849548.html
        //中国联通号码：130、131、132、145（无线上网卡）、155、156、185（iPhone5上市后开放）、186、176（4G号段）、175（2015年9月10日正式启用，暂只对北京、上海和广东投放办理）,166,146
        //中国移动号码：134、135、136、137、138、139、147（无线上网卡）、148、150、151、152、157、158、159、178、182、183、184、187、188、198
        //中国电信号码：133、153、180、181、189、177、173、149、199
        $g = "/^1[34578]\d{9}$/";
        $g2 = "/^19[89]\d{8}$/";
        $g3 = "/^166\d{8}$/";
        if(preg_match($g, $phone_number)){
            return true;
        }else  if(preg_match($g2, $phone_number)){
            return true;
        }else if(preg_match($g3, $phone_number)){
            return true;
        }

        return false;

    }


    /**
     * 数组排序
     * @param $array
     * @param $keys
     * @param string $sort
     * @return array
     */
    public static function arraySort($array,$keys,$sort='asc') {
        $newArr = $valArr = array();
        foreach ($array as $key=>$value) {
            $valArr[$key] = $value[$keys];
        }
        ($sort == 'asc') ?  asort($valArr) : arsort($valArr);
        reset($valArr);
        foreach($valArr as $key=>$value) {
            $newArr[$key] = $array[$key];
        }
        return $newArr;
    }


    /**
     * 提取数组的字段值
     * @param $arr
     * @param null $flied
     * @return array
     */
    public static function getArrayFaild($arr,$flied = null)
    {
        $res = [];
        foreach($arr as $k=>$v)
        {
            if($flied)
                $res[] =   $v[$flied];
            else{
                $res[] =   $k;
            }
        }
        return $res;
    }

    public static function formatOnlyCode($onlycode)
    {
        if(strlen($onlycode)<>12)
        {
            $onlycode = preg_replace('/ /', '', $onlycode);
        }
        return substr($onlycode,0,4).' '.substr($onlycode,4,4).' '.substr($onlycode,8,4);
    }
    public static function getLastDay($date)
    {
        $days = (strtotime($date)-strtotime(date('Y-m-d')))/86400;
        return intval($days);
    }
    public static function formeatPrice($price,$num=2)
    {
        return number_format($price,$num);
    }

    /**
     * 获取参数
     */
    public static function getParam($name,$default='',$method='get')
    {
        if($method == 'post')
            $var = filter_input(INPUT_POST, $name, FILTER_SANITIZE_SPECIAL_CHARS);
        else
            $var = filter_input(INPUT_GET, $name, FILTER_SANITIZE_SPECIAL_CHARS);
        if(is_null($var))
            $var = $default;
        return $var;
    }
    /**
     * 获取get数组参数
     */
    public static function getArrayParam($name,$default='',$method='get')
    {
        if($method=='get')
            return isset($_GET[$name])?$_GET[$name]:$default;
        else
            return isset($_POST[$name])?$_POST[$name]:$default;
    }
    /**
     * 获取手机系统
     * @return string
     */
    public static function mobileOS()
    {
        $useragent = isset($_SERVER["HTTP_USER_AGENT"])?strtolower($_SERVER["HTTP_USER_AGENT"]):"";
        // iphone
        $is_iphone = strripos($useragent, 'iphone');

        $is_ios = strripos($useragent, 'ios');
        $is_ipad = strripos($useragent, 'ipad');
        $is_ipod = strripos($useragent, 'ipod');

        if ($is_iphone || $is_ios || $is_ipad || $is_ipod) {
            return 'ios';
        }
        // android
        $is_android = strripos($useragent, 'android');
        if ($is_android) {
            return 'android';
        }
        return 'other';
    }

    public static function jsonOut($array)
    {
        $str = '';
        if (is_array($array))
            $str = json_encode($array,JSON_UNESCAPED_UNICODE);
        else {
            $arrays = array($array);
            $str = json_encode($arrays,JSON_UNESCAPED_UNICODE);
        }
        exit($str);
    }

    /**
     * jsonpout
     * array to json and output jsonp
     */
    public static function jsonpOut($data)
    {
        $callback = Yii::$app->getRequest()->getQueryParam('callback');
        echo $callback . '(' . json_encode($data) . ');';
        exit;
    }

    /**
     * 设置cookie
     */
    public static function TCookie($key, $val = null, $time = 31536000)
    {
        $obj = "";
        if (empty($val)) {
            $obj = Yii::$app->request->cookies->get($key);
        } else {
            $cookie = new \yii\web\Cookie([
                'name' => $key,
                'value' => $val,
                'expire' => time() + $time
            ]);
            $obj = Yii::$app->response->cookies->add($cookie);
        }
        return $obj;
    }

    public static function FormatKm($m)
    {
        $strlen = strlen($m);
        $unit = 'm';
        if ($strlen > 3) {
            $m = round($m / 1000, 2);
            $unit = 'km';
        }
        return array('m' => $m, 'unit' => $unit);
    }



    /**
     * 设置cache
     */
    public static function TCache($key, $val = null, $time = 31536000)
    {
        $TCache = Yii::$app->cache;
        if (empty($val)) {
            $cacheData = $TCache->get($key);
            return $cacheData;
        } else {
            if ($val)
                $cacheData = $TCache->set($key, $val, $time);
        }
    }

    /**
     * 设置cache
     */
    public static function TFile($key, $val = null)
    {
        $file = dirname(Yii::$app->basePath). '/forms/'.$key;
        if($val)
        {
            $myfile = fopen($file, "w") or die("Unable to open file!");
            fwrite($myfile, $val);
            fclose($myfile);
        }else{
            $val = file_get_contents($file);
            return $val;
        }
    }

    public static function TCacheDel($key)
    {
        $TCache = Yii::$app->cache;
        $TCache->delete($key);
    }

    /**
     * ip地址国别判断
     */
    public static function getCountryByIp($ip = null)
    {
        $country = Tools::TCookie('country');
        if (!$country) {
            $country = 'en';
            $ip = $ip ? $ip : Yii::app()->request->userHostAddress;
            if ($ip == '127.0.0.1')
                return 'cn';
            $c = Yii::$app->ip2location->getCountryCode($ip);
            if(strtolower($c) == 'cn')
                $country = 'cn';
            /*
            //阿里ip地址判断
            $url = 'http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip;
            $opts = array(
                'http' => array(
                    'method' => "GET",
                    'timeout' => 3,//单位秒
                )
            );
            $page = file_get_contents($url, false, stream_context_create($opts));
            if ($page) {
                $chinaIPS = array('cn', 'hk', 'tw');
                $pageArray = json_decode($page, true);
                $country_id = isset($pageArray['data']['country_id']) ? strtolower($pageArray['data']['country_id']) : "en";
                if (in_array($country_id, $chinaIPS)) {
                    $country = 'cn';
                } else
                    $country = 'en';
            }
            */
            Tools::TCookie('country', $country);
        }
        return $country;
    }

    public static function getLg()
    {
        return Yii::$app->language ? Yii::$app->language : self::TCookie('lg');
    }


    public static function remote($tmpurls, $reffer = null, $header = true, $charset = null, $encoding = "", $httpheader = "",$postdata='')
    {
        $urls = array();
        if ($tmpurls && !is_array($tmpurls)) {
            $urls[$tmpurls] = $tmpurls;
        } else if (is_array($tmpurls)) {
            $urls = $tmpurls;
        } else {
            return false;
        }

        /**
         * 判断是否启动了cache
         * 由于是一次获取的，此处可以通过统一判断，即要存在都存在，不存在就都不存在
         */


        $user_agents = array(
            "Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/6.0)", //来路
            "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:35.0) Gecko/20100101 Firefox/35.0", //来路
            "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)", //来路
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.2; Trident/6.0)", //来路
            "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)", //来路
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/6.0)", //来路
        );
        $user_agent = $user_agents[rand(0, 5)];
        $curl = $text = array();
        $handle = curl_multi_init();
        foreach ($urls as $k => $v) {
//         $nurl[$k]= preg_replace('~([^:\/\.]+)~ei', "rawurlencode('\\1')", $v);
            $nurl[$k] = $v;
            $curl[$k] = curl_init($nurl[$k]);
            curl_setopt($curl[$k], CURLOPT_HEADER, $header);
            if ($httpheader)
                curl_setopt($curl[$k], CURLOPT_HTTPHEADER, $httpheader);
            curl_setopt($curl[$k], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl[$k], CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl[$k], CURLOPT_NOBODY, false);

            if ($encoding)
                curl_setopt($curl[$k], CURLOPT_ENCODING, $encoding);
            if ($reffer)
                curl_setopt($curl[$k], CURLOPT_REFERER, $reffer); //来路地址
            curl_setopt($curl[$k], CURLOPT_USERAGENT, $user_agent);
            $timeout = 30;
            curl_setopt($curl[$k], CURLOPT_TIMEOUT, $timeout); //过期时间
            if($postdata)
            {
                curl_setopt($curl[$k], CURLOPT_POST, 1);//post方式提交
                curl_setopt($curl[$k], CURLOPT_POSTFIELDS, $postdata);//要提交的信息
            }
            curl_multi_add_handle($handle, $curl[$k]);
        }

        $active = null;
        do {
            $mrc = curl_multi_exec($handle, $active);
        } while ($active);


        foreach ($curl as $k => $v) {
            if (curl_error($curl[$k]) == "") {

                if ($charset) {
                    $texttmp = (string)curl_multi_getcontent($curl[$k]);
//            		$text[$k] = mb_convert_encoding($texttmp, "utf-8",$charset);
                    $text[$k] = html_entity_decode(mb_convert_encoding($texttmp, 'UTF-8', $charset), ENT_QUOTES, 'UTF-8');
                } else {
                    $texttmp = (string)curl_multi_getcontent($curl[$k]);
                    $p = '/http-equiv="Content-Type" content="(.*?)"/';
                    preg_match($p, $texttmp, $out);
                    $tmp = isset($out[1]) ? strtolower($out[1]) : "";
                    $p2 = '/charset=(.*)/';
                    preg_match($p2, $tmp, $out2);
                    $charset = isset($out2[1]) ? strtolower($out2[1]) : "UTF-8";
                    if (strpos($charset, 'utf'))
                        $text[$k] = $texttmp;
                    else {
                        $text[$k] = html_entity_decode(mb_convert_encoding($texttmp, 'UTF-8', $charset), ENT_QUOTES, 'UTF-8');
                    }
                }
            }
            curl_multi_remove_handle($handle, $curl[$k]);
            curl_close($curl[$k]);
        }
        curl_multi_close($handle);
        return $text;
    }

    /**
     * 模拟post进行url请求
     * @param string $url
     * @param string $param
     */
    public  static function request_post($url = '', $param = '') {
        if (empty($url) || empty($param)) {
            return false;
        }

        $postUrl = $url;
        $curlPost = $param;
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);

        return $data;
    }

    public static function  curl_post_302($url) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL,  $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        $data = curl_exec($ch);
        $Headers =  curl_getinfo($ch);
        curl_close($ch);
        if ($data != $Headers){
            return $Headers["redirect_url"];
        }else{
            return false;
        }

    }

    ////获得访客浏览器语言
    public static function Get_Lang()
    {
        $lang = 'en';
        if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $str = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $str = substr($str, 0, 5);
            if(preg_match("/zh/i", $str)) {
                $lang = "cn";
            }
        }
        return $lang;
    }

    public static function formatInfos($info)
    {
        $tmp = str_replace(array('lng', 'lat','r'), array('"lng"','"lat"','"r"'), $info);
        $tmp = '{'.$tmp.'}';
        $infos = json_decode($tmp,true);
        if(isset($infos['lat']))
        {
            $infos['lat'] = round($infos['lat'],3);
            $infos['lng'] = round($infos['lng'],3);
        }
        return $infos;
    }

    /**
     * get Month By Date
     * @param null $date
     * @return array
     */
    public static function getMonth($sdate = null,$edate = null,$day=29)
    {
        $sdate = $sdate?strtotime($sdate):strtotime(' -'.$day.' day');
        $edate = $edate?strtotime($edate):time();
        $result = array();
        for($i=$sdate;$i<=$edate;$i=$i+86400)
        {
            $result[] = date('Ymd',$i);
        }
        return $result;
    }


    public static function getBestZoomlevel($distance) {
        $r = $distance/(1128.497220 * 0.0027);
        return 21-round(log($r,2),2);
    }

    /**
     * 根据输入的地点坐标计算中心点（适用于400km以下的场合）
     */

    public static function  GetCenterPointFromListOfCoordinates($geoCoordinateList)
    {
        //以下为简化方法（400km以内）
        $total = count($geoCoordinateList);
        $lat = $lon = 0;
        foreach ($geoCoordinateList as $k => $g) {
            if($g['lng']>=73.66 && $g['lng']<=135.05 && $g['lat']>=3.86 && $g['lat']<=53.55)
            {
                $lat += $g['lat'] * PI() / 180;
                $lon += $g['lng'] * PI() / 180;
            }else{
                $total--;
            }
        }
        $lat = $lat / $total;
        $lon = $lon / $total;
        return array('lat' => $lat * 180 / PI(), 'lng' => $lon * 180 / PI());
    }

    public static function asyncjob($asyurl, $host, $port)
    {
        $fp = fsockopen($host, $port, $errno, $errstr, 1);
        if (!$fp) {
            //todo
            //file_put_contents(APPLICATION_PATH.'/data/err_'.self::getClient(), $asyurl."\n", FILE_APPEND);
            //self::reportException($errno, $errstr);
            return 0;
        } else {
            $out = "GET $asyurl HTTP/1.1\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Connection: Close\r\n\r\n";
            fputs($fp, $out);
            fclose($fp);
            return 1;
            //file_put_contents(APPLICATION_PATH.'/data/success_'.self::getClient(), $asyurl."\n", FILE_APPEND);
        };
    }

    /**
     * 异步 post 提交数据
     * @param $asyurl
     * @param $params
     * @param $host
     * @param $port
     * @return int
     */
    public static function asyncpost($asyurl, $params,$host, $port=80)
    {
        $port = 80;//强制用80端口
        $fp = fsockopen($host, $port, $errno, $errstr, 1);
        if (!$fp) {
            //todo
            //file_put_contents(APPLICATION_PATH.'/data/err_'.self::getClient(), $asyurl."\n", FILE_APPEND);
            //self::reportException($errno, $errstr);
            return 0;
        } else {
            $data = http_build_query($params);
// send request
            $out = "POST ${asyurl} HTTP/1.1\r\n";
            $out .= "Host:${host}\r\n";
            $out .= "Content-type:application/x-www-form-urlencoded\r\n";
            $out .= "Content-length:".strlen($data)."\r\n";
            $out .= "Connection:close\r\n\r\n";
            $out .= "${data}";
            fputs($fp, $out);
// get response
            $response = '';
            while($row=fread($fp, 4096)){
                $response .= $row;
            }
            fclose($fp);
            return 1;
            //file_put_contents(APPLICATION_PATH.'/data/success_'.self::getClient(), $asyurl."\n", FILE_APPEND);
        };
    }

    public static function getIp()
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            $ip = getenv("REMOTE_ADDR");
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER['REMOTE_ADDR'];
        else if (isset($_SERVER['HTTP_X_REAL_IP']) && $_SERVER['HTTP_X_REAL_IP'] && strcasecmp($_SERVER['HTTP_X_REAL_IP'], "unknown"))
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        else
            $ip = "unknown";
        return ($ip);
    }

    public static function makeTokenBySTID($stid, $username)
    {

//		$finduser = Model_lUserMgt::find ( $urid );
        $strname = md5($username);
        $strshort = substr($strname, 0, 3);
        $newid = $stid * 983 + 1013;
        //$strhex = dechex ( $newid );
        $strhex = self::Num2Str($newid);
        $str = $strshort . $strhex;
        return $str;
    }

    public static function Str2Num($str)
    {
        $remain = $str;
        $num = 0;
        while ($remain) {
            $c = substr($remain, 0, 1);
            $mod = ord($c);
            if ($mod < 91) {
                $n = $mod - 65;
            } else {
                $n = $mod - 97 + 26;
            }
            $remain = substr($remain, 1);
            $num += $n * pow(52, strlen($remain));
        }
        return $num;
    }

    public static function Num2Str($num)
    {
        $remain = $num;
        $str = "";
        while ($remain > 0) {
            $mod = $remain % 52;
            if ($mod < 26) {
                $c = chr($mod + 65);
            } else {
                $c = chr($mod - 26 + 97);
            }
            $str = $c . $str;
            $remain = intval(($remain - $mod) / 52);
        }
        return $str;
    }

    public static function getURIDByToken($token)
    {
        if (strlen($token) < 5) {
            return 0;
        }
        $strhex = substr($token, 3);
        //$newid = hexdec($strhex);
        $newid = self::Str2Num($strhex);
        $newid -= 1013;
        if ($newid % 983 == 0) {
            $stid = $newid / 983;
        } else {
            return 0;
        }
        return $stid;
    }

    /**
     * 整数加密
     * @param $num
     * @param $key
     * @return string
     */
    public static function encodeNum($num, $key=null)
    {
        if(empty($key))
        {
            $s = time();
            $key = md5($s);
            $strshort = substr($key, 0, 3);
        }else
            $strshort = substr($key, 0, 3);

        $newid = $num * 983 + 1013;
        $strhex = self::Num2Str($newid);
        $str = $strshort . $strhex;
        return $str;
    }

    /**
     * 生成唯一码
     * @param int $strlen
     * @return bool|string
     */
    public static function makeUid($strlen = 24)
    {
        $pid = getmypid();
        $pidcode = dechex($pid);
        $unicode = uniqid("", true);
        $md5code = md5(time() . mt_rand(0, 1000));
        $out = substr($unicode, 0, 14);
        $out = $out . $pidcode;
        $morelen = $strlen - strlen($out);
        if ($morelen > 0) {
            $morestr = substr($md5code, 0, $morelen);
            $out .= $morestr;
        }
        return $out;
    }

    /**
     * 整数揭秘
     * @param $str
     * @return float|int
     */
    public static function decodeNum($str)
    {
        if (strlen($str) < 5) {
            return 0;
        }
        $strhex = substr($str, 3);
        $newid = self::Str2Num($strhex);
        $newid -= 1013;
        if ($newid % 983 == 0) {
            $num = $newid / 983;
        } else {
            return 0;
        }
        return $num;
    }

    /**
     * 字符串加密
     * @param $arr
     * @return string
     */
    public static function encodeStr($decodestr)
    {
        $str = 'adfasdfasfsfohjkhfiwuykfhskfayfiusyidfy';
        return substr($str,rand(0,strlen($str)),2).base64_encode('loco'.base64_encode($decodestr).'lsn');
    }

    /**
     * 字符串解密
     * @param $decodestr
     * @return string
     */
    public static function decodeStr($encodestr)
    {
        $str = 'adfasdfasfsfohjkhfiwuykfhskfayfiusyidfy';
        $encodestr = substr($encodestr,2);
        $encodestr = base64_decode($encodestr);
        $encodestr = substr($encodestr,4);
        $encodestr = substr($encodestr,0,strlen($encodestr)-3);
        $encodestr = base64_decode($encodestr);
        return $encodestr;
    }


    /**
     *
     * @param $k
     * @param null $v
     * @param null $timeout
     * @return mixed|void
     */
    public static function TSession($k,$v=null,$timeout=null)
    {
        $session = Yii::$app->session;
        if($v)
            $session->set($k,$v);
        else
        {
            if($session->has($k))
            {
                return $session->get($k);
            }
        }
        if($timeout)
            $session->timeout = $timeout;
        return ;

    }

    /**
     *
     * @param $k
     * @param null $v
     * @param null $timeout
     * @return mixed|void
     */
    public static function TSessionDel($k=null)
    {
        $session = Yii::$app->session;
        if($k)
        {
            if($session->has($k))
            {
                return $session->destroySession($k);
            }
        }else{
            return $session->destroy();
        }

        return ;

    }

    /**
     * @param $file UploadedFile 对象
     * @param $type 项目名称，比如party，user等
     * @param string $act create：新建 或 update：更新，如果传了fileurl原图片路径，则先做删除，在保存文件
     * @param string $fileurl 原图片路径
     * @param null $filename 保存的图片名称，默认为空
     * @return string
     */

    public  static function uploadFile($file,$type='',$act="create",$fileurl='',$filename=null){

        if(empty($type)) $type  =   date('Ymd');
        if(!empty($fileurl)&&$act==='update'){
            $deleteFile=Yii::$app->basePath.'/web'.$fileurl;
            if(is_file($deleteFile))
                unlink($deleteFile);
        }
        if(isset(Yii::$app->params['uploadfilepath']) && Yii::$app->params['uploadfilepath'] !='')
            $uploadDir=Yii::$app->params['uploadfilepath'].$type;
        else
            $uploadDir = Yii::$app->basePath.'/web/uploads/'.$type;


        self::recursionMkDir($uploadDir);
        if(!$filename)
            $filename=time().'-'.uniqid(). '.'.$file->extension;

        $uploadPath=$uploadDir.'/'.$filename;


        $filePath= Yii::$app->params['file_url'].$type.'/'.$filename;

        move_uploaded_file($file->tempName,$uploadPath); // 上传图片
        return $filePath;
    }

    /**
     * @param $file $_FIFLE 对象
     * @param $type 项目名称，比如party，user等
     * @param string $act create：新建 或 update：更新，如果传了fileurl原图片路径，则先做删除，在保存文件
     * @param string $fileurl 原图片路径
     * @param null $filename 保存的图片名称，默认为空
     * @return string
     */

    public  static function uploadFileByFile($file,$type='',$act="create",$fileurl='',$filename=null){

        if(empty($type)) $type  =   date('Ymd');
        if(!empty($fileurl)&&$act==='update'){
            $deleteFile=Yii::$app->basePath.'/web'.$fileurl;
            if(is_file($deleteFile))
                unlink($deleteFile);
        }
        if(isset(Yii::$app->params['uploadfilepath']) && Yii::$app->params['uploadfilepath'] !='')
            $uploadDir=Yii::$app->params['uploadfilepath'].$type;
        else
            $uploadDir = Yii::$app->basePath.'/web/uploads/'.$type;

        self::recursionMkDir($uploadDir);
        $pinfo=pathinfo($file["name"]);
        $ftype=isset($pinfo['extension'])?$pinfo['extension']:self::formatImageExt($file["type"]);
        if(!$filename)
            $filename=time().'-'.uniqid(). '.'.$ftype;
        else
            $filename=$filename.'.'.$ftype;
        $uploadPath=dirname(\Yii::$app->basePath).'/'.$uploadDir.'/'.$filename;
        $filePath= Yii::$app->params['file_url'].$type.'/'.$filename;
        move_uploaded_file($file['tmp_name'],$uploadPath); // 上传图片
        return ['uploadPath'=>$uploadPath,'url'=>$filePath,'filename'=>$filename,'ftype'=>$ftype];
    }

    public static function recursionMkDir($dir){
        if(!is_dir($dir)){
            if(!is_dir(dirname($dir))){
                self::recursionMkDir(dirname($dir));
                mkdir($dir,0777);
            }else{
                mkdir($dir,0777);

            }
        }
    }

    public static function formatImageExt($type=null)
    {
        $m = ['image/gif'=>'gif', 'image/jpeg'=>'jpg', 'image/png'=>'png', 'image/psd'=>'psd', 'image/bmp'=>'bmp'];
        return $type && isset($m[$type])?$m[$type]:"jpg";
    }


    public static function makeRange($location)
    {
        $lat = $location['lat'];
        $lng = $location['lng'];
        $dis = $location['dist'];  //meter
        $lat_dist = round($dis * 10000 / 111000);
        $lat_min = (round($lat * 10000) - $lat_dist) / 10000;
        $lat_max = (round($lat * 10000) + $lat_dist) / 10000;
        $lng_dist = round($dis * 10000 / (111000 * cos(deg2rad($lat))));  //geyingjun modified
        $lng_min = (round($lng * 10000) - $lng_dist) / 10000;
        $lng_max = (round($lng * 10000) + $lng_dist) / 10000;
        $range['lat_min'] = $lat_min;
        $range['lat_max'] = $lat_max;
        $range['lng_min'] = $lng_min;
        $range['lng_max'] = $lng_max;
        return $range;
    }


    /**
     * 计算两点地理坐标之间的距离
     * @param  Decimal $longitude1 起点经度
     * @param  Decimal $latitude1  起点纬度
     * @param  Decimal $longitude2 终点经度
     * @param  Decimal $latitude2  终点纬度
     * @param  Int     $unit       单位 1:米 2:公里
     * @param  Int     $decimal    精度 保留小数位数
     * @return Decimal
     */
    public static function getDistance($longitude1, $latitude1, $longitude2, $latitude2, $unit=2, $decimal=2){
        $EARTH_RADIUS = 6370.996; // 地球半径系数
        $PI = 3.1415926;
        $radLat1 = $latitude1 * $PI / 180.0;
        $radLat2 = $latitude2 * $PI / 180.0;
        $radLng1 = $longitude1 * $PI / 180.0;
        $radLng2 = $longitude2 * $PI /180.0;
        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;
        $distance = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
        $distance = $distance * $EARTH_RADIUS * 1000;
        if($unit==2){
            $distance = $distance / 1000;
        }
        return round($distance, $decimal);
    }

    public static function getBestZoomlevelByLatLng($latlngs)
    {
        $zoom = 14;

        if(count($latlngs)>1)
        {
            $dist = 0;
            foreach($latlngs as $k=>$v)
            {
                foreach($latlngs as $kk=>$vv)
                {
                    if($k!=$kk)
                    {
                        if(!$v['lng'] || $v['lat'] || $vv['lng'] || $vv['lat'])
                            continue;
                        $d =  self::getDistance($v['lng'],$v['lat'],$vv['lng'],$vv['lat'],1);
                        if($d>$dist)
                            $dist = $d;
                    }
                }
            }
            if($dist>0)
            {
                $zoom = self::getBestZoomlevel($dist);
                $zoom = ceil($zoom);
            }
        }
        return $zoom;
    }

    public static function getHowWeek($time)
    {
        $re = '';
        $weekarray=array("日","一","二","三","四","五","六");
        if(Yii::$app->language =='cn')
            $re = "星期".$weekarray[date("w",$time)];
        else
            $re = date('l',$time);
        return $re;
    }
    public static function getHost()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'];
        return $host;
    }


    public  static function getUrlArray($url=null)
    {
        if(!$url)
            return ;
        $querys = parse_url($url);
        $query = $querys['query'];
        $queryParts = explode('&', $query);
        $params = array();
        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            $params[$item[0]] = $item[1];
        }
        return $params;
    }
    /**
     * 将参数变为字符串
     * @param $array_query
     * @return string string 'm=content&c=index&a=lists&catid=6&area=0&author=0&h=0®ion=0&s=1&page=1'(length=73)
     */
    public static  function getUrlQuery($array_query)
    {
        $tmp = array();
        foreach($array_query as $k=>$param)
        {
            $tmp[] = $k.'='.$param;
        }
        $params = implode('&',$tmp);
        return $params;
    }

    /**
     * 格式化日期
     * @param $time
     * @return string
     */
    public static function format_date($time,$op=1){
        if(!is_numeric($time)){
            $time=strtotime($time);
        }
        if($time<=0)
            return '';
        if($op == 1)
            $t=time()-$time;
        else
            $t=$time - time();
        $f=array(
//            '31536000'=>'年',
//            '2592000'=>'个月',
//            '604800'=>'星期',
            '86400'=>'天',
            '3600'=>'小时',
//            '60'=>'分钟',
//            '1'=>'秒'
        );
        $str = '';
        foreach ($f as $k=>$v)
        {
            if (0 !=$c=floor($t/(int)$k)) {
                if($k == 86400)
                    $t -= $c*86400;
                else if($k == 3600)
                    $t -= $c*3600;
                $str .= '<span class="pink">'.$c.'</span>'.$v;
            }
        }
        return $str;
    }

    public static function transformLat($x, $y)
    {
        $lat = -100.0 + 2.0 * $x + 3.0 * $y + 0.2 * $y * $y + 0.1 * $x * $y + 0.2 * sqrt(abs($x));
        $lat += (20.0 * sin(6.0 * $x * pi()) + 20.0 * sin(2.0 * $x * pi())) * 2.0 / 3.0;
        $lat += (20.0 * sin($y * pi()) + 40.0 * sin($y / 3.0 * pi())) * 2.0 / 3.0;
        $lat += (160.0 * sin($y / 12.0 * pi()) + 320 * sin($y * pi() / 30.0)) * 2.0 / 3.0;
        return $lat;
    }

    public static function transformLon($x, $y)
    {
        $lon = 300.0 + $x + 2.0 * $y + 0.1 * $x * $x + 0.1 * $x * $y + 0.1 * sqrt(abs($x));
        $lon += (20.0 * sin(6.0 * $x * pi()) + 20.0 * sin(2.0 * $x * pi())) * 2.0 / 3.0;
        $lon += (20.0 * sin($x * pi()) + 40.0 * sin($x / 3.0 * pi())) * 2.0 / 3.0;
        $lon += (150.0 * sin($x / 12.0 * pi()) + 300.0 * sin($x / 30.0 * pi())) * 2.0 / 3.0;
        return $lon;
    }

    public static function isInChina($flat, $flng)
    {
        return true;
        //暂时都是true
        $data = self::searchgeo3($flat, $flng);
        if(!empty($data))
        {
            if($data[0]['ID_0'] == 49)
                return true;
        }

        return false;
    }

    /**
     *
     * @param $lat
     * @param $lng
     * @param $flag
     * @return mixed
     */
    public static function adjust_china($lat, $lng, $flag)
    {
        $ee = 0.00669342162296594323;
        $a = 6378245.0;

        $ret['lat'] = $lat;
        $ret['lng'] = $lng;

        if(!self::isInChina($lat, $lng))
        {
            return $ret;
        }
        $adjustLat = self::transformLat($lng - 105.0, $lat - 35.0);
        $adjustLng = self::transformLon($lng - 105.0, $lat - 35.0);


        $radLat = $lat / 180.0 * pi();
        $magic = sin($radLat);
        $magic = 1 - $ee * $magic * $magic;
        $sqrtMagic = sqrt($magic);
        $adjustLat = ($adjustLat * 180.0) / (($a * (1 - $ee)) / ($magic * $sqrtMagic) * pi());
        $adjustLng = ($adjustLng * 180.0) / ($a / $sqrtMagic * cos($radLat) * pi());
        if($flag)
        {
            $ret['lat'] = $lat + $adjustLat;
            $ret['lng'] = $lng + $adjustLng;
        }
        else
        {
            $ret['lat'] = $lat - $adjustLat;
            $ret['lng'] = $lng - $adjustLng;
        }

        return $ret;
    }

    public static function is_weixin()
    {
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            return true;
        }
        return false;
    }

    public static function is_qq()
    {
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'QQ') !== false ) {
            return true;
        }
        return false;
    }

    public static function httpcopy($url, $file = '', $timeout = 60)
    {
        $result = '';
        if ($file) {
            $file = empty($file) ? pathinfo($url, PATHINFO_BASENAME) : $file;
            $dir = pathinfo($file, PATHINFO_DIRNAME);
            !is_dir($dir) && @mkdir($dir, 0755, true);
        }
        $url = str_replace(' ', "%20", $url);
        $url = urldecode($url);
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            $temp = curl_exec($ch);
            if (!curl_error($ch)) {
                if ($file)
                    @file_put_contents($file, $temp);
                $result = $temp;
            }
        } else {
            $opts = array(
                'http' => array(
                    'method' => 'GET',
                    'header' => '',
                    'timeout' => $timeout
                )
            );
            $context = stream_context_create($opts);
            if ($file && @copy($url, $file, $context)) {
                $result = $context;
            }
        }
        return $result;
    }

    /**
     * 内网IP
     * @param $ip
     * @return bool
     */
    public  static  function isneiwang($ip) {
        $i = explode('.', $ip);
        if ($i[0] == 10) return true;
        if ($i[0] == 172 && $i[1] > 15 && $i[1] < 32) return true;
        if ($i[0] == 192 && $i[1] == 168) return true;
        return false;
    }


    /**
     * 发送get请求
     * @param string $url 请求地址
     * @param array $post_data post键值对数据
     * @return string
     */
    public  static function getUrl($url,$params =[]) {
        $postdata = http_build_query($params);
        if(strpos($url,'?') !== false)
            $url .='&'.$postdata;
        else
            $url .= '?'.$postdata;
        $options = array(
            'http' => array(
                'method' => 'GET',
                'header' => 'Content-type:application/x-www-form-urlencoded',
//                'content' => $postdata,
                'timeout' => 15 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }

    /**
     * 发送post请求
     * @param string $url 请求地址
     * @param array $post_data post键值对数据
     * @return string
     */
    public  static function send_post($url, $post_data) {
        $postdata = http_build_query($post_data);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postdata,
                'timeout' => 30 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return $result;
    }

    /**
     * 发送delete请求
     * @param string $url 请求地址
     * @param array $post_data post键值对数据
     * @return string
     */
    public  static function send_delete($url, $params=[]) {
        $postdata = http_build_query($params);
        $url .= '?'.$postdata;
        $options = array(
            'http' => array(
                'method' => 'DELETE',
                'header' => 'Content-type:application/x-www-form-urlencoded',
//                'content' => $postdata,
                'timeout' => 15 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }

    public static function Log($k,$v)
    {
        $model = new Log();
        $model->key = $k;
        $model->val = $v;
        $model->create_time = date('Y-m-d h:i:s');
        $model->save();
        $myfile = fopen("/tmp/log.log", "a+");
        $txt = $k.":".$v."\n";
        fwrite($myfile, $txt);
        fclose($myfile);
    }

    // 过滤掉emoji表情
    public static function filterEmoji($str)
    {
        $str = preg_replace_callback(
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $str);

        return $str;
    }

    public static function jumpUrl($url)
    {
        header("Location: $url");
    }

    public static function middleOmitted($str,$len)
    {
        if(strlen($str)>$len)
        {
            return  substr($str,0,$len/2).'...'.substr($str,-$len/2);
        }else
            return $str;
    }


    /**
     * 获取用户输入的数据
     * @return array|mixed
     */
    public static function requestData()
    {
        $data = new \stdClass();
        $request = Yii::$app->request;
        if($request->isPost){

            if(is_array(json_decode($request->rawBody, true))){
                $data = json_decode($request->rawBody, true);
            } else {
                $data = $request->post();
            }
        } else if($request->isGet) {
            $data = $request->get();
        }

        return $data;
    }

    /**
     * @param $key
     * @param string $default
     * @return string
     */
    public static function getJsonParam($key, $default = ""){
        $requestData = self::requestData();
        return array_key_exists($key, $requestData) ? $requestData[$key] : $default;
    }

    /**
     * @param $key
     * @param string $default
     * @return string
     */
    public  static function getJsonParamErr($key, $default = "",$tips=""){
        $requestData = self::requestData();
        $value = array_key_exists($key, $requestData) ? $requestData[$key] : $default;
        if(!is_array($value) && strlen($value)<1){
            if(empty($tips))self::errorOut("{$key}上传错误");
            else self::errorOut($tips);
            die();
        }else if(is_array($value) && count($value)<1){
            if(empty($tips))self::errorOut("{$key}上传错误");
            else self::errorOut($tips);
            die();
        }
        return $value;
    }

    /**
     * json输出错误信息
     * @param $message
     * @param int $status
     * @return json
     */
    public static function errorOut($message = 203, $status = '错误')
    {
        $out['error'] = 255;
        $out['status']     = $status;
        $out['message']    = $message;
        return self::jsonOut($out);
    }

    /**
     * json输出成功信息
     * @param $message
     */
    public static function successOut($message = MSG200,$status = 200,$showtoast="")
    {
        $out['status']     = $status;
        $out['message']    = $message;
        $out['showtoast']  = $showtoast;

        return self::jsonOut($out);
    }

    /**
     * 数组排序
     * @param $arr
     * @param $keys
     * @param string $orderby
     * @return array
     */

    public static function array_sort($arr,$keys=null,$orderby='asc'){
        $keysvalue = $new_array = array();
        if(!$keys)
        {
            $keysvalue = array_keys($arr);
        }else{
            foreach ($arr as $k=>$v){
                $keysvalue[$k] = $v[$keys];
            }
        }

        if($orderby== 'asc'){
            asort($keysvalue);
        }else{
            arsort($keysvalue);
        }

        reset($keysvalue);
        foreach ($keysvalue as $k=>$v){
            $new_array[$v] = $arr[$v];
        }
        return $new_array;
    }

    /**
     * 检查数据是否为空,空数据跳出
     * @param type $data
     * @param type $message
     * @param type $status
     * @return boolean
     */

    public static function checkData($data,$message='OK',$status = OTHER_ERR){
        try{
            if(is_array ($data)){

                if (key_exists ("code", $data) ){
                    $message    = $data['msg'];
                    $status     = $data['code'];

                }elseif (key_exists ("total", $data) && $data['total'] == 0){
                    self::errorOut($message , $status);
                    die();

                }else{

                    if(!empty($data)){

                        return true;
                    }else{

                        $status = MV_SUCCESS;
                    }
                }
            }else{

                if(!empty($data)) return true;
            }

        }  catch (Exception $e){
            self::errorOut();
            die();
        }
        self::errorOut($message , $status);
        die();
    }

    /**
     * json输出数据
     * @param array $data
     * @return json
     */
    public static function dataJsonOut($data=[],$message = 'ok',$errno = 200)
    {
        $out_std = $out_data =[];
        $out_std['code'] = $errno;
        $out_std['status'] = $errno;
        $out_std['message'] = $message;
        $out_data['data'] = $data;
        return self::JsonOut(array_merge($out_std, $out_data));
    }

    public static function AddUrToken($out,$token,$source,$lat=0,$lng=0)
    {
        if (is_array($out)) {
            foreach ($out as $k => $v) {
                if(is_array($v))
                {
                    $out[$k] = self::AddUrToken($out[$k],$token,$source);
                }
                else if($k=='url')
                {
                    if(strstr($v, 'ant-step')&&strpos($v, "www.ant-step")<10)
                    {
                        $out[$k]=$v."?urtoken=$token"."&source=".$source;
                        if($lat>0 && $lng>0)
                        {
                            $out[$k].="&lat=$lat&lng=$lng";
                        }
                    }
                }
                else {

                }
            }
        }
        return $out;
    }

    /**
     * 产生订单号
     * @return string
     */
    public static function makeOrderCode()
    {
        return date('YmdHis').rand(100000,999999);
    }

    /**
     * 产生验证码
     * @param int $n
     * @return string
     */
    public static function makeRandomNum($n=16)
    {
        $str = "0123456789";   //   输出字符集
        // n  输出串长度
        $len = strlen($str)-1;
        $s = '';
        for($i=0 ; $i<$n; $i++){
            $s .=  $str[rand(0,$len)];
        }
        return $s;
    }

    /**
     * 产生字符集
     * @param int $n
     * @return string
     */
    public static function makeRandomStr($n=16)
    {
        $s = '';
        $str = "0123456789abcdefghijklmnopqrstuvwxyz";   //   输出字符集
        // n  输出串长度
        $len = strlen($str)-1;
        for($i=0 ; $i<$n; $i++){
            $s .=  $str[rand(0,$len)];
        }
        return $s;
    }

    public static function decodeGID($gtoken)
    {
        if(empty($gtoken))
            return;

        $n = self::decodeStr($gtoken);
        $s = substr($n,0,1);
        $gid = substr($n,1,$s);
        $urid = substr($n,$s+1);
        if($gid && $urid)
            return ['gid'=>$gid,'urid'=>$urid];
        else
            return ;
    }

    public static function encodeGID($gid,$urid)
    {
        $n = strlen($gid).$gid.$urid;
        return self::encodeStr($n);
    }

    /*
     * 订单编号
     */
    public static function makeOrderNo()
    {
        return date('YmdHis').rand(100,999)+0;
    }

    /**
     * 产生唯一码
     * @param $phone
     * @return string
     */
    public static function makeProductId()
    {
        return time().rand(10,99);
        $url = 'https://www.iteblog.com/api/mobile.php?mobile='.$phone;
        $data = Tools::getUrl($url);
        if($data)
        {
            $pageArray = json_decode($data,true);
            if($pageArray)
            {
                $city_code = $pageArray['city_code'].'';
                if(strlen($city_code)>4)
                {
                    $city_code = substr($city_code,strlen($city_code)-4,4);
                }else{
                    $font = '';
                    for($i=4-strlen($city_code.'');$i>0;$i--)
                    {
                        $font .='0';
                    }
                    $city_code = $font.$city_code;
                }
                $ordercount = ghOrderDetailMgt::find()->andFilterWhere(['curid'=>$shopid])->andFilterWhere(['>=','ustate',3])->count();
                $num = '';
                if($id)
                {
                    for($i=4-strlen($id.'');$i>0;$i--)
                    {
                        $num ='0'.$num;
                    }
                    $num = $num.$id;
                }else{
                    for($i=4-strlen(($ordercount+1).'');$i>0;$i--)
                    {
                        $num ='0'.$num;
                    }
                    $num = $num.($ordercount+1);
                }
                $city_code = $city_code.date('ym').$num;
                return $city_code;
            }
        }
    }

    /**
     * $type : 1- 获取短地址
     * @author: vfhky 20130304 20:10
     * @description: PHP调用新浪短网址API接口
     * @reference: http://t.cn/8FgeBI2
     * @param string $type: 非零整数代表长网址转短网址,0表示短网址转长网址
     */
    public  static function  xlUrlAPI($type,$url){
        /* 这是我申请的APPKEY，大家可以测试使用 */
        $key = '569452181';
        if($type)
            $baseurl = 'http://api.t.sina.com.cn/short_url/shorten.json?source='.$key.'&url_long='.$url;
        else
            $baseurl = 'http://api.t.sina.com.cn/short_url/expand.json?source='.$key.'&url_short='.$url;
        $strRes = self::getUrl($baseurl);
        $arrResponse=json_decode($strRes,true);
        if (isset($arrResponse->error) || !isset($arrResponse[0]['url_long']) || $arrResponse[0]['url_long'] == '')
            return 0;
        if($type)
            return $arrResponse[0]['url_short'];
        else
            return $arrResponse[0]['url_long'];
    }


    /**
     * 加密2个参数
     * @param $sid
     * @param null $cid
     * @return string
     */
    public static function makeEncode2Params($op='encode',$sid,$cid=null)
    {
        if($op == 'encode')
        {
            $zero = '';
            if(strlen($sid) <10 )
                $zero = '0';
            $t = rand(1,9).$zero.strlen($sid).$sid.$cid;
            $t = Tools::encodeNum($t);
            return $t;
        }else if($op =='decode')
        {
            $nu = Tools::decodeNum($sid);
            if($nu>0)
            {
                $pidnum = substr($nu,1,2);
                $pidnum = intval($pidnum);
                $sid = substr($nu,3,$pidnum);
                $cid = substr($nu,($pidnum+3));
                return [$sid,$cid];
            }
        }
    }
}