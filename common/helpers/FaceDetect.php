<?php
/**
 * Created by wayne.
 * Date: 2019/1/8
 * Time: 7:01 PM
 */

namespace common\helpers;


class FaceDetect
{

    const threshold_face_prob = 0.8;
    const threshold_face_compare = 0.7;

    const ali_face_attribute = 'https://dtplus-cn-shanghai.data.aliyuncs.com/face/attribute';
    const akId = 'gVrRMAiY5yK2PLQr';
    const akSecret = 'J0l1qNdyWgYacn7O4zmm7IUjLZEE28';


    static function faceCompare($src, $dst) {
        $num = count($src);
        $sum = 0;
        for($i=0; $i<$num; $i++) {
            $sum += ($src[$i] - $dst[$i])*($src[$i] - $dst[$i]);
        }
        return $sum;
    }

//    static public function getDetecttmp($src) {
//        $num = count($src);
//        $sum = 0.0;
//        for($i=0; $i<$num; $i++) {
//            $sum += $src[$i]*$src[$i];
//        }
//        print_r($src);
//        echo 'sum:'.$sum.'sqrt:'.sqrt($sum);
//
//        return sqrt($sum);
//    }

    static function faceCompare2($src, $dst) {
        $num = count($src);
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;

        for($i=0; $i<$num; $i++) {
            $sum1 += $src[$i]*$dst[$i];
            //$sum2 += $src[$i]*$src[$i];
            //$sum3 += $dst[$i]*$dst[$i];
        }
        //$ret = $sum1/sqrt($sum2*$sum3);

        return $sum1;
    }

    static function faceDetect($imageurl)
    {

        if(empty($imageurl))
            return null;

        $akId = FaceDetect::akId;
        $akSecret = FaceDetect::akSecret;
        //更新api信息
        $url = FaceDetect::ali_face_attribute;
        $contentobj['type'] = 0;
        $contentobj['image_url'] = $imageurl;

        $options = array(
            'http' => array(
                'header' => array(
                    'accept'=> "application/json",
                    'content-type'=> "application/json",
                    'date'=> gmdate("D, d M Y H:i:s \G\M\T"),
                    'authorization' => ''
                ),
                'method' => "POST", //可以是 GET, POST, DELETE, PUT
                'content' => json_encode($contentobj) //如有数据，请用json_encode()进行编码
            )
        );

        $http = $options['http'];
        $header = $http['header'];
        $urlObj = parse_url($url);

        if(empty($urlObj["query"]))
            $path = $urlObj["path"];
        else
            $path = $urlObj["path"]."?".$urlObj["query"];
        $body = $http['content'];
        if(empty($body))
            $bodymd5 = $body;
        else
            $bodymd5 = base64_encode(md5($body,true));
        $stringToSign = $http['method']."\n".$header['accept']."\n".$bodymd5."\n".$header['content-type']."\n".$header['date']."\n".$path;
        $signature = base64_encode(
            hash_hmac(
                "sha1",
                $stringToSign,
                $akSecret, true));
        $authHeader = "Dataplus "."$akId".":"."$signature";
        $options['http']['header']['authorization'] = $authHeader;
        $options['http']['header'] = implode(
            array_map(
                function($key, $val){
                    return $key.":".$val."\r\n";
                },
                array_keys($options['http']['header']),
                $options['http']['header']));
        $context = stream_context_create($options);
        $file = file_get_contents($url, false, $context );
        return $file;
    }
}