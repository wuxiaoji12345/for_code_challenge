<?php
/**
 * Created by wayne.
 * Date: 2019/1/5
 * Time: 10:03 PM
 */

namespace api\controllers;

use api\dbmodels\dbApi;
use common\helpers\UploadOss;
use common\models\MatchImage;
use common\models\MatchImageConfig;
use Yii;
use yii\web\UploadedFile;

class ApiController extends Controller
{

    public function actionTemp() {
          $allCategory = MatchImageConfig::find()->asArray()->all();
        foreach ($allCategory as $one) {
            $images = MatchImage::find()->andFilterWhere(['matchid'=>$one['matchid']])
                ->orderBy('create_time desc')->asArray()->all();

        }
        return $allCategory;
    }

    public function actionFileupload(){
        //$urid = self::getJsonParamErr('urid');
        //       $token =  self::getJsonParamErr('token');

//        $check = self::checkUser($urid, $token);
//        if(!$check) {
//            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
//        }

        $imgkey = self::getJsonParamErr('imgkey');
        $imgobj = UploadedFile::getInstanceByName($imgkey);
        if(empty($imgobj)) {
            return self::errorOut('上传错误');
        }

        $ossupload = new UploadOss();
        $ossupload->fileobj = $imgobj;

        $imgurl = $ossupload->uploadOss();
        self::checkData($imgurl,'失败');
        return self::dataOut(['imgurl'=>$imgurl,'imgkey'=>$imgkey]);
    }

    public function actionShareinfos(){

        $type  = self::getJsonParamErr('type');
        $matchid  = self::getJsonParamErr('matchid');

        $data = dbApi::getShareInfo($type,$matchid);

        self::checkData($data);

        return self::dataOut($data);

    }

    public function actionSystemtime() {
        list($msec, $sec) = explode(' ', microtime());
        $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        //echo $msectime;
        return self::dataOut(['ms'=>$msectime]);
    }
}