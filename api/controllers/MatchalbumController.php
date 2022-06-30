<?php
/**
 * Created by wayne.
 * Date: 2019/1/30
 * Time: 5:33 PM
 */

namespace api\controllers;



use api\dbmodels\dbMatchImage;

class MatchalbumController extends Controller
{

    public function actionPhotoindex() {

        $matchid = self::getJsonParamErr('matchid');
        $page = self::getJsonParam('page',1);
        $limit = self::getJsonParam('limit',10);
        $sort = self::getJsonParam('sort', 1);


        $data = dbMatchImage::getPhotosList($matchid,$sort,$page,$limit);

        self::checkData($data,'暂无活动');
        return self::dataOut($data);
    }


    public function actionFindmyphotos(){
        $imgurl = self::getJsonParam('imgurl');
        $matchid = self::getJsonParamErr('matchid');
        $urid = self::getJsonParam('urid');
        $catid = self::getJsonParam('catid');
        $page = self::getJsonParam('page',1);
        $limit = self::getJsonParam('limit',10);


        $data = dbMatchImage::getMyPhotosByFace($urid,$matchid,$imgurl,$catid,$page,$limit);

        if($data == false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        }
        return self::dataOut($data);
    }

}