<?php

namespace common\components;
use Yii;

class Helper
{
    /*
     * 下划线转驼峰
     */
    static function convertUnderline($str)
    {
        $str = preg_replace_callback('/([-_]+([a-z]{1}))/i',function($matches){            
            return strtoupper($matches[2]);
        },$str);
            return ucfirst($str);
    }
    
    /*
     * 驼峰转下划线
     */
    static function humpToLine($str){
        $str = preg_replace_callback('/([A-Z]{1})/',function($matches){
            return '_'.strtolower($matches[0]);
        },$str);
            return $str;
    }
    
    /**
     * 方法:isdate()
     * 功能:判断日期格式是否正确
     * 参数:$str 日期字符串 $format日期格式
     * 返回:布儿值
     */
    static function isdate($str,$format="Y-m-d"){
        $strArr = explode("-",$str);
        if(empty($strArr)){
            return false;
        }
        foreach($strArr as $val){
            if(strlen($val)<2){
                $val="0".$val;
            }
            $newArr[]=$val;
        }
        $str =implode("-",$newArr);
        $unixTime=strtotime($str);
        $checkDate= date($format,$unixTime);
        if($checkDate==$str)
            return true;
        else
            return false;
    }
    /**
     * @author  lito
     * @since   V1.5.6
     * @version V1.5.6
     * @ctime 2016年8月11日 下午3:35:38
     * @utime 2016年8月11日 下午3:35:38
     **/
    public  static function dump($data, $echo = true, $label = null, $strict = true) {
        $label = ($label === null) ? '' : rtrim($label) . ' ';
        if (!$strict) {
            if (ini_get('html_errors')) {
                $output = print_r($data, true);
                $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
            } else {
                $output = $label . print_r($data, true);
            }
        } else {
            ob_start();
            var_dump($data);
            $output = ob_get_clean();
            if (!extension_loaded('xdebug')) {
                $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
                $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
            }
        }
        if ($echo) {
            echo($output);
            return null;
        } else
            return $output;
    }
   /**
     * 判断是否测试服务器
     * @author Eagle
     * @return boolean
     */
    public static function isAlpha(){
            if(strstr($_SERVER['HTTP_HOST'], 'alpha')||strstr($_SERVER['HTTP_HOST'], 'lito')||strstr($_SERVER['HTTP_HOST'], 'uesca')||strstr($_SERVER['HTTP_HOST'], 'mpms.com')){
                    return true;
            }else{
                    return false;
            }
     }
    
    /**
     * 当前GPS坐标对应地址
     * @author lito
     * @since V1.5.3
     * @version V1.5.3
     * @ctime 2016年7月27日 上午11:14:44
     * @utime 2016年7月27日 上午11:14:44
     * @return array
     **/
    static function getAddressBygps($gps="116.310003,39.991957")
    {
        $gaodekey   =  self::getSystemParams("gaodewebkey");
        $url =   "http://restapi.amap.com/v3/geocode/regeo?output=xml&location={$gps}&key={$gaodekey}";
        $simple = file_get_contents($url);
        $p = xml_parser_create();
        xml_parse_into_struct($p, $simple, $vals, $index);
        xml_parser_free($p);
        return $vals[5]['value'];
    }
    /**
     * 通过GPS坐标获取adcode
     * @author   lito
     * @since    V1.5.6
     * @version  V1.5.6
     * @ctime 2016年8月9日 上午11:29:29
     * @utime 2016年8月9日 上午11:29:29
     **/
    static function getAdcodeByGps($gps="116.310003,39.991957")
    {
        $gaodekey   =   self::getSystemParams("gaodejskey");
        $url ="http://restapi.amap.com/v3/geocode/regeo?output=xml&location={$gps}&key={$gaodekey}&radius=1000&extensions=all";
        $simple = @file_get_contents($url);
        $p = xml_parser_create();
        xml_parse_into_struct($p, $simple, $vals, $index);
        xml_parser_free($p);
        return $vals['12']['value'];
    }
    
