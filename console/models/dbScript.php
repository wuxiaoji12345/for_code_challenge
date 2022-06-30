<?php

namespace console\models;
use api\dbmodels\dbMatch;
use api\dbmodels\dbScore;
use api\models\ImageFace;
use api\models\ImageFaceCategory;
use api\models\ImageFacesRaw;
use api\models\MatchImage;
use api\models\MatchImageConfig;
use common\helpers\CurlTools;
use common\helpers\FaceDetect;
use common\models\ScoreEnroll;
use common\models\ScoreStates;
use Yii;

/**
 * Created by wayne.
 * Date: 2019/1/8
 * Time: 6:09 PM
 */
class dbScript
{
    const URL_vphoto = "https://api.vphotos.cn/vphotosgallery/wechat/album/getPhotoListByWeChatId";
    //const URL_paipai = "https://m.inmuu.com/v1/srv/activityPhoto/getNewPhotoList/";
    const URL_paipai = "http://api.meipaipai.cn:8089/v3/studio/getPhotoList/";
    const URL_pushi = "https://live.photoplus.cn/api/live/pic/list?token=plus_364edafadcaefa67badaet_plus";

    private static function updatephoto($matchid, $dataimages, $photos, $sourcetype=0) {

        $insertnum = 0;
        $photonum = 0;
        $allfacenum = 0;
        $passface = 0;
        $rawitems = 0;
        $rawfacenum = 0;
        $total = count($dataimages);

        foreach ($photos as $one) {
            $photonum++;
            echo $photonum . ",";
            $onephoto['pic_id'] = 0;
            if($sourcetype == MatchImageConfig::SOURCETYPE_MEIPAI) {
//                $onephoto['smallUrl'] = $one['photoPathUrl'];
//                $onephoto['thumbUrl'] = $one['photoPathUrl'].'!listpc';
//                $onephoto['imageSize'] = round($one['photoSize']/1000, 2).'K';
//                $onephoto['photoTime'] = date('Y-m-d H:i:s', $one['shootingTime']/1000);
                $onephoto['smallUrl'] = $one['watermarkUrl'];
                $onephoto['thumbUrl'] = $one['watermarkUrl'].'!p900';
                $onephoto['imageSize'] = round($one['photoSize']/1000, 2).'K';
                $onephoto['photoTime'] = date('Y-m-d H:i:s', $one['createdAt']/1000);
            } else if($sourcetype == MatchImageConfig::SOURCETYPE_PUSHI){
                $onephoto['smallUrl'] = 'http:'.$one['big_img'];
                $onephoto['thumbUrl'] = 'http:'.$one['small_img'];
                $onephoto['imageSize'] = round($one['middle_size']/1000, 2).'K';
                $onephoto['photoTime'] = $one['relate_time'];
                $onephoto['imageurl_origin'] = 'http:'.$one['origin_img'];
                $onephoto['imagesize_origin'] = round($one['pic_size']/1000, 2).'K';
                $onephoto['pic_id'] = intval($one['pic_id']);
            } else {
                $onephoto = $one;
            }

            if($sourcetype == MatchImageConfig::SOURCETYPE_PUSHI){
                for ($i = 0; $i < $total; $i++) { //handled
                    if ($dataimages[$i]['pic_id'] == $onephoto['pic_id']) {
                        break;
                    }
                }
            } else {
                for ($i = 0; $i < $total; $i++) { //handled
                    if (strcmp($dataimages[$i]['imageurl'], $onephoto['smallUrl']) == 0) {
                        break;
                    }
                }
            }


            if ($i >= $total) { //not has image in match_image table
                $tmp = new MatchImage();
                $tmp->matchid = $matchid;
                $tmp->imageurl = $onephoto['smallUrl'];
                $tmp->imageurl_thumb = $onephoto['thumbUrl'];
                $tmp->imagesize = "".$onephoto['imageSize'];
                $tmp->create_time = strtotime($onephoto['photoTime']);
                if(isset($onephoto['pic_id'])) {
                    $tmp->pic_id = $onephoto['pic_id'];
                }
                if(isset($onephoto['imageurl_origin'])) {
                    $tmp->imageurl_origin = $onephoto['imageurl_origin'];
                    $tmp->imagesize_origin = $onephoto['imagesize_origin'];
                }
                $tmp->save();
            } else {
                continue;
            }

            $imgurls = explode('?', $onephoto['smallUrl']);
            $imgurl = $imgurls[0];

            $raw_select_data = ImageFacesRaw::find()->andFilterWhere(['like', 'imgurl', $imgurl.'%', false])->one();
                //->andFilterWhere(['imgurl' => $imgurl])->one();
            if (empty($raw_select_data)) {
                $jsonret = FaceDetect::faceDetect($onephoto['smallUrl']);

                if (empty($jsonret))
                    continue;

                $jsonobj = json_decode($jsonret, true);
                if (empty($jsonobj) || $jsonobj['errno'] != 0) {
                    continue;
                }

                $raw_select_data = new ImageFacesRaw();
                $raw_select_data->matchid = $matchid;
                $raw_select_data->imgurl = $onephoto['smallUrl'];
                $raw_select_data->imgthumburl = $onephoto['thumbUrl'];
                $raw_select_data->imgsize = $onephoto['imageSize'];
                $raw_select_data->face_num = $jsonobj['face_num'];
                $raw_select_data->content_body = $jsonret;
                $raw_select_data->save();
            } else {
                $jsonret = $raw_select_data->content_body;
                $jsonobj = json_decode($jsonret, true);

                if (empty($jsonobj)) {
                    echo 'emptybody:' . $raw_select_data['id'] . "||";
                    $jsonret = FaceDetect::faceDetect($onephoto['smallUrl']);
                    if (empty($jsonret))
                        continue;

                    $jsonobj = json_decode($jsonret, true);
                    if (empty($jsonobj) || $jsonobj['errno'] != 0) {
                        continue;
                    }

                    $rawimageurl = $onephoto['smallUrl'];

                    $raw_select_data->content_body = $jsonret;
                    $raw_select_data->save();
                }
//            if($raw_select_data[0]['face_num'] != $jsonobj['face_num']) {
//                echo 'num error:('.$raw_select_data[0]['face_num'].",".$jsonobj['face_num'].")".$raw_select_data[0]['id']."|||";
//            }

                $rawitems++;
                $rawfacenum += $jsonobj['face_num'];

            }

            $facenum = $jsonobj['face_num'];
            if($facenum == 0)
                continue;

            $catdata = ImageFaceCategory::find()->andFilterWhere(['matchid'=>$matchid])->asArray()->all();

            unset($allface);
            for($j=0; $j<$facenum; $j++) {  //traverse all faces in one photo
                $allface[$j]['face_prob'] = $jsonobj['face_prob'][$j];
                $allface[$j]['gender'] = $jsonobj['gender'][$j];
                $allface[$j]['age'] = $jsonobj['age'][$j];
                $length = $jsonobj['dense_fea_len'];
                $allface[$j]['dense_fea'] = array_slice($jsonobj['dense_fea'], $j*$length, $length);
                $allface[$j]['pose'] = array_slice($jsonobj['pose'],$j*3,3);
                $allface[$j]['face_rect'] = array_slice($jsonobj['face_rect'],$j*4,4);

                $allfacenum++;

                if($allface[$j]['face_prob']< FaceDetect::threshold_face_prob) {
                    $passface++;
                    continue;
                }

                if(abs($allface[$j]['pose'][0]) > 40) {
                    $passface++;
                    continue;
                }


                $sel_catid = 0;
                $compare_value = 0;
                $cat_facenum = 10;
                for($k=0; $k<count($catdata); $k++) {   //traverse all photo category
                    if ($catdata[$k]['gender'] != $allface[$j]['gender'])
                        continue;

                    $src = json_decode($catdata[$k]['dense_fea'], true);
                    $value = FaceDetect::faceCompare2($src, $allface[$j]['dense_fea']);
                    //echo 'value:'.$value."\r\n";
                    if($value > $compare_value){
                        $compare_value = $value;
                        $sel_catid = $catdata[$k]['id'];
                        $cat_facenum = $catdata[$k]['face_num'];
                    }

                }

                if($compare_value>0.60) {
                    $allface[$j]['catid'] = $sel_catid;
                    $allface[$j]['result'] = $compare_value;
                    if($cat_facenum>1 && $facenum<$cat_facenum) {
                        $uimageurl = $onephoto['smallUrl'];
                        $uage = $allface[$j]['age'];
                        $ufea = json_encode($allface[$j]['dense_fea']);
                        $ucatid = $allface[$j]['catid'];
                        $categorymodel = ImageFaceCategory::findOne(['id'=>$ucatid]);
                        $categorymodel->imgurl = $uimageurl;
                        $categorymodel->age = $uage;
                        $categorymodel->face_num = $facenum;
                        $categorymodel->dense_fea = $ufea;
                        $categorymodel->save();
                    }

                }


                if(empty($allface[$j]['catid'])) {
                    $model = new ImageFaceCategory();
                    $model->matchid = $matchid;
                    $model->imgurl = $onephoto['smallUrl'];
                    $model->gender = "".$allface[$j]['gender'];
                    $model->age = "".$allface[$j]['age'];
                    $model->face_num = $facenum;
                    $model->dense_fea = json_encode($allface[$j]['dense_fea']);
                    $ret = $model->save();
                    $catid = 0;
                    if($ret) {
                        $catid = $model->id;
                    }
                    $allface[$j]['catid'] = $catid;
                    $allface[$j]['result'] = 0;
                }

                $findface_data = ImageFace::find()->andFilterWhere(['catid'=>$allface[$j]['catid'],'matchid'=>$matchid])
                    ->andFilterWhere(['like', 'imgurl', $imgurl.'%', false])
                    //->andFilterWhere(['imgurl'=>$imgurl])
                    ->asArray()->all();

                if(empty($findface_data)) {
                    $newface = new ImageFace();
                    $newface->catid = $allface[$j]['catid'];
                    $newface->matchid = $matchid;
                    $newface->imgurl = $onephoto['smallUrl'];
                    $newface->imgthumburl = $onephoto['thumbUrl'];
                    $newface->imgsize = $onephoto['imageSize'];
                    $newface->face_prob= $allface[$j]['face_prob'];
                    $newface->pose = json_encode($allface[$j]['pose']);
                    $newface->face_rect = json_encode($allface[$j]['face_rect']);
                    $newface->gender = "".$allface[$j]['gender'];
                    $newface->age = "".$allface[$j]['age'];
                    $newface->dense_fea_len = $length;
                    $newface->dense_fea = json_encode($allface[$j]['dense_fea']);
                    $newface->result = $allface[$j]['result'];
                    $newface->save();
                }
            }

            $insertnum++;
        }
    }

