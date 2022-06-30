<?php

/**
 * Created by wayne.
 * Date: 2019/1/5
 * Time: 10:03 PM
 */

namespace api\controllers;

use api\models\dbEnterprise;
use api\models\dbUser;
use api\models\WxTemplate;
use common\helpers\UploadOss;
use common\models\MatchImage;
use common\models\MatchImageConfig;
use common\models\model\Sms;
use JmesPath\Utils;
use Yii;
use yii\web\UploadedFile;

class SmsController extends Controller
{
    public function actionRegisterverify()
    {
        $mobile = self::getJsonParamErr("mobile");
        $action = self::getJsonParamErr("action");
        $code = self::getJsonParam("code");
        $data = dbUser::SendMobilemessage($mobile, $action, $code);
        if ($data == false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        } else {
            return self::dataOut(array(), '发送成功', self::MV_SUCCESS);
        }
    }

    public function actionSubscribetemplate()
    {
        $gid = self::getJsonParamErr('gid');
        $app = self::getJsonParamErr('app');
        $gid = $this->checkGid($gid);
        if (empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }
        $type = self::getJsonParam('type');

        $query = WxTemplate::find()->select('name,wx_subscribe_template')
            ->andFilterWhere(['app' => $app]);
        if (!empty($type)) {
            $query->andFilterWhere(['type' => $type]);
        }
        $data = $query->asArray()->all();
        if ($data === false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        } else {

            return self::dataOut($data, 'ok', self::MV_SUCCESS);
        }
    }


    public function actionRegistermessage()
    {
        $gid = self::getJsonParamErr('gid');
        $app = self::getJsonParamErr('app');
        $gid = $this->checkGid($gid);
        if (empty($gid)) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::GID_ERR);
        }

        $mobile = self::getJsonParamErr('mobile');
        $matchname = self::getJsonParamErr('matchname');
        $typename = self::getJsonParamErr('typename');

        $data = dbEnterprise::sendRegistermessage($gid, $mobile, $app, $matchname, $typename);
        if ($data == false) {
            return self::dataOut(array(), $GLOBALS['errormsg'], self::OTHER_ERR);
        } else {

            return self::dataOut(array(), '发送成功', self::MV_SUCCESS);
        }
    }

    public function actionVerifyCode()
    {
        $mobile = self::getJsonParamErr('mobile');
        $smscode = self::getJsonParamErr('smscode');
        $message = Sms::find()->andFilterWhere(['mobile' => $mobile, 'content' => $smscode])
            ->orderBy('id desc')->one();

        if (empty($message) || $message->create_time + 5 * 60 < time()) {
            return self::dataOut(['valid' => false], '验证失败', self::OTHER_ERR);
        }

        return self::dataOut(['valid' => true], '验证成功', self::MV_SUCCESS);
    }
}