    /**
     * @author lito
     * @since    V1.5.6
     * @version V1.5.6
     * @ctime 2016年8月18日 下午6:28:43
     * @utime 2016年8月18日 下午6:28:43
     **/
    public static function  getSystemParams($key)
    {
        if(empty($key)) return [];
        
        if(self::isAlpha())
        {
            return Yii::$app->params["alpha_".$key];
        }else{
            return Yii::$app->params[$key];
        }
    }
    /**
     * createPath
     * @param string $targetFolder
     * @return string
     */
    public static function createPath($targetFolder) {
        if (!is_dir($targetFolder)) {
            $dirs = explode('/', $targetFolder);
            $num = count($dirs);
            for ($i = 1, $dir = $dirs[0]; $i < $num; $i++) {
                $dir = $dir . '/' . $dirs[$i];
                if (!is_dir($dir)) {
                    mkdir($dir);
                    chmod($dir, 0755);
                }
            }
        }
    }
    /**
     * 将数组中int类型转换为str
     * @author lito
     * @since V1.5
     * @version V1.5
     * @ctime 2016-5-5 下午3:25:54
     * @utime 2016-5-5 下午3:25:54
     * @param
     * @return array
     **/
    static function  foreach_array_inttostr($array)
    {
        if(is_array($array))
        {
            foreach($array as $key=>$v)
            {
                if(is_array($v))
                {
                    self::foreach_array_inttostr($v);
                }else{
                    if(is_int($v))
                    {
                        $array[$key]	=	(string)$v;
                    }
    
                    if(!isset($v))
                    {
                        $array[$key]	=	'0';
                    }
                }
            }
        }
        return $array;
    }
    /**
     * 字符串截取
     * @author lito
     * @since    V1.5.6
     * @version V1.5.6
     * @ctime 2016年9月28日 上午9:35:22
     * @utime 2016年9月28日 上午9:35:22
     **/
    static function str_cut($string, $length, $dot = '...') {
        $CHARSET = \Yii::$app->charset;
        $strlen = strlen($string);
        if($strlen <= $length) return $string;
        $string = str_replace(array(' ','&nbsp;', '&', '"', '\'','“', '”', '—', '<', '>', '·', '…'), array(' ',' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
    $strcut = '';
    if(strtolower($CHARSET) == 'utf-8') {
        $length = intval($length-strlen($dot)-$length/3);
        $n = $tn = $noc = 0;
        while($n < strlen($string)) {
            $t = ord($string[$n]);
            if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1; $n++; $noc++;
            } elseif(194 <= $t && $t <= 223) {
                $tn = 2; $n += 2; $noc += 2;
            } elseif(224 <= $t && $t <= 239) {
                $tn = 3; $n += 3; $noc += 2;
            } elseif(240 <= $t && $t <= 247) {
                $tn = 4; $n += 4; $noc += 2;
            } elseif(248 <= $t && $t <= 251) {
                $tn = 5; $n += 5; $noc += 2;
            } elseif($t == 252 || $t == 253) {
                $tn = 6; $n += 6; $noc += 2;
            } else {
            $n++;
        }
        if($noc >= $length) {
        break;
        }
    }
    if($noc > $length) {
        $n -= $tn;
    }
    $strcut = substr($string, 0, $n);
    $strcut = str_replace(array('∵', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), array(' ', '&', '"', '\'', '“', '”', '—', '<', '>', '·', '…'), $strcut);
    }
            else {
            $dotlen = strlen($dot);
            $maxi = $length - $dotlen - 1;
            $current_str = '';
            $search_arr = array('&',' ', '"', "'", '“', '”', '—', '<', '>', '·', '…','∵');
            $replace_arr = array('&','&nbsp;', '"', '\'', '“', '”', '—', '<', '>', '·', '…',' ');
        $search_flip = array_flip($search_arr);
        for ($i = 0; $i < $maxi; $i++) {
            $current_str = ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
            if (in_array($current_str, $search_arr)) {
                $key = $search_flip[$current_str];
                $current_str = str_replace($search_arr[$key], $replace_arr[$key], $current_str);
            }
            $strcut .= $current_str;
        }
    }
    return $strcut.$dot;
    }
    /**
     * 判断url是否有效
     * @author lito
     * @since V1.6
     * @version V1.6
     * @ctime 2016年5月20日 下午3:15:45
     * @utime 2016年5月20日 下午3:15:45
     * @param
     * @return array
     * */
    static function url_exists($url) {
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        //设置超时
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code == 200) {
            return true;
        }
        return false;
    }
    
    private static function formatLoc($points){
    
    
        if(empty($points))  return false;
    
        $rc = 6378137;
        $rj = 6356725;
    
        $lodeg =(int)$points['lng'];
        $lomin =(int)(($points['lng']-$lodeg)*60);
        $losec =($points['lng']-$lodeg-$lomin/60)*3600;
    
        $ladeg =(int)$points['lat'];
        $lamin =(int)(($points['lat']-$ladeg)*60);
        $lasec =($points['lat']-$ladeg-$lamin/60)*3600;
         
        $data['radlo'] = $points['lng'] * M_PI/180;
        $data['radla'] = $points['lat'] * M_PI/180;
    
        $data['ec'] = $rj+($rc-$rj)*(90-$points['lat'])/90;
        $data['ed'] = $data['ec'] * cos($data['radla']);
    
    
        return $data;
    
    }
    
