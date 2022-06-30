<?php
/**
 * Created by wayne.
 * Date: 2019/1/5
 * Time: 10:03 PM
 */

namespace api\controllers;

use api\models\dbEnterprise;
use common\helpers\UploadOss;
use common\models\MatchImage;
use common\models\MatchImageConfig;
use Yii;
use yii\web\UploadedFile;

class EnterpriseController extends Controller
{

    public function actionInfo() {
        $gid = self::getJsonParamErr('gid');
        $gid = $this->checkGid($gid);
        if(empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $data['info']['logo'] = "http://moveclub-file.oss-cn-hangzhou.aliyuncs.com/mpms/20190531/1559298205-5cf1009d0645b.png";

//        $data = dbEnterprise::getInfo($gid);
//        if($data == false) {
//            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
//        }

        return self::dataOut($data);

    }

    public function actionFileupload(){
        $urid = self::getJsonParamErr('urid');
        $token =  self::getJsonParamErr('token');

        $check = self::checkUser($urid, $token);
        if(!$check) {
            return self::dataOut(array(), '错误用户', self::TOKEN_ERR);
        }

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
}