    public static function getImagesFromVphoto($configmodel)
    {

        if (empty($configmodel) || empty($configmodel['matchid']) || empty($configmodel['sourceid']))
            return false;

        $matchid = $configmodel['matchid'];
        $url = self::URL_vphoto;
        $postdata['weChatId'] = $configmodel['sourceid'];//$configdata['sourceid'];
        $postdata['pageSize'] = 1;
        $newurl = $url.'?weChatId='.$postdata['weChatId'].'&pageSize='.$postdata['pageSize'];

        $result = CurlTools::Curl($newurl);
        if (empty($result)) {
            echo 'Error:Vphotos curl error!!!';
            return false;
        }
        $result = json_decode($result, true);
        if (empty($result) || empty($result['data'])) {
            echo 'Error:Vphotos parse error!!!';
            return false;
        }

        $apitotal = isset($result['data']['total']) ? $result['data']['total'] : 0;
        if ($apitotal == 0) {
            echo 'vphoto no image';
            return true;
        }

        $query = MatchImage::find()->andFilterWhere(['matchid' => $configmodel['matchid']]);
            //->andFilterWhere(['status' => 1]);
        $total = $query->count();
        $timenow = time();
        $ten_minute = $timenow % 600;
        if ($ten_minute >= 60) {
            if ($total >= $apitotal) {
                echo 'no change';
                return true;
            }
        }

        $postdata['pageSize'] = $apitotal;
        $newurl = $url.'?weChatId='.$postdata['weChatId'].'&pageSize='.$postdata['pageSize'];
        $result = CurlTools::Curl($newurl);
        if (empty($result)) {
            echo 'Error:Vphotos api error 2!!!';
            return false;
        }
        $result = json_decode($result, true);
        if (empty($result) || empty($result['data']) || empty($result['data']['photos'])) {
            echo 'Error:Vphotos parse error 2!!!';
            return false;
        }

        $dataimages = $query->asArray()->all();

        $photos = $result['data']['photos'];

        self::updatephoto($matchid, $dataimages, $photos);

    }

