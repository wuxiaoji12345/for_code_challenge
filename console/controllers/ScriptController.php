<?php
/**
 * Created by wayne.
 * Date: 2019/1/8
 * Time: 4:23 PM
 */
namespace console\controllers;


use api\models\ImageFace;
use api\models\ImageFaceCategory;
use api\models\ImageFacesRaw;
use api\models\MatchImage;
use api\models\MatchImageConfig;
use common\helpers\FaceDetect;
use common\helpers\FileLockAPI;

use common\helpers\MDCUtils;
use common\helpers\UploadOss;
use common\helpers\Utils;
use common\models\MemberInfo;
use console\models\dbScript;
use yii\console\Controller;
use Yii;

class ScriptController extends Controller
{

    public function __construct($id, $module, $config = [])
    {
        set_time_limit(0);
        date_default_timezone_set('Asia/Shanghai');
        parent::__construct($id, $module, $config);
    }

    public $path;
    public $matchid;

    public function options($actionID)
    {
        return ['path','matchid'];
    }

    public function optionAliases()
    {
        return ['path' => 'path','matchid'=>'matchid'];
    }

    public function actionSortphoto() {

        //get process lock
        $fp = FileLockAPI::getActionLock();
        if(empty($fp))
            return;

        $time = time();
        $configdata = MatchImageConfig::find()
            ->andFilterWhere(['<=','start_time', $time])
            ->andFilterWhere(['>=', 'end_time', $time])
            ->asArray()->all();

        if(empty($configdata)) {
            echo 'no image match';
            FileLockAPI::unlockFile($fp);
            return;
        }

        foreach ($configdata as $one) {

            if($one['sourcetype'] == MatchImageConfig::SOURCETYPE_VPHOTO) {
                dbScript::getImagesFromVphoto($one);
            } else if($one['sourcetype'] == MatchImageConfig::SOURCETYPE_MEIPAI) {
                dbScript::getImagesFromPaipai($one);
            } else if($one['sourcetype'] == MatchImageConfig::SOURCETYPE_PUSHI) {
                dbScript::getImagesFromPushi($one);
            }

        }


        echo "done\r\n";//exit;

        FileLockAPI::unlockFile($fp);
    }

