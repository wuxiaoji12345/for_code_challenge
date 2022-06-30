<?php

/**
 * Created by wayne.
 * Date: 2019/2/1
 * Time: 1:27 PM
 */

namespace api\dbmodels;


use api\models\ImageFace;
use api\models\ImageFaceCategory;
use api\models\Match;
use api\models\MatchImage;
use api\models\MatchImageConfig;
use common\helpers\FaceDetect;

class dbMatchImage
{
    public static function getPhotosList($matchid,$sort,$page,$limit) {
        $data = [];
        $imgconfig = MatchImageConfig::findOne(['matchid'=>$matchid]);
        if(empty($imgconfig)) {
            $data['list'] = array();
            $data['allpages'] = 0;
            $data['total'] = 0;
            return $data;
        }
        $query = MatchImage::find()
            ->select ('id mimgid,imageurl imgurl,imageurl_thumb imgthumburl,imagesize,matchid')
            ->andWhere(['matchid'=>$matchid])
            ->andFilterWhere(['status'=>1]);

        $total = $query->count();
        $offset = ($page-1)*$limit;
        $allpages = ceil($total/$limit);
        $sort = $sort == 1?'desc':'asc';
        $list = $query->orderBy ("create_time $sort")->offset($offset)->limit($limit)->asArray()->all();

        if($imgconfig->sourcetype == 1 && !empty($list) && !empty($imgconfig->watermark)) {

            if(!strstr($imgconfig->watermark,'imageMogr2')){
                //$list[$i]['imgurl'] = $list[$i]['imgurl'];
            } else {
                for ($i=0; $i<count($list); $i++) {
                    $list[$i]['imgurl'] = $list[$i]['imgurl'].$imgconfig->watermark;
                }
            }

        }

        $photoviews = 0;
        if($imgconfig) {
            $addview = 1;
            if($imgconfig->view_magic > 1) {
                $addview = rand(1, $imgconfig->view_magic);
            }
            $imgconfig->updateCounters(['views'=>$addview,'realviews'=>1]);
            $photoviews = $imgconfig->views;
        }


        $data['total']      = $total;
        $data['page']       = $page;
        $data['allpages']   = $allpages;
        $data['photoviews']   = intval( $photoviews);

        $data['title'] = "照片";
        $matchinfo = Match::find()->select('title,category_id,imgurl')->andFilterWhere(['id'=>$matchid])->one();

        if(isset($imgconfig->title) && $imgconfig->title){
            $data['title']   = $imgconfig->title;
            $data['imgurl'] = $matchinfo->imgurl;
        }else{
            if($matchinfo) {
                $data['title']   = $matchinfo->title;
                $data['imgurl'] = $matchinfo->imgurl;
                $data['category_id'] = $matchinfo->category_id;
            }
        }

        $data['list'] = $list;

        return $data;
    }

    public static function getMyPhotosByFace($urid,$matchid,$imgurl,$catid,$page,$limit,$face_compare=0.7){

        $max_dense = 1024;
        $lastfaccompares = 10;

        $t1 = microtime(true);

        $data = [];
        if($catid){

            $query =  ImageFace::find()
                ->select ('id imgid,imgurl,imgthumburl,imgsize,matchid,catid')
                ->andWhere(['matchid'=>$matchid,'catid'=>$catid]);

            $facelist['totalnum'] = $query->count ();
            $facelist['list'] = $query->offset (($page-1)*$limit)->limit($limit)->asArray ()->all();

            $data['matchid'] = $matchid;
            $data['catid'] = $catid;
        }else{

            if($imgurl){

                $categoryList = ImageFaceCategory::find()->select ('id,dense_fea')
                    ->andWhere(['matchid'=>$matchid])->asArray ()->all();

                $alifaceres = FaceDetect::faceDetect($imgurl);

                $src_faceinfos = json_decode($alifaceres);
                if(!isset($src_faceinfos->dense_fea)) {
                    $GLOBALS['errormsg'] = '请上传人物照片!';
                    return false;
                }

                $src_facenum = $src_faceinfos->face_num;
                $src_dense   = $src_faceinfos->dense_fea;


                if($src_facenum >1 || $src_facenum <= 0) {
                    $GLOBALS['errormsg'] = '请上传单人照片查找照片!';
                    return false;
                }

                foreach($categoryList as $k=>$v){

                    $dst_faces = json_decode($v['dense_fea'],true);
                    if(empty($dst_faces))  continue ;

                    $loop = intval(count($dst_faces)/$max_dense);

                    //多个人的特征点
                    for($j=0;$j<$loop;$j++){
                        $start = $j * $max_dense;
                        $dst = array_slice($dst_faces , $start,$max_dense);
                        $facecompares = FaceDetect::faceCompare($src_dense, $dst);
                        if($facecompares <= $face_compare ){
                            if($facecompares < $lastfaccompares){
                                $lastfaccompares = $facecompares;
                                $mycategory = $v;
                            }
                        }
                    }
                }

                if(isset($mycategory)){

                    $query =  ImageFace::find()
                        ->select ('id imgid,imgurl,imgthumburl,imgsize,matchid,catid')
                        ->andWhere(['matchid'=>$matchid,'catid'=>$mycategory['id']]);

                    $facelist['totalnum'] = $query->count ();
                    $facelist['list'] = $query->offset (($page-1)*$limit)->limit($limit)->asArray ()->all();

                    if($facelist){
                        //保存到user_image_face表
                        $data['matchid'] = $matchid;
                        $data['catid'] = $mycategory['id'];

                    }
                }
            }
        }


        if(isset($facelist)){

            $data['times']      = microtime (true)-$t1;
            $data['total']      = $facelist['totalnum'];
            $data['page']       = $page;
            $data['allpages']   = ceil( $data['total']/$limit);
            if($facelist['list'])$data['list']       = $facelist['list'];
        } else {
            $GLOBALS['errormsg'] = '没找到:<';
            return false;
        }
        return $data;

    }
}