    public static function getImagesFromPushi($configmodel) {
        if (empty($configmodel) || empty($configmodel['matchid']) || empty($configmodel['sourceid']))
            return false;
        $matchid = $configmodel['matchid'];
        $url = self::URL_pushi;
        $number = $configmodel['sourceid'];//$configdata['sourceid'];
        $page = 1;
        $count = 1;
        $nowater = 'false';
        if(empty($configmodel['watermark'])) {
            $nowater = 'true';
        }
        $url = $url.'&activityNo='.$number.'&page='.$page.'&noWater='.$nowater;
        $result = CurlTools::Curl($url.'&count='.$count);

        

        if (empty($result)) {
            echo 'Error:Pushi curl error!!!';
            return false;
        }

        $result = json_decode($result, true);
        if (empty($result) || empty($result['result'])) {
            echo 'Error:Pushi parse error!!!';
            return false;
        }

        $apitotal = isset($result['result']['pic_total']) ? $result['result']['pic_total'] : 0;
        if ($apitotal == 0) {
            echo 'Pushi no image';
            return true;
        }

        $query = MatchImage::find()->andFilterWhere(['matchid' => $configmodel['matchid']])
            ->andFilterWhere(['status' => 1]);
        $total = $query->count();
        $timenow = time();
        $ten_minute = $timenow % 600;
        if ($ten_minute >= 60) {
            if ($total >= $apitotal) {
                echo 'no change';
                return true;
            }
        }

        $result = CurlTools::Curl($url.'&count='.$apitotal);
        if (empty($result)) {
            echo 'Error:Pushi api error 2!!!';
            return false;
        }
        $result = json_decode($result, true);
        if (empty($result) || empty($result['result']) || empty($result['result']['pics_array'])) {
            echo 'Error:Pushi parse error 2!!!';
            return false;
        }

        $dataimages = $query->asArray()->all();

        $photos = $result['result']['pics_array'];

        self::updatephoto($matchid, $dataimages, $photos, MatchImageConfig::SOURCETYPE_PUSHI);

    }