    function ResizeImage($upload,$maxwidth,$maxheight,$name)
    {
        $uploadfile = @imagecreatefromjpeg($upload);

        if(empty($uploadfile)) {
            return false;
        }

        //取得当前图片大小
        $width = imagesx($uploadfile);
        $height = imagesy($uploadfile);

        //生成缩略图的大小
        if(($width > $maxwidth) || ($height > $maxheight))
        {
            $scale_w = $maxwidth/$width;
            $scale_h = $maxheight/$height;
            $scale = $scale_w<$scale_h?$scale_w:$scale_h;
            $newwidth = $width * $scale;
            $newheight = $height * $scale;
            if(function_exists("imagecopyresampled"))
            {
                $uploaddir_resize = imagecreatetruecolor($newwidth, $newheight);
                imagecopyresampled($uploaddir_resize, $uploadfile, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            }
            else
            {
                $uploaddir_resize = imagecreate($newwidth, $newheight);
                imagecopyresized($uploaddir_resize, $uploadfile, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            }

            ImageJpeg ($uploaddir_resize,$name);
            ImageDestroy ($uploaddir_resize);
        }
        else
        {
            ImageJpeg ($uploadfile,$name);
        }
        return true;
    }

    public function actionFileupload(){
        $path = $this->path;
        $matchid = $this->matchid;

        $file = Utils::read_all_dir($path);
        if(empty($file) || empty($file['file'])) {
            echo '空文件夹';
            return;
        }

        if(empty($matchid)) {
            echo '错误赛事';
            return;
        }

        $file = $file['file'];
        $num = 0;
        foreach ($file as $oneimage) {
            $md5 = md5_file($oneimage);
            if(empty($md5)) continue;
            $size = filesize($oneimage);
            if($size>20*1024*1024) {
                $newfile = $oneimage.'_new';

                $ret = $this->ResizeImage($oneimage, 2048,2048,$newfile);
                if(empty($ret)) {
                    echo 'error:'.$oneimage."\r\n";
                    continue;
                }
                $size = filesize($newfile);
                if($size<=0) {
                    echo 'error:'.$oneimage."\r\n";
                    continue;
                }

                $oneimage = $newfile;
                $md5 = md5_file($oneimage);
            }

            $size = floor($size/1024).'K';
            $img = MatchImage::findOne(['matchid'=>$matchid,'md5'=>$md5]);
            if(empty($img)) {
                $ossupload = new UploadOss();
                $ossupload->fileobj = $oneimage;
                $imgurl = $ossupload->uploadOss();
                if(empty($imgurl)) continue;

                $img = new MatchImage();
                $img->matchid = $matchid;
                $img->md5 = $md5;
                $img->imageurl = $imgurl."?x-oss-process=image/resize,w_1024,h_1024";
                $img->imageurl_thumb = $imgurl."?x-oss-process=image/resize,w_300,h_300";
                $img->status = 1;
                $img->imagesize = $size;
                $img->create_time = time();
                $ret = $img->save();
                $num += $ret;
            }

            $raw = ImageFacesRaw::findOne(['imgurl'=>$img->imageurl]);
            if(empty($raw)) {
                $jsonret = FaceDetect::faceDetect($img->imageurl);
                if(empty($jsonret))
                    continue;

                $jsonobj = json_decode($jsonret, true);
                if(empty($jsonobj) || $jsonobj['errno'] != 0) {
                    continue;
                }

                $raw = new ImageFacesRaw();
                $raw->matchid = $matchid;
                $raw->imgurl = $img->imageurl;
                $raw->imgthumburl = $img->imageurl_thumb;
                $raw->imgsize = $img->imagesize;
                $raw->face_num = $jsonobj['face_num'];
                $raw->content_body = $jsonret;

                $raw->save();
            } else {
                $jsonret = $raw->content_body;
                if(!empty($jsonret)) {
                    $jsonobj = json_decode($jsonret, true);
                    if(empty($jsonobj)) {

                        $jsonret = FaceDetect::faceDetect($img->imageurl);
                        if(empty($jsonret))
                            continue;

                        $jsonobj = json_decode($jsonret, true);
                        if(empty($jsonobj) || $jsonobj['errno'] != 0) {
                            continue;
                        }

                        $raw->content_body = $jsonret;
                        $raw->save();
                    }
                } else {
                    $jsonret = FaceDetect::faceDetect($img->imageurl);
                    if(empty($jsonret))
                        continue;

                    $jsonobj = json_decode($jsonret, true);
                    if(empty($jsonobj) || $jsonobj['errno'] != 0) {
                        continue;
                    }

                    $raw->content_body = $jsonret;
                    $raw->save();
                }
            }

            $facenum = $jsonobj['face_num'];
            //echo 'face:'.$facenum.',';
            if($facenum == 0)
                continue;

            $catdata = ImageFaceCategory::find()->andFilterWhere(['matchid'=>$matchid])->all();

            for($j=0; $j<$facenum; $j++) {  //traverse all faces in one photo
                $allface[$j]['face_prob'] = $jsonobj['face_prob'][$j];
                $allface[$j]['gender'] = $jsonobj['gender'][$j];
                $allface[$j]['age'] = $jsonobj['age'][$j];
                $length = $jsonobj['dense_fea_len'];
                $allface[$j]['dense_fea'] = array_slice($jsonobj['dense_fea'], $j*$length, $length);
                $allface[$j]['pose'] = array_slice($jsonobj['pose'],$j*3,3);
                $allface[$j]['face_rect'] = array_slice($jsonobj['face_rect'],$j*4,4);

                if($allface[$j]['face_prob']< 0.8) {
                    continue;
                }

                if(abs($allface[$j]['pose'][0]) > 45 || abs($allface[$j]['pose'][1]) > 45 || abs($allface[$j]['pose'][2]) > 45) {
                    continue;
                }

                $sel_catid = 0;
                $compare_value = 2;
                $cat_facenum = 10;
                for($k=0; $k<count($catdata); $k++) {   //traverse all photo category
                    if ($catdata[$k]->gender != $allface[$j]['gender'])
                        continue;

                    $src = json_decode($catdata[$k]->dense_fea, true);
                    $value = FaceDetect::faceCompare($src, $allface[$j]['dense_fea']);
                    if($value < $compare_value){
                        $compare_value = $value;
                        $sel_catid = $catdata[$k]->id;
                        $cat_facenum = $catdata[$k]->face_num;
                    }

                }

                if($compare_value<0.7) {
                    $allface[$j]['catid'] = $sel_catid;
                    $allface[$j]['result'] = $compare_value;

                    if($cat_facenum>1 && $facenum<$cat_facenum) {
                        $uimageurl = $img->imageurl;
                        $uage = $allface[$j]['age'];
                        $ufea = json_encode($allface[$j]['dense_fea']);
                        $ucatid = $allface[$j]['catid'];
                        $cat = ImageFaceCategory::findOne(['id'=>$ucatid]);
                        $cat->imgurl = $uimageurl;
                        $cat->age = $uage;
                        $cat->face_num = $facenum;
                        $cat->dense_fea = $ufea;
                        $cat->save();
                    }

                }

                if(empty($allface[$j]['catid'])) {

                    $insertcategory = new ImageFaceCategory();
                    $insertcategory->matchid = $matchid;
                    $insertcategory->imgurl = $img->imageurl;
                    $insertcategory->gender = $allface[$j]['gender'];
                    $insertcategory->age = $allface[$j]['age'];
                    $insertcategory->face_num = $facenum;
                    $insertcategory->dense_fea = json_encode($allface[$j]['dense_fea']);

                    $insertcategory->save();
                    $catid = $insertcategory->id;
                    $allface[$j]['catid'] = $catid;
                    $allface[$j]['result'] = 0;
                }

                $findface = ImageFace::find()->andFilterWhere(['catid'=>$allface[$j]['catid'], 'matchid'=>$matchid,'imgurl'=>$img->imageurl])->all();

                if(empty($findface)) {
                    $insertface = new ImageFace();
                    $insertface->catid = $allface[$j]['catid'];
                    $insertface->matchid = $matchid;
                    $insertface->imgurl = $img->imageurl;
                    $insertface->imgthumburl = $img->imageurl_thumb;
                    $insertface->imgsize = $img->imagesize;
                    $insertface->face_prob = $allface[$j]['face_prob'];
                    $insertface->pose = json_encode($allface[$j]['pose']);
                    $insertface->face_rect = json_encode($allface[$j]['face_rect']);
                    $insertface->gender = $allface[$j]['gender'];
                    $insertface->age = $allface[$j]['age'];
                    $insertface->dense_fea_len = $length;
                    $insertface->dense_fea = json_encode($allface[$j]['dense_fea']);
                    $insertface->result = $allface[$j]['result'];
                    $insertface->save();
                }
            }


        }

        echo 'done:'.$num;
        exit;

    }

    public function actionPtsascore() {
        $matchid = $this->matchid;
        if(empty($matchid)) {
            echo 'matchid不能为空'."\r\n";
            return;
        }

        $data = dbScript::calcPTSAscore($matchid);

        echo 'done';
        exit;
    }

    public function actionExportscore() {
        $matchid = $this->matchid;
        if(empty($matchid)) {
            echo 'matchid不能为空'."\r\n";
            return;
        }

        $data = dbScript::exportscore($matchid);

        echo 'done';
        exit;
    }

    public function actionMemberdata() {
        $usercount = MemberInfo::find()->count();
        $limit = 50;
        $pages = ceil($usercount/$limit);
        $num = 0;
        for ($i = 0; $i<$pages; $i++) {
            $offset = $i*$limit;
            $users = MemberInfo::find()->orderBy('id asc')
                ->limit($limit)->offset($offset)
                ->asArray()->all();
            foreach ($users as $oneuser) {
                unset($params);
                $idtype = 1;
                if($oneuser['idtype'] == '身份证') {
                    $idtype = 1;
                } else if($oneuser['idtype'] == '港澳台通行证') {
                    $idtype = 3;
                } else if($oneuser['idtype'] == '护照') {
                    $idtype = 2;
                } else {
                    $idtype = 1;
                }

                $birth = $oneuser['birth'];
                $fullname = $oneuser['name'];
                $idnumber = $oneuser['idnumber'];

                if(empty($idnumber) || empty($fullname)) {
                    continue;
                }

                $params['fullname'] = $fullname;
                $params['idtype'] = $idtype;
                $params['idnumber'] = $idnumber;
                $params['gender'] = $oneuser['gender'];

                $params['birth'] = $birth;

                $input['jsonparams'] = json_encode($params);

                $ret = MDCUtils::mdcCall('user/savemember', $input);
                $num++;
                echo $num."\r\n";
            }

        }
        echo 'done';
    }

}