    /**
     * 根据2个经纬度计算角度
     * 
     * @auther Eagle
     * @since V2.0
     * @version V2.0
     * @ctime 2016-11-11 14:23:13
     * @utime 2016-11-11 14:23:14
     * @param $pa 新点 数组 lat lng
     * @return 成功返回数据,异常返回fasle或null
     **/
    public static function getAngel($pa,$pb){
    
        if(empty($pa) || empty($pb) )       return false;
         
        $spa = self::formatLoc($pa);
        $spb = self::formatLoc($pb);
    
        $dx = ($spb['radlo'] - $spa['radlo']) * $spa['ed'];
        $dy = ($spb['radla'] - $spa['radla']) * $spa['ec'];
    
        if($dy == 0) return false;
    
        $angle = atan(abs($dx/$dy))*180/M_PI;
        $dlo = $pb['lng'] - $pa['lng'];
        $dla = $pb['lat'] - $pa['lat'];
    
        if($dlo>0 && $dla<=0){
            $angle = (90 - $angle) + 90;
        }
        else if($dlo<=0 && $dla<0){
            $angle = $angle+180;
        }else if($dlo<0 && $dla>=0){
            $angle = (90-$angle)+270;
        }
    
        return $angle;
    }
    /**
     * 判断是否是微信浏览器
     * @author lito
     * @ctime 2016-2-27 下午8:01:42
     * @utime 2016-2-27 下午8:01:42
     * @param $_SERVER
     * @return bool
     * */
    static function isWechat() {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }
    
    /**
     * @author lito
     * @since V1.5
     * @version V1.5
     * @ctime 2016-4-27 上午9:30:28
     * @utime 2016-4-27 上午9:30:28
     * @param
     * @return array
     * */
    static function isMobile() {
        //如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($_SERVER['HTTP_VIA'])) {
            //找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        //脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array(
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            );
            //从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        //协议法，因为有可能不准确，放到最后判断
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            //如果只支持wml并且不支持html那一定是移动设备
            //如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }
    
    static public function getMathctypeName($matchtype)
    {
        switch($matchtype)
        {
            case MATCHTYPE_LOVE:
                $matchtype  =   MATCHTYPE_LOVE_NAME;
                break;
            case MATCHTYPE_BOSS:
                $matchtype  =   MATCHTYPE_BOSS_NAME;
                break;
            case MATCHTYPE_G100:
                $matchtype  =   MATCHTYPE_G100_NAME;
                break;
        }
        return $matchtype;
        
    }

  
   /**
    * 生成成绩证书
    * @param type $sourcepic
    * @param type $destpic
    * @param type $txtarr
    * @return type
    */ 
   public static function addText2Image($sourcepic,$destpic,$txtarr){
        $SourceImgInfo  = @getimagesize($sourcepic);
        
        
        $sourceRes      = self::createImgSource($SourceImgInfo[2] , $sourcepic);
        $font = CERTIFICATE_FONT;
        
        if(! file_exists ($font))  return false;
        
        foreach($txtarr as $k=>$v){
            $text   = self::charsetEncode( $v['txt'],"GB2312", "GB2312");
            $color  = imagecolorallocate($sourceRes['source'], $v['color'][0],$v['color'][1],$v['color'][2]);
            imagettftext($sourceRes['source'], $v['fz'], 0, $v['sx'], $v['sy'], $color, $font, $text);      
        }
         
        self::saveBmpImg2File($sourceRes['format'] , $sourceRes['source'] , $destpic); 
        
        imagedestroy($sourceRes['source']);
        return $destpic;
    }
    
    /**
     * 字符串格式化
     * @param type $input
     * @param type $_output_charset
     * @param type $_input_charset
     * @return type
     */
    private static function charsetEncode($input,$_output_charset ,$_input_charset) {
	$output = "";
	if(!isset($_output_charset) )$_output_charset  = $_input_charset;
	if($_input_charset == $_output_charset || $input ==null ) {
		$output = $input;
	} elseif (function_exists("mb_convert_encoding")) {
		$output = mb_convert_encoding($input,$_output_charset,$_input_charset);
	} elseif(function_exists("iconv")) {
		$output = iconv($_input_charset,$_output_charset,$input);
	} else die("sorry, you have no libs support for charset change.");
	return $output;
    }
    
