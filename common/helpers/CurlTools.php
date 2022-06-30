<?php
/**
 * Created by wayne.
 * Date: 2019/1/8
 * Time: 6:15 PM
 */

namespace common\helpers;


class CurlTools
{
    static public function postCurl($url, $post_data) {
        //$post_data = array ("id" => 12);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// post数据
        curl_setopt($ch, CURLOPT_POST, 1);
// post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
//打印获得的数据
        return $output;
    }

    public static function Curl($url =  NULL){
        if(empty($url)){
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}