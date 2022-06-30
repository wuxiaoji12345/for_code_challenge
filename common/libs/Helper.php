<?php

namespace common\libs;

use common\libs\ding\Ding;
use common\libs\file_log\LOG;
use common\models\BaseModel;
use common\models\OperationLog;
use GuzzleHttp\Client;
use yii\db\Exception;

class Helper
{
    const ENCRYPT_PREFIX = 'ZERO';
    const SECRET_KEY = 'lingmouai';
    const PAGE_SIZE = 25;


    /**
     * 获取用户IP地址
     * @param int $type
     * @param bool $adv
     * @return array|null|string
     */
    public static function get_client_ip($type = 0, $adv = false)
    {
        $type = $type ? 1 : 0;
        static $ip = NULL;
        if ($ip !== NULL) return $ip[$type];
        if ($adv) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos = array_search('unknown', $arr);
                if (false !== $pos) unset($arr[$pos]);
                $ip = trim($arr[0]);
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }

    /**
     * @param $url
     * @param $data
     * @param bool $json
     * @param int $timeout
     * @param array $header
     * @return bool|string
     */
    public static function curlQuery($url, $data, $json = false, $timeout = 300, $header = [])
    {
        $ch = curl_init();
//        $header = [];
        if ($json) {
            $data = json_encode($data);
            $header[] = 'Content-Type:application/json;charset=utf-8';
            $header[] = 'Content-Length:' . strlen($data);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $handles = curl_exec($ch);
        if ($handles == false)
            LOG::log(curl_error($ch));
        curl_close($ch);
        return $handles;
    }

    public static function curlQueryLog($url, $data, $json = false, $timeout = 300, $header = [])
    {
        LOG::log($url);
        LOG::log($data);
        $res = self::curlQuery($url, $data, $json, $timeout, $header);
        if ($res == null) {
            $ding = Ding::getInstance();
            $ding->sendTxt("推送返回为null \nurl:" . $url . "\ndata: " . json_encode($data, JSON_UNESCAPED_UNICODE));
        }
        LOG::log($res);
        return json_decode($res, true);
    }

    /**
     * @param $url
     * @param array $header
     * @return bool|string
     */
    public static function curlGet($url, $header = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * 随机生成验证码数字
     * @param int $len
     * @return string
     */
    public static function randString($len = 6)
    {
        $chars = str_repeat('0123456789', $len);
        $chars = str_shuffle($chars);
        $str = substr($chars, 0, $len);
        return $str;
    }

    /**
     * 生成随机字符串
     * @param int $len
     * @return string
     */
    public static function genRandomString($len)
    {
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9"
        );
        $charsLen = count($chars) - 1;
        shuffle($chars); // 将数组打乱
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars [mt_rand(0, $charsLen)];
        }
        return $output;
    }