     /**
     * 获取图片格式
     * @param type $type
     * @param type $sourcepic
     */
    private static function  createImgSource($type,$sourcepic){
        
        $data =[];
        switch ($type)
        {
            case 1:
                    $data['source'] = imagecreatefromgif($sourcepic);
                    $data['format']   = 'gif';
                    break;
            case 2:
                    $data['source'] = imagecreatefromjpeg($sourcepic);
                    $data['format'] = 'jpg';
                    break;
            case 3:
                    $data['source'] = imagecreatefrompng($sourcepic);
                    $data['format'] = 'png';
                    break;
            default:
                die('不支持的图片文件类型');
                exit;
        }
        
        return $data;
        
    }
    /**
     * 保持bmp到图片
     * @param type $format
     * @param type $SourceImage
     * @param type $dest
     * @return boolean
     */
    private static function saveBmpImg2File($format,$SourceImage, $dest){
        switch ($format)
        {
                case 'jpg':
                        imagejpeg($SourceImage, $dest);
                        break;
                case 'png':
                        imagepng($SourceImage, $dest);
                        break;
                case 'gif':
                        imagegif($SourceImage, $dest);
                        break;
                default:
                        imagejpeg($SourceImage, $dest);
                        break;
        }
        return  true;
    }
    /*
     * 此函数不能再iis下工作，但是效率比较高
     * @return string user IP address
     */
    public  static function getUserHostAddressNoIIS() {
        switch (true) {
            case isset($_SERVER["HTTP_X_FORWARDED_FOR"]):
                $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                break;
            case isset($_SERVER["HTTP_CLIENT_IP"]):
                $ip = $_SERVER["HTTP_CLIENT_IP"];
                break;
            default:
                $ip = $_SERVER["REMOTE_ADDR"] ? $_SERVER["REMOTE_ADDR"] : '127.0.0.1';
        }
        if (strpos($ip, ', ') > 0) {
            $ips = explode(', ', $ip);
            $ip = $ips[0];
        }
        // 检查ip地址
        if (! preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/',$ip)) {
            $ip =   '127.0.0.1';
        }
        return $ip;
    }
    /**
     * 秒转成时分秒
     */
    public static function formatSecondsToHms($seconds){
        $plus = "";
        if($seconds<0)
            $plus = "- ";
        $seconds = abs($seconds);
        if ($seconds > 3600) {
            $hours = intval($seconds / 3600);
            $minutes = $seconds % 3600;
            $time = $plus.$hours . ":" . gmstrftime('%M:%S', $minutes);
        } else {
            $time = $plus.gmstrftime('%H:%M:%S', $seconds);
        }
        return $time;
    }
    /**
     * 生成外部使用的url
     * @author  Lito
     * @time 2017年1月22日 下午3:01:39
     **/
    public static  function getOuturl($action,$params,$type="base64")
    {
        $params['timestamp']    =   time();
        $hostUrl    =   self::getSystemParams('host_name');
        switch(strtoupper($type))
        {
            case "BASE64":
                $paramsstr  =   json_encode($params);
                $code   =   base64_encode($paramsstr);
                break;
            case 'BASE64PARAM':
                $params['checksum'] =   self::getChecksum($params,0,2); 
                $paramsstr  =   json_encode($params);
                $code   =   base64_encode(base64_encode($paramsstr));
                break;
                
        }
        return self::getShortUrl($hostUrl.$action."?code=".$code);
        //$this->
    }
    /**
     * url 加密后解密
     * @date: 2017年4月13日 上午10:13:43
     * @author: lito
     * @param: variable
     * @return:bool or array
    */
    public static function moveurldecrypt($checksum,$type='BASE64PARAM')
    {
         switch(strtoupper($type))
         {
                case 'BASE64PARAM':
                    $result =   json_decode(base64_decode(base64_decode($checksum)),true);
                    $code   =   self::getChecksum($result,0,2);
                    if($code==$result['checksum'])
                    {
                        return $result;
                    }                        
                    
                
                    return false;
                    break;
                    
        }
        return false;
    }
    /**
     * 生成CHECKSUM
     * @date: 2017年4月13日 上午10:37:16
     * @author: lito
     * @param: variable
     * @return string or bool
    */
    public static  function getChecksum($array,$offset,$len)
    {
        
        return  md5(json_encode(array_slice($array,$offset,$len)).MOVE_URL_KEY); 
    } 
    
    
    public static function isBossrun()
    {
        $roles  =   Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
        if(!empty($roles[BOSS_MANAGER]))
        {
            return 1;
        }else{
            return 0;
        }
    }
    
    
    
    public  static function getMatchtype()
    {
        $matchtype  =   Yii::$app->request->get('matchtype');
        $matchtype  =   $matchtype?$matchtype:Yii::$app->session->get('matchseriall')['matchtype'];
        return $matchtype;
    }
        
    
    /**
     * 获取短网址，如果获取失败，返回空字符串
     * @author Charles Zhu
     * @since v1.4
     * @version v1.4
     * @ctime 2016-3-23 11:57:34
     * @utime 2016-3-23 11:57:38
     * @param string 原始网址
     * @return string 短网址
     */
    public static function getShortUrl($originUrl){
        $ch = curl_init();
        $url = 'http://apis.baidu.com/3023/shorturl/shorten?url_long=' . urlencode($originUrl);
        // 到这里获取http://apistore.baidu.com/astore/usercenter
        $header = ['apikey:dafa3d41a97e28b082c5f756824ad097'];
        // 添加apikey到header
        curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 执行HTTP请求
        curl_setopt($ch , CURLOPT_URL , $url);
        $res = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($res, TRUE);
        $error = json_last_error();
        if($error !== JSON_ERROR_NONE){
            return '';
        }
        if(empty($res['urls'][0]['url_short'])){
            return '';
        }
        return $res['urls'][0]['url_short'];
    }
    
    
   static  function validation_filter_id_card($id_card){
       $id_card =   trim($id_card, "\xC2\xA0");
       $id_card =   trim($id_card);
        if(mb_strlen($id_card)==18){
            return self::idcard_checksum18($id_card);
        }elseif((mb_strlen($id_card)==15)){
            $id_card=self::idcard_15to18($id_card);
            return self::idcard_checksum18($id_card);
        }else{
            return false;
        }
    }
    // 计算身份证校验码，根据国家标准GB 11643-1999
    static public function idcard_verify_number($idcard_base){
        
        
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
    // 将15位身份证升级到18位
    static public function idcard_15to18($idcard){
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
    // 18位身份证校验码有效性检查
    static public function idcard_checksum18($idcard){
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
    
    
    // PHP根据身份证号，自动获取对应的星座函数
    function get_xingzuo($cid) {
        // 根据身份证号，自动返回对应的星座
        if (!self::validation_filter_id_card($cid)) return '';
        $bir = substr($cid,10,4);
        $month = (int)substr($bir,0,2);
        $day = (int)substr($bir,2);
        $strValue = '';
        if(($month == 1 && $day <= 21) || ($month == 2 && $day <= 19)) {
            $strValue = "水瓶座";
        }else if(($month == 2 && $day > 20) || ($month == 3 && $day <= 20)) {
            $strValue = "双鱼座";
        }else if (($month == 3 && $day > 20) || ($month == 4 && $day <= 20)) {
            $strValue = "白羊座";
        }else if (($month == 4 && $day > 20) || ($month == 5 && $day <= 21)) {
            $strValue = "金牛座";
        }else if (($month == 5 && $day > 21) || ($month == 6 && $day <= 21)) {
            $strValue = "双子座";
        }else if (($month == 6 && $day > 21) || ($month == 7 && $day <= 22)) {
            $strValue = "巨蟹座";
        }else if (($month == 7 && $day > 22) || ($month == 8 && $day <= 23)) {
            $strValue = "狮子座";
        }else if (($month == 8 && $day > 23) || ($month == 9 && $day <= 23)) {
            $strValue = "处女座";
        }else if (($month == 9 && $day > 23) || ($month == 10 && $day <= 23)) {
            $strValue = "天秤座";
        }else if (($month == 10 && $day > 23) || ($month == 11 && $day <= 22)) {
            $strValue = "天蝎座";
        }else if (($month == 11 && $day > 22) || ($month == 12 && $day <= 21)) {
            $strValue = "射手座";
        }else if (($month == 12 && $day > 21) || ($month == 1 && $day <= 20)) {
            $strValue = "魔羯座";
        }
        return $strValue;
    }
    function get_shengxiao($cid) {
        //根据身份证号，自动返回对应的生肖
        if(!self::validation_filter_id_card($cid)) return '';
        $start = 1901;
        $end = $end = (int)substr($cid,6,4);
        $x = ($start - $end) % 12;
        $value = "";
        if($x == 1 || $x == -11){
            $value = "鼠";
        }
        if($x == 0) {
            $value = "牛";
        }
        if($x == 11 || $x == -1){
            $value = "虎";
        }
        if($x == 10 || $x == -2){
            $value = "兔";
        }
        if($x == 9 || $x == -3){
            $value = "龙";
        }
        if($x == 8 || $x == -4){
            $value = "蛇";
        }
        if($x == 7 || $x == -5){
            $value = "马";
        }
        if($x == 6 || $x == -6){
            $value = "羊";
        }
        if($x == 5 || $x == -7){
            $value = "猴";
        }
        if($x == 4 || $x == -8){
            $value = "鸡";
        }
        if($x == 3 || $x == -9){
            $value = "狗";
        }
        if($x == 2 || $x == -10){
            $value = "猪";
        }
        return $value;
    }
    static function getSexBycard($cid) {
        $cid =   trim($cid);
        //根据身份证号，自动返回性别
        if(!self::idcard_checksum18($cid)) return false;
        $sexint = (int)substr($cid,16,1);
        return $sexint % 2 === 0 ? 2 : 1;
    }
    
    static function getIDCardInfo($IDCard){ 
     $IDCard =   trim($IDCard);
     if(strlen($IDCard)==18)
     {
         $tyear=intval(substr($IDCard,6,4));
         $tmonth=intval(substr($IDCard,10,2));
         $tday=intval(substr($IDCard,12,2));
     }
     elseif(strlen($IDCard)==15)
     {
         $tyear=intval("19".substr($IDCard,6,2));
         $tmonth=intval(substr($IDCard,8,2));
         $tday=intval(substr($IDCard,10,2));
     }else{
         $result['birth']   =0;
         $result['age']     =0;
         $result['sex']     =0;
         return $result;
     }
      
     $result['birth']   =    $tyear."-".$tmonth."-".$tday;
     $result['age']     =   self::birth2Age($result['birth']);
     $result['sex']     =   self::getSexBycard($IDCard);
     return $result;
    }
        
    
    static  function moverand($len=5)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;
        
        for($i=0;$i<$len;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        
        return $str;
    }
    
     /**
     * 毫秒转成:时:分:秒
     * @param type $second
     * @return string
     */
    public static function msecond2HMS($time,$format=''){
        
        if(is_numeric($time)){
            $time = $time/1000;
            
            $value = array(
              "hours" => "00","minutes" => "00", "seconds" => "00",
            );
            if($time >= 3600){
              $hour             = floor($time/3600);
              $value["hours"]   = $hour < 10 ? "0$hour" : $hour;

              $time = ($time%3600);

            }
            if($time >= 60){
              $minutes          = floor($time/60);
              $value["minutes"]   = $minutes < 10 ? "0$minutes" : $minutes;

              $time = ($time%60);
            }
            if($time > 0){
                $seconds            = floor($time);
                $value["seconds"]   = $seconds < 10 ? "0$seconds" : $seconds;

            }

            $t="";
            if($value["hours"] )
                $t.=$value["hours"]  .":";
            if($value["minutes"] )
                $t.=$value["minutes"]  .":";
            if($value["seconds"] )
                $t.=$value["seconds"] ;
            switch(strtoupper($format))
            {
                case "ARRAY":
                    return $value;
                 default:
                    return $t;   
            }
        }else{
            return "";
        }
    }
    
    /**
     * pc端跳转新页面，wap端子页面加载
     */
    public static function ifJumpToNewPage(){
        if( self::isMobile() ){
            return "";
        }else{
            return " target='_blank'";
        }
    }
            
            
     /**
         * 生日转年龄
         * @param type $birthday
         * @return boolean|int
         */
    public static function birth2Age($birthday){
        $age = strtotime($birthday); 
        if(!$age){ 
          return false; 
        } 
        list($y1,$m1,$d1) = explode("-",date("Y-m-d",$age)); 
        $now = strtotime("now"); 
        list($y2,$m2,$d2) = explode("-",date("Y-m-d",$now)); 
        $age = $y2 - $y1; 
        if((int)($m2.$d2) < (int)($m1.$d1)) 
          $age -= 1;


        if($age<0) $age=0;

        return $age; 
    }
    
    public static function getDocumentpreview($file)
    {
        $filearr    =   explode('.', $file);
        $key   =   end($filearr);
        $ext    =   [
            'doc'=> '<i class="fa fa-file-word-o text-primary"></i>',
            'docx'=> '<i class="fa fa-file-word-o text-primary"></i>',
            'xls'=> '<i class="fa fa-file-excel-o text-success"></i>',
            'xlsx'=> '<i class="fa fa-file-excel-o text-success"></i>',
            'ppt'=> '<i class="fa fa-file-powerpoint-o text-danger"></i>',
            'pdf'=> '<i class="fa fa-file-pdf-o text-danger"></i>',
            'zip'=> '<i class="fa fa-file-archive-o text-muted"></i>',
            'htm'=> '<i class="fa fa-file-code-o text-info"></i>',
            'txt'=> '<i class="fa fa-file-text-o text-info"></i>',
            'mov'=> '<i class="fa fa-file-movie-o text-warning"></i>',
            'mp3'=> '<i class="fa fa-file-audio-o text-warning"></i>',
            'jpeg'=> '<i class="fa fa-file-photo-o text-danger"></i>',
            'jpg'=> '<i class="fa fa-file-photo-o text-danger"></i>',
            'gif'=> '<i class="fa fa-file-photo-o text-muted"></i>',
            'png'=> '<i class="fa fa-file-photo-o text-primary"></i>'
        ];
        return ($ext[$key])?$ext[$key]:'<i class="fa fa-file text-primary"></i>';
        
    }
    
    /**
     * ascii 参数加密
     * @param type $str
     */
    public static function asciiParamsEncode($str){
        $length = strlen($str);
    
        $newstr = '';
        for($i=0;$i<$length;$i++){ //每个字符转成ascii编码的16进制
            $newstr .= dechex( ord ( $str[$i] )) ;
        }
    
        $str_rand  = dechex(rand(16, 255));//前缀2位随机数字,转成16进制 存0-1字节
    
        $rp = rand(0, strlen ($newstr)-1);//从字符串中随机替换取1位
        $str_reppos = $rp < 16 ?'0'.dechex($rp) : dechex($rp);//随机替换位转成16进制存4-5字节
    
        $str_rep = substr($newstr , $rp ,1);// 被替换的内容取1位放到第6个字节
    
        $str_str = substr_replace($newstr , '7' ,$rp, 1);//替换随机字符为7,存放第7-?字节
    
        $strlength  = strlen($str_str);//计算字符串的长度,转成16进制 存2-3字节
        $str_length = $strlength < 16 ?'0'. dechex($strlength) : dechex($strlength);
    
        return $str_rand.$str_length.$str_reppos.$str_rep.$str_str;
    
    }
    
    /**
     * scii 参数解密
     * @param type $str
     */
    public static function asciiParamsDecode($str){
    
        $str_length = hexdec(substr($str ,2,2 )); //取有效字符串长度,2-3字符
        $str_reppos = hexdec(substr($str ,4,2 )); //取随机替换点位置,4-5字符
        $str_rep    = substr($str ,6,1 );//取被替换的字符,6字符
        $str_str    = substr($str ,7,$str_length);//取随机替换后的有效数据7-?字节
        $newstr = substr_replace($str_str ,$str_rep, $str_reppos , 1); //还原原始数据
        $length = strlen($newstr);
        $outstr = '';
        for($i=0;$i<$length;$i+=2){
            $outstr .= chr(hexdec(substr($newstr , $i ,2)));
        }
        return $outstr;
    }
    

    /**
     * 生成订单号
     * @return type
     */
    public static function  build_order_no(){
        return date('YmdHis').mt_rand(1000,9999).substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 6);
    
    }
    
    
   public  static  function setFlash($obj,$status,$msg="")
    {
        $msgs    =   "";
        foreach($obj->errors as $key=>$v)
        {
            $msgs.=$v[0];
        }
        
        $msg    =   $msgs?$msgs:$msg;
        Yii::$app->getSession()->setFlash($status,$msg);
        
    }
   public  static  function setFlashNoObj($status,$msg="")
    {
        Yii::$app->getSession()->setFlash($status,$msg);
    }
   public  static  function getErrormsg($obj,$msg='')
    {
        $msgs    =   "";
        foreach($obj->errors as $key=>$v)
        {
            $msgs.=$v[0];
        }
        
        return $msgs.$msg;
        
    }
    
    
    public static function truncate_utf8_string($string, $length, $etc = '...')
    {
        $result = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strlen = strlen($string);
        for ($i = 0; (($i < $strlen) && ($length > 0)); $i++)
        {
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0'))
            {
                if ($length < 1.0)
                {
                    break;
                }
                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            }
            else
            {
                $result .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
        if ($i < $strlen)
        {
            $result .= $etc;
        }
        return $result;
    }
    
    public static function issuperadmin($id='')
    {
        $auth   =   Yii::$app->authManager;
        $currentrole    =   $id?$auth->getRolesByUser($id):$auth->getRolesByUser(Yii::$app->user->id);
        foreach ($currentrole as $key=>$obj)
        {
            if($obj->name==SUPERADMIN)    return true;
        }
        return false;
    }
    
   
    public static function cutstr($str,$len=20,$code='...')
    {
       $result  =    mb_substr($str,0,$len,'utf-8');
       if( mb_strlen($str,'utf-8')>$len)
       {
           return $result.$code;
       }else{
           return $result;
       }
        
    }

    static public function getLetter($i,$key=""){
        if($i>701)
        {
            return "";
        }
        $y = ($i / 26);
        if ($y >= 1) {
            $y = intval($y);
            return   $key?chr($y+64).chr($i-$y*26 + 65).$key:chr($y+64).chr($i-$y*26 + 65);
        } else {
            return  $key?chr($i+65).$key:chr($i+65);
        }
         
         
    }


    static public function getMatchImgurl($fileName,$mid,$type)
    {
        if(!$fileName) return self::getSystemParams("image_url");
        $keyfile    =   "";
        if($type==1)
        {
            $keyfile    =   "thumb/";
        }else{
            $keyfile    =   "image/";
        }
       return  self::getSystemParams("image_url")."match/".$mid."/".$keyfile.$fileName;
    }

    static public function statusList()
    {
        return [
            1=>'有效',
            2=>"无效"
        ];
    }


    static public function statusName($obj)
    {
        if(isset($obj->status)) return self::statusList()[$obj->status];
        return '';
    }


    static public function getImagetype($filename)
    {
        $file = fopen($filename, 'rb');
        $bin  = fread($file, 2); //只读2字节
        fclose($file);
        $strInfo  = @unpack('C2chars', $bin);
        $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
        // dd($typeCode);
        $fileType = '';
        switch ($typeCode) {
            case 255216:
                $fileType = 'jpg';
                break;
            case 7173:
                $fileType = 'gif';
                break;
            case 6677:
                $fileType = 'bmp';
                break;
            case 13780:
                $fileType = 'png';
                break;
            default:
                $fileType =  '';
        }
        // if ($strInfo['chars1']=='-1' AND $strInfo['chars2']=='-40' ) return 'jpg';
        // if ($strInfo['chars1']=='-119' AND $strInfo['chars2']=='80' ) return 'png';
        return $fileType;
    }

    static function ffmpegPhoto($file,$time = 1){
        // 从配置中获得ffmpeg截图存放位置
        // 这个是全局配置的，比如 /data/web/public/video
        // 建议这个地址是web网站目录结构中的一个地址，确保权限是777
        $path='/usr/local/mpms2backend/frontend/web/video/';
        /**
         *  拼接ffmpeg命令行
         *  $time 默认为1代表截取第几秒中的画面
         *  - 下面命令表示从 $file 视频流的1秒后切一个图
         *  $file 是视频文件的路径如：/data/web/public/video/bdb2c61440f788b6b96d4de8ae534b9d.mp4
         *  得到的缩略图和视频路径相同，文件名相同只有扩展名不同，可以根据自己的需求更改
         *  - 存放名称路径是下面的代码
         */
        $arr    =   explode('/',$file);

        $cmd="ffmpeg -i ".$file." -f image2 -ss 1 -vframes {$time} -s 400*300 ".$path.explode('.',$arr[count($arr)-1])[0]."a.jpg ";
        // 使用函数执行系统命令

        echo $cmd;
        echo "\n";

        $system = ['exec','system','passthru'];
        $status = 0;
        foreach ($system as $value){
            if(function_exists($value)){
                if($value == 'exec'){
                    $value($cmd,$data,$res);
                }else{
                    $value($cmd,$res);
                }
            }else{
                $status++;
            }
        }
        if($status == 3){
            $message = 'exec、system、passthru方法都被禁用了，无法生成缩略图！';
        }
        if($res==1){
            $message = '图片截取失败！';
        }else{
            $message = '生成缩略图成功！';
        }
        return $message ;
    }

    /**
     * @param $url
     * @param $type
     * @param array $data
     */
    static function curl($url,$data=[])
    {
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $output = curl_exec($ch);
        $output =json_decode($output);
        return $output;

    }

}