    public static function getImagesFromPaipai($configmodel)
    {

        if (empty($configmodel) || empty($configmodel['matchid']) || empty($configmodel['sourceid']))
            return false;

        $matchid = $configmodel['matchid'];
        $url = self::URL_paipai;
        $category = $configmodel['sourceid'];//$configdata['sourceid'];
        $url = $url.$category.'?pageNo=1';

        $postdata['pageSize'] = 1;
        $result = CurlTools::Curl($url.'&pageSize='.$postdata['pageSize']);

        if (empty($result)) {
            echo 'Error:Paipai curl error!!!';
            return false;
        }
        $result = json_decode($result, true);
        if (empty($result) || empty($result['result'] || empty($result['success']))) {
            echo 'Error:Paipai parse error!!!';
            return false;
        }

        $apitotal = isset($result['result']['total']) ? $result['result']['total'] : 0;
        if ($apitotal == 0) {
            echo 'Paipai no image';
            return true;
        }

//        if (empty($result)) {
//            echo 'Error:Paipai curl error!!!';
//            return false;
//        }
//        $result = json_decode($result, true);
//        if (empty($result) || empty($result['data'])) {
//            echo 'Error:Paipai parse error!!!';
//            return false;
//        }
//
//        $apitotal = isset($result['data']['total']) ? $result['data']['total'] : 0;
//        if ($apitotal == 0) {
//            echo 'Paipai no image';
//            return true;
//        }

        $query = MatchImage::find()->andFilterWhere(['matchid' => $configmodel['matchid']])
            ->andFilterWhere(['status' => 1]);
        $total = $query->count();
        $timenow = time();
        $ten_minute = $timenow % 600;
        if ($ten_minute >= 60) {
            if ($total >= $apitotal) {
                echo 'no change';
                return true;
            }
        }

        $postdata['pageSize'] = $apitotal;
        $result = CurlTools::Curl($url.'&pageSize='.$postdata['pageSize']);
        if (empty($result)) {
            echo 'Error:Paipai api error 2!!!';
            return false;
        }
        $result = json_decode($result, true);
        if (empty($result) || empty($result['result']) || empty($result['result']['photoList'])) {
            echo 'Error:Paipai parse error 2!!!';
            return false;
        }

        $dataimages = $query->asArray()->all();

        $photos = $result['result']['photoList'];

        self::updatephoto($matchid, $dataimages, $photos, MatchImageConfig::SOURCETYPE_MEIPAI);

    }


    public static function calcPTSAscore($matchid) {
        $allscore = ScoreStates::find()->andFilterWhere(['matchid'=>$matchid,'isvalued'=>1])
            ->orderBy('itemid asc, score asc')->asArray()->all();

        $itemid = 0; $rank = 0;
        for($i=0; $i<count($allscore); $i++)  {
            $thescore = $allscore[$i];
            if($thescore['itemid'] != $itemid) {
                $rank = 1;
                $itemid = $thescore['itemid'];
            } else {
                $rank ++ ;
            }

            $ret = dbScore::setPTSAscore($thescore['enrollid'], $rank, $thescore['itemid']);
            if(empty($ret)) {
                echo $GLOBALS['errormsg']."\r\n";
            }
        }

    }


    public static function putCsv($csvFileName, $dataArr ,$haderText = ''){
        $handle = fopen($csvFileName,"a+");//写方式打开
        if(!$handle){
            return '文件打开失败';
        }
        //判断是否定义头标题
        if(!empty($haderText)){
            foreach ($haderText as $key => $value) {
                $haderText[$key] = iconv("utf-8","gbk//IGNORE",$value);//对中文编码进行处理
            }
            $re = fputcsv($handle,$haderText);//该函数返回写入字符串的长度。若出错，则返回 false。。
        }

        if(!empty($dataArr)) {
            foreach ($dataArr as $key => $value) {
                foreach ($value as $k => $v) {
                    $value[$k] = iconv("utf-8","gbk//IGNORE",$v);//对中文编码进行处理
                }
                $re = fputcsv($handle,$value);//该函数返回写入字符串的长度。若出错，则返回 false。。
            }
        }

    }

    public static function milliSecondtoTime($score) {
        $ms = $score % 1000;
        $second = intval($score / 1000);
        $hour = intval($second / 3600);

        if($second >= 60) {
            $time = gmstrftime('%M:%S', $second);
        } else {
            $time = gmstrftime('%S', $second);
        }

        return $time.".".substr(sprintf("%03d",$ms),0,2);
    }

    public static function exportscore($matchid) {
        $allsession = dbMatch::getSessionList($matchid,  null, null);
        if(empty($allsession) || empty($allsession[0]['items']))
            return false;

        $allenroll = ScoreEnroll::find()->andFilterWhere(['matchid'=>$matchid])
            ->asArray()->all();
        $enrollmap = array();
        foreach ($allenroll as $oneenroll) {
            $enrollmap[$oneenroll['id']] = $oneenroll;
        }

        $allitems = $allsession[0]['items'];

        $path = Yii::$app->getRuntimePath();
        $filename = $path.'/'.$matchid.'_score_'.time().'.csv';

        $head = array('match title here');

        self::putCsv($filename,null,$head);

        $titlename = ['名次', '姓名', '单位', '成绩', '备注'];
        $whiteline = [' '];


        $index = 1;
        foreach ($allitems as $oneitem) {
            $itemname[0] = $index.'.'.$oneitem['name'];
            self::putCsv($filename,null,$itemname);
            self::putCsv($filename,null,$titlename);
            $id = $oneitem['id'];
            unset($list);
            $list = ScoreStates::find()->andFilterWhere(['itemid'=>$id])
                ->andFilterWhere(['>=','isvalued',1])
                ->orderBy('isvalued asc, score asc')
                ->asArray()->all();

            $output = array();
            for($i=0; $i<count($list); $i++) {
                $onelist = $list[$i];


                if($onelist['isvalued'] == 1) {
                    $output[$i][0] = $i+1;
                    $output[$i][1] = $onelist['enrollname'];
                    $output[$i][2] = $enrollmap[$onelist['enrollid']]['unit'];
                    $output[$i][3] = self::milliSecondtoTime($onelist['score']);
                } else if($onelist['isvalued'] == 2) {
                    $output[$i][0] = '';
                    $output[$i][1] = $onelist['enrollname'];
                    $output[$i][2] = $enrollmap[$onelist['enrollid']]['unit'];
                    $output[$i][3] = '';
                    $output[$i][4] = '未完赛';
                }else if($onelist['isvalued'] == 3) {
                    $output[$i][0] = '';
                    $output[$i][1] = $onelist['enrollname'];
                    $output[$i][2] = $enrollmap[$onelist['enrollid']]['unit'];
                    $output[$i][3] = '';
                    $output[$i][4] = '弃权';
                }else if($onelist['isvalued'] == 4) {
                    $output[$i][0] = '';
                    $output[$i][1] = $onelist['enrollname'];
                    $output[$i][2] = $enrollmap[$onelist['enrollid']]['unit'];
                    $output[$i][3] = '';
                    $output[$i][4] = '犯规';
                }



            }

            self::putCsv($filename,$output,'');

            self::putCsv($filename,null,$whiteline);
            $index++;
        }
        //print_r($allitems);exit;
    }
}