    /**
     * 二维数组根据字段进行排序
     * @params array $array 需要排序的数组
     * @params string $field 排序的字段
     * @params string $sort 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
     */
    public static function arraySequence($array, $field, $sort = 'SORT_DESC')
    {
        $arrSort = array();
        foreach ($array as $uniqid => $row) {
            foreach ($row as $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        array_multisort($arrSort[$field], constant($sort), $array);
        return $array;
    }

    public static function getModelError($error)
    {
        $item = array_pop($error);
        return is_array($item) ? $item[0] : 'error';
    }

    /**
     * openssl 对称加密
     * @param $data
     * @param $key
     * @param string $encryptMethod
     * @param $options
     * @return false|string
     */
    public static function encrypt($data, $key, $encryptMethod = 'aes-256-cbc', $options = 0)
    {

        $ivLength = openssl_cipher_iv_length($encryptMethod);
//        $iv = openssl_random_pseudo_bytes($ivLength, $isStrong);
//        if (false === $iv) {
//            die('IV 生成失败');
//        }
        $iv = self::genRandomString($ivLength);

        $data .= static::ENCRYPT_PREFIX;
        return openssl_encrypt($data, $encryptMethod, $key, $options, $iv) . $iv;
    }

    /**
     * openssl 对称解密
     * @param $data
     * @param $key
     * @param string $encryptMethod
     * @param int $options
     * @return false|string
     */
    public static function decrypt($data, $key, $encryptMethod = 'aes-256-cbc', $options = 0)
    {
        $ivLength = openssl_cipher_iv_length($encryptMethod);
        $dataLength = strlen($data) - $ivLength;
        $iv = substr($data, $dataLength);
        $data = substr($data, 0, $dataLength);
        $decrypted = openssl_decrypt($data, $encryptMethod, $key, $options, $iv);
        return substr($decrypted, 0, -strlen(static::ENCRYPT_PREFIX));
    }

    /**
     * token加密校验
     * @param $timestamp
     * @param $api_key
     * @return string
     */
    public static function md5token($timestamp, $api_key)
    {
        return md5($timestamp . md5($api_key) . $timestamp);
    }

    /**
     * 日期格式化
     * @param $date
     * @param string $format
     * @param bool $is_chinese
     * @return false|int|string
     */
    public static function dateTimeFormat($date, $format = 'Y-m-d H:i:s', $is_chinese = false)
    {
        if ($is_chinese) {
            $arr = date_parse_from_format('Y年m月d日', $date);
            $date = mktime(0, 0, 0, $arr['month'], $arr['day'], $arr['year']);
        } else {
            return date($format, strtotime($date));
        }
        return date($format, $date);
    }

    // 是否含emoji
    public static function haveEmojiChar($str)
    {
        $mbLen = mb_strlen($str);

        $strArr = [];
        for ($i = 0; $i < $mbLen; $i++) {
            $strArr[] = mb_substr($str, $i, 1, 'utf-8');
            if (strlen($strArr[$i]) >= 4) {
                return true;
            }
        }

        return false;
    }

    // 移除 emoji 表情
    public static function removeEmojiChar($str)
    {
        $mbLen = mb_strlen($str);

        $strArr = [];
        for ($i = 0; $i < $mbLen; $i++) {
            $mbSubstr = mb_substr($str, $i, 1, 'utf-8');
            if (strlen($mbSubstr) >= 4) {
                continue;
            }
            $strArr[] = $mbSubstr;
        }

        return implode('', $strArr);
    }

    // 合并对象数组,arr2的值覆盖arr1的值，arr2有，但是arr1没有的对象，在合并后追加
    public static function objectArrayMerge($arr, $arr2, $key_field, $value_field)
    {
        foreach ($arr as &$item) {
            $keyValue = $item[$key_field];
            $sameItem = array_filter(
                $arr2,
                function ($e) use (&$keyValue, &$key_field) {
                    return $e[$key_field] == $keyValue;
                }
            );
            if (!empty($sameItem)) {
                $item[$value_field] = $sameItem[$value_field];
            }
        }
        foreach ($arr2 as $item2) {
            $keyValue = $item2[$key_field];
            $sameItem = array_filter(
                $arr,
                function ($e) use (&$keyValue, &$key_field) {
                    return $e[$key_field] == $keyValue;
                }
            );
            if (empty($sameItem)) {
                $arr[] = $item2;
            }
        }
        return $arr;
    }

    /**
     * 生成where条件
     * @param $data
     * @param $params
     * @param bool $or
     * @param bool $need_jurisdiction
     * @return mixed
     */
    public static function makeWhere($data, $params, $or = false, $need_jurisdiction = false)
    {
        $where[] = $or ? 'or' : 'and';
        foreach ($data as $item1) {
            foreach ($item1[0] as $k => $v) {
                $switch = $item1[2] ?? '';
                if ($item1[1] == 'between') {
                    if (isset($params[$v[0]]) && ($params[$v[0]] || $params[$v[0]] == "0")) {
                        $start = self::timeAddInfo(self::timeAddInfo($params[$v[0]], 'start_time'), $switch);
                        $end = self::timeAddInfo(self::timeAddInfo($params[$v[1]], 'end_time'), $switch);
                        $where[] = [$item1[1], $k, $start, $end];
                    }
                } else if ($item1[1] == 'tweenbe') {
                    if (isset($params[$k]) && ($params[$k] || $params[$k] == "0")) {
                        $time = self::timeAddInfo($params[$k], $switch);
                        $where[] = [
                            'and', ['<=', $v[0], $time], ['>=', $v[1], $time]
                        ];
                    }
                } else {
                    //0在php中也是判断为空
                    if (isset($params[$k]) && ($params[$k] || $params[$k] == "0")) {
                        $time = self::timeAddInfo($params[$k], $switch);
                        $where[] = [$item1[1], $v, $time];
                    }
                }
            }
        }
        if ($need_jurisdiction) $where = BaseModel::jurisdiction($where);
        return $where;
    }

    /**
     * 时间格式化
     * @param $time
     * @param $switch
     * @param string $recursion
     * @return false|int|string
     */
    public static function timeAddInfo($time, $switch, $recursion = '')
    {
        switch ($switch) {
            case 'start_time':
                $time = $time . ' 00:00:00';
                break;
            case 'end_time':
                $time = $time . ' 23:59:59';
                break;
            case 'timestamp':
                $time = strtotime($time);
                break;
        }
        if ($recursion)
            $time = self::timeAddInfo($time, $recursion);
        return $time;
    }

    /**
     * 格式化时间戳
     * @param $timestamp
     * @param string $format
     * @return false|string
     */
    public static function timestampToDate($timestamp, $format = 'Y-m-d H:i:s')
    {
        return date($format, $timestamp);
    }

    /**
     * 解压前端gzipEncode方法压缩的长文本
     * @param $str
     * @return false|string
     */
    public static function gzipDecode($str)
    {
        return gzdecode(base64_decode($str));
    }

    public static function gzipEncode($str)
    {
        return base64_encode(gzencode($str));
    }

    public static function makeSignature($requestData)
    {
        //剔除不参与加密的参数
        unset($requestData['r']);
        unset($requestData['signature']);
        //升序排列
        ksort($requestData);
        /*
        由于跨平台,java加密时是按中文进行加密的,而经过http已经变成unicode,所以需要先转过来
        例如java是按如下进行md5
        {"addressId":"8","areaId":"140201","detail":"突然有一天你会发现自己说话"}^ZS2018LCJ
        到了这里会变成
        {"addressId":"8","areaId":"140201","detail":"\u7a81\u7136\u6709\u4e00"}^ZS2018LCJ
        所以要逐个参数转成中文
        */
        $postKey = '';
        foreach ($requestData as $k => $v) {
            /*$requestData[$k] = preg_replace_callback(
                "#\\\u([0-9a-f]+)#i",
                function($m){return iconv('UCS-2','UTF-8', pack('H4',$m[1]));},$v
            );*/
            $postKey .= $k;
        }
        //json编码(JSON_UNESCAPED_UNICODE是为了不要把中文再变成unicode)
        //$requestData = json_encode($requestData, JSON_UNESCAPED_UNICODE);
        //json_encode会把/变成\/,所以我们要通过stripslashes把\/变回/
        //$requestData = stripslashes($requestData);
        //连接密钥
        //$requestData = $requestData . "^" . self::SECRET_KEY;
        $requestData = $postKey . "^" . self::SECRET_KEY;
        //签名
        $signature = md5($requestData);
        return $signature;
    }

    public static function checkSignature($requestData)
    {
        //对比签名
        if ($requestData['signature'] != self::makeSignature($requestData)) {
            return false;
        }
        return true;
    }


    public static function makePageInfo($params, $page_size = self::PAGE_SIZE)
    {
        $data['page'] = (isset($params['page']) && $params['page']) ? $params['page'] - 1 : 0;
        $data['page_size'] = ($params['page_size'] ?? $page_size);
        return $data;
    }

    public static function buildWeChatQuery(array $param)
    {
        $data = '';
        foreach ($param as $k => $v) {
            $data .= '/' . $k . '/' . $v;
        }
        return $data;
    }

    /**
     * 导出csv需要头信息
     * @param $fileName
     */
    public static function csvDownload($fileName)
    {
        ini_set("memory_limit", "2048M");
        set_time_limit(0);


        //设置导出的文件名
        $fileName = iconv('utf-8', 'gbk', $fileName . date("Y-m-d"));

        //设置表头
//        header('Content-Type: application/vnd.ms-excel');
        $now = gmdate("D, d M Y H:i:s");

//        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");

        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");

        header("Last-Modified: {$now} GMT");

        // force download

        header("Content-Type: application/force-download");

        header("Content-Type: application/octet-stream");

        header("Content-Type: application/download");

        // disposition / encoding on response body

        header("Content-Disposition: attachment;filename=" . $fileName . ".csv");

        header("Content-Transfer-Encoding: binary");

    }

    /**
     * ocr图片识别功能
     * @param $imgurl
     * @return mixed
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function ocr($imgurl)
    {
        $url = 'http://api.mdc.movecloud.cn/app/wechat-token?app=119';
        $client = new Client();
        $res = $client->get($url);
        $data = json_decode($res->getBody(), true);
        $res = (new Client)->head($imgurl);
        $filesize = $res->getHeader('content-length')[0] ?? 0;
        $formdata = [
            'access_token' => $data['data']['token'] ?? '',
            'img_url' => $filesize > 2000 * 1000
                ? "${imgurl}?x-oss-process=image/quality,q_80/format,jpg"
                : "${imgurl}?x-oss-process=image/quality,q_90/format,jpg",
        ];
        $result = (new Client())->post('https://api.weixin.qq.com/cv/ocr/comm', [
            'form_params' => $formdata,
        ]);
        $ocrContent = (string)$result->getBody();
        if (!$data = json_decode($ocrContent, true)) {
//            throw new Exception('OCR识别失败');
            $data = [];
        }
        if ($data['errcode'] > 0) {
            $data = [];
            if ($data['errcode'] == 40001) {
//                throw new Exception('系统忙，请稍后再试');
            }
//            throw new Exception($data['errmsg']);
        } elseif ($data['errcode'] < 0) {
//            throw new Exception('微信系统错误');
        }
        return $data;
    }

    /**
     * 数据脱敏
     * @param $data
     * @param int $start_len
     * @param int $end_len
     * @param string $fill
     * @return string
     */
    public static function desensitization($data, $start_len = 3, $end_len = -4, $fill = '****')
    {
        if (empty($data)) return $data;
        return substr($data, 0, $start_len) . $fill . substr($data, $end_len);
    }

    public static function findNum($str = '', $algorithm = 'add')
    {
        $str = trim($str);
        if (empty($str)) {
            return '';
        }
        $result = 0;
        if ($algorithm == 'add') {
            for ($i = 0; $i < strlen($str); $i++) {
                if (is_numeric($str[$i])) {
                    $result += $str[$i];
                }
            }
        }
        return $result;
    }


    /**
     * 记录操作
     * @param array $log
     */
    public static function RecordOperationLog(array $log) {
        $log['operation_time'] = date('Y-m-d H:i:s');
        $log['ip'] = $_SERVER["REMOTE_ADDR"];
        $model = new OperationLog();
        $model->load($log,'');
        $model->save();
    }
}