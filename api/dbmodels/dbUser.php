<?php
/**
 * Created by wayne.
 * Date: 2019/2/1
 * Time: 3:51 PM
 */

namespace api\dbmodels;


use AlibabaCloud\Client\AlibabaCloud;
use api\models\AddressCheck;
use api\models\Match;
use api\models\MatchSession;
use api\models\MatchSessionItem;
use api\models\MemberInfo;
use api\models\MemberScoreRecord;
use api\models\RegisterDetail;
use api\models\RegisterInfo;
use api\models\RegisterRelation;
use api\models\RegisterType;
use api\models\User;
use api\models\UserChannel;
use api\models\UserChannelExtra;
use api\models\UserChannelPhone;
use api\models\UserFormid;
use api\models\UserInfo;
use api\models\UserMember;
use api\models\wxapp\wxBizDataCrypt;
use backend\models\Address;
use common\helpers\CurlTools;
use common\helpers\MDCUtils;
use common\helpers\Utils;

use common\libs\Helper;
use common\models\BaseModel;
use common\models\CheckInfo;
use common\models\WorkOrder;
use common\models\WorkOrderHistory;
use common\models\WorkOrderIndex;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;


class dbUser
{
    const WX_USER_URL = 'https://api.weixin.qq.com/sns/jscode2session?appid=';//微信小程序


    static public function saveformID($urid, $app, $formid)
    {
        $userchannel = UserChannel::findOne(['urid' => $urid, 'app' => $app]);
        if (empty($userchannel)) {
            $GLOBALS['errormsg'] = '错误用户信息';
            return false;
        }

        $form = new UserFormid();
        $form->urid = $urid;
        $form->openid = $userchannel->openid;
        $form->formid = $formid;
        $form->app = $app;
        $form->status = 1;
        $form->create_time = time();
        $ret = $form->save();
        if (empty($ret)) {
            $GLOBALS['errormsg'] = '保存错误';
        }
        return $ret;
    }


    /**
     * 解密数据
     * @param type $input
     */
    private static function decryptedData($encryptedData, $sessionkey, $iv, $app = 1)
    {

        $sysparams = Yii::$app->params;

        $appid = $sysparams['appids'][$app]['appid'];
        //$secret  = $sysparams['appids'][$app]['secret'];
        $pc = new wxBizDataCrypt($appid, $sessionkey);

        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        if ($errCode == 0) {
            return ArrayHelper::toArray(json_decode($data));
        }

        return false;

    }


    public static function wxLogin($wxcode, $encryptedData, $iv, $userdata, $gid = 1, $fromurid = 0, $app = 1, $dist = 0)
    {
        $sysparams = Yii::$app->params;

        $appid = $sysparams['appids'][$app]['appid'];
        $secret = $sysparams['appids'][$app]['secret'];

        $url = self::WX_USER_URL . $appid . '&secret=' . $secret . '&js_code=' . $wxcode . '&grant_type=authorization_code';
        $wxlogininfo = CurlTools::Curl($url);
        if (empty($wxlogininfo))
            return false;

        $wxinfo = json_decode($wxlogininfo, true);
        if (empty($wxinfo) || isset($wxinfo['errcode'])) {
            return false;
        }

        $userdata = json_decode($userdata, true);
        if (empty($userdata)) {
            return false;
        }
        $userdata = array_change_key_case($userdata);

        if (!isset($wxinfo['openid']) || !isset($wxinfo['session_key'])) {
            return false;
        }

        $unionid = '';
        if (!empty($wxinfo['unionid'])) {
            $unionid = $wxinfo['unionid'];
        }
        $openid = $wxinfo['openid'];
        $sessionkey = $wxinfo['session_key'];
        if (empty($unionid)) {
            $infos = self::decryptedData($encryptedData, $sessionkey, $iv, $app);
            if (!empty($infos['unionId'])) {
                $unionid = $infos['unionId'];
            }
        }

        if (empty($openid)) {
            return false;
        }

        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {

            //save User
            $isnew = User::findOne(['unionid' => $openid]);
            if (empty($isnew)) {
                $isnew = new User();
                $isnew->unionid = $openid;
                $isnew->create_time = time();
                $isnew->status = 1;
                $ret = $isnew->save();
                if (empty($ret)) {
                    $transaction->rollBack();
                    return false;
                }
            }

            //save UserInfo
            $userinfo = UserInfo::findOne(['urid' => $isnew->id]);
            if (empty($userinfo)) {
                $userinfo = new UserInfo();
                $userinfo->urid = $isnew->id;
                if (isset($userdata['nickname']))
                    $userinfo->nickname = $userdata['nickname'];
                if (isset($userdata['avatarurl']))
                    $userinfo->avatarurl = $userdata['avatarurl'];
                if (isset($userdata['country']))
                    $userinfo->country = $userdata['country'];
                if (isset($userdata['province']))
                    $userinfo->province = $userdata['province'];
                if (isset($userdata['city']))
                    $userinfo->city = $userdata['city'];
                if (isset($userdata['language']))
                    $userinfo->language = $userdata['language'];
                if (isset($userdata['gender']))
                    $userinfo->gender = $userdata['gender'];

                $userinfo->create_time = time();
                $userinfo->status = 1;
                $ret = $userinfo->save();
                if (empty($ret)) {
                    $transaction->rollBack();
                    return false;
                }
            }

            //save UserChannel
            $userchannel = UserChannel::findOne(['urid' => $isnew->id, 'openid' => $openid, 'gid' => $gid, 'app' => $app, 'status' => 1]);
            if (empty($userchannel)) {
                $userchannel = new UserChannel();
                $userchannel->urid = $isnew->id;
                $userchannel->openid = $openid;
                $userchannel->unionid = $openid;
                $userchannel->session_key = $sessionkey;
                $userchannel->app = $app;
                $userchannel->create_time = time();
                $userchannel->token = Utils::makeToken();
                $userchannel->gid = $gid;
                $userchannel->dist = $dist;
                if (!empty($extappid)) {
                    $userchannel->extappid = $extappid;
                }
                $userchannel->status = 1;
            } else {
                $userchannel->token = Utils::makeToken();
            }
            $ret = $userchannel->save();
            if (empty($ret)) {
                $transaction->rollBack();
                return false;
            }

            $transaction->commit();
        } catch (\Exception $e) {
            return false;
        }

        $userChannelExtra = UserChannelExtra::findOne([
            'user_channel_id' => $userchannel->id,
            'status' => UserChannelExtra::STATUS_VALID
        ]);
        $isChecker = false;
        if (isset($userChannelExtra) && $userChannelExtra->is_checker == UserChannelExtra::CHECKER_YES) {
            $isChecker = true;
        }
        $is_super_checker = false;
        if (isset($userChannelExtra) && $userChannelExtra->is_super_checker == UserChannelExtra::SUPER_CHECKER_YES) {
            $is_super_checker = true;
        }

        $output['isowner'] = 0;
        if (!empty($userChannelExtra)) {
            $output['isowner'] = $userChannelExtra->is_owner;
        }

        $params['appid'] = 8;
        $params['unionid'] = $unionid;
        $params['openid'] = $openid;
        $params['uname'] = $userinfo->nickname;
        /*$ret = MDCUtils::mdcCall('user/login', $params);
        if(!empty($ret) && $ret['status'] == 200) {
            $output['uid'] = $ret['data']['uid'];
            $output['uuid'] = $ret['data']['uuid'];
        }*/

        $output['urid'] = $isnew->id;
        $output['token'] = $userchannel->token;
        $output['state'] = 0;
        $output['ischecker'] = $isChecker;
        $output['is_super_checker'] = $is_super_checker;
        $output['channelid'] = Utils::ecbEncrypt(Yii::$app->params['channelIDKey'], $userchannel->id);

        return $output;

    }

    public static function wxLoginNew($bk_model)
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            //save UserChannel
            $userchannel = UserChannel::findOne(['urid' => $bk_model->id, 'status' => 1]);
            if (empty($userchannel)) {
                $transaction->rollBack();
                return false;
            } else {
                $userchannel->token = Utils::makeToken();
            }
            $ret = $userchannel->save();
            if (empty($ret)) {
                $transaction->rollBack();
                return false;
            }

            $transaction->commit();
        } catch (\Exception $e) {
            return false;
        }

        $userChannelExtra = UserChannelExtra::findOne([
            'user_channel_id' => $userchannel->id,
            'status' => UserChannelExtra::STATUS_VALID
        ]);
        $isChecker = false;
        if (isset($userChannelExtra) && $userChannelExtra->is_checker == UserChannelExtra::CHECKER_YES) {
            $isChecker = true;
        }
        $is_super_checker = false;
        if (isset($userChannelExtra) && $userChannelExtra->is_super_checker == UserChannelExtra::SUPER_CHECKER_YES) {
            $is_super_checker = true;
        }
        $is_super_man = false;
        $role = BaseModel::getRoleNames();
        if (in_array(BaseModel::SUPER_MAN, $role[0])) {
            $is_super_man = true;
        }
        $output['isowner'] = 0;
        if (!empty($userChannelExtra)) {
            $output['isowner'] = $userChannelExtra->is_owner;
        }

//        $params['appid'] = 8;
//        $params['unionid'] = $unionid;
//        $params['openid'] = $openid;
//        $params['uname'] = $userinfo->nickname;
        /*$ret = MDCUtils::mdcCall('user/login', $params);
        if(!empty($ret) && $ret['status'] == 200) {
            $output['uid'] = $ret['data']['uid'];
            $output['uuid'] = $ret['data']['uuid'];
        }*/

        $output['urid'] = $bk_model->id;
        $output['token'] = $userchannel->token;
        $output['state'] = 0;
        $output['ischecker'] = $isChecker;
        $output['is_super_checker'] = $is_super_checker;
        $output['is_super_man'] = $is_super_man;
        $output['channelid'] = Utils::ecbEncrypt(Yii::$app->params['channelIDKey'], $userchannel->id);

        return $output;

    }

    public static function uLogin($uname, $pwd, $code, $gid = 1, $app = 1, $fromurid = 0, $dist = 0)
    {
        if (!empty($pwd)) {
            $pwd = md5($pwd);
            $newuser = UserChannelPhone::find()
                ->andFilterWhere(['phone' => $uname])
                ->andFilterWhere(['password' => $pwd])
                ->andFilterWhere(['app' => $app, 'gid' => $gid, 'status' => 1])->one();


        } else if (!empty($code)) {
            $newuser = UserChannelPhone::find()
                ->andFilterWhere(['phone' => $uname])
                ->andFilterWhere(['code' => $pwd])
                ->andFilterWhere(['>', 'code_expired', time()])
                ->andFilterWhere(['app' => $app, 'gid' => $gid, 'status' => 1])->one();


        } else {
            return false;
        }

        if (empty($newuser)) {
            return false;
        }

        $newuser->token = Utils::makeToken();
        $ret = $newuser->save();
        if (empty($ret)) {
            return false;
        }
        $output['token'] = $newuser->token;
        $output['urid'] = $newuser->urid;
        return $output;

    }

    public static function sendMessage($phone, $gid, $app)
    {
        $channel = UserChannelPhone::findOne(['phone' => $phone, 'gid' => $gid, 'app' => $app]);
        if (empty($channel)) {
            $GLOBALS['errormsg'] = '消息发送错误1';
            return false;
        }

        $sendtime = strtotime($channel->send_date . ' 23:59:59');
        if (time() < $sendtime) {
            if ($channel->send_num >= 10) {
                $GLOBALS['errormsg'] = '每日发送验证码已达上限';
                return false;
            }
        }

        $code = '' . rand(1000, 9999);
        $channel->code = $code;
        $channel->code_expired = time() + 10 * 60;
        $ret = $channel->save();
        if (empty($ret)) {
            $GLOBALS['errormsg'] = '消息发送错误2';
            return false;
        }

        $ret = Utils::sendVerifyMessage('PTSA', $code, $phone, '赛事平台');
        if ($ret) {
            if (time() < $sendtime) {
                $channel->send_num += 1;
            } else {
                $channel->send_num = 1;
            }
            $channel->send_date = date('Y-m-d', time());
            $channel->save();
        }

        return true;
    }

    public static function SendVerify($phone, $gid, $app)
    {
        $allself = UserChannelPhone::find()
            ->andFilterWhere(['phone' => $phone])->all();
        if (empty($allself)) {

            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $isnew = new User();
                $isnew->phone = $phone;
                $isnew->create_time = time();
                $isnew->status = 1;
                $ret = $isnew->save();
                if (empty($ret)) {
                    $transaction->rollBack();
                    $GLOBALS['errormsg'] = 'Error 1001';
                    return false;
                }

                //save UserInfo
                $userinfo = new UserInfo();
                $userinfo->urid = $isnew->id;
                $userinfo->nickname = $phone;

                $userinfo->create_time = time();
                $userinfo->status = 1;
                $ret = $userinfo->save();
                if (empty($ret)) {
                    $transaction->rollBack();
                    $GLOBALS['errormsg'] = 'Error 1002';
                    return false;
                }

                //save UserChannel
                $userchannel = new UserChannelPhone();
                $userchannel->urid = $isnew->id;
                $userchannel->phone = $phone;
                $userchannel->app = $app;
                $userchannel->gid = $gid;
                $userchannel->create_time = time();
                $userchannel->token = Utils::makeToken();
                $userchannel->status = 1;
                $ret = $userchannel->save();
                if (empty($ret)) {
                    $transaction->rollBack();
                    $GLOBALS['errormsg'] = 'Error 1003';
                    return false;
                }

                $transaction->commit();
            } catch (\Exception $e) {
                $GLOBALS['errormsg'] = 'Error 1004';
                return false;
            }

            $ret = self::sendMessage($phone, $gid, $app);
            return $ret;

        } else {
            $onechannel = null;
            foreach ($allself as $one) {
                if ($one->gid == $gid && $one->app == $app) {
                    $onechannel = $one;
                    break;
                }
            }

            if (empty($onechannel)) {
                $userchannel = new UserChannelPhone();
                $userchannel->urid = $allself[0]->urid;
                $userchannel->phone = $phone;
                $userchannel->app = $app;
                $userchannel->gid = $gid;
                $userchannel->create_time = time();
                $userchannel->token = Utils::makeToken();
                $userchannel->status = 1;
                $ret = $userchannel->save();
                if (empty($ret)) {
                    $GLOBALS['errormsg'] = 'Error 1005';
                    return false;
                }
            }

            $ret = self::sendMessage($phone, $gid, $app);
            return $ret;
        }

        return true;
    }

    public static function getMyMemberList($urid, $gid, $page)
    {
        $query = UserMember::find()->from(UserMember::tableName() . ' as a')
            ->select('a.id as memberid, a.memberinfos, b.name, b.gender, b.idtype, b.idnumber, b.birth, b.avatar, b.nation, b.score')
            ->joinWith('memberinfo as b', false)
            ->andFilterWhere(['a.urid' => $urid, 'status' => 1])
            ->andFilterWhere(['not', ['a.memberinfos' => 'null']]);
        $limit = 20;
        $total = $query->count();
        $pages = ceil($total / $limit);
        $offset = ($page - 1) * $limit;
        $data = $query->limit($limit)->offset($offset)
            ->orderBy('a.id desc')->asArray()->all();

        $output['list'] = $data;
        $output['pages'] = $pages;
        $output['page'] = $page;
        $output['total'] = $total;

        return $output;
    }


    private static function saveMember($urid, $gid, $data, $usermemberid = null)
    {
        $caldata = ArrayHelper::map($data, 'key_name', 'value');
        if (empty($caldata['mv_idtype'])) {
            $caldata['mv_idtype'] = '身份证';
        }
        $idtype = $caldata['mv_idtype'];
        $idnumber = $caldata['mv_idnumber'];
        $mvname = $caldata['mv_name'];
        $mvsex = empty($caldata['mv_sex']) ? '' : $caldata['mv_sex'];
        $mvbirth = empty($caldata['mv_birth']) ? '' : $caldata['mv_birth'];

        if (empty($idtype) || empty($idnumber) || empty($mvname)) {
            $GLOBALS['errormsg'] = '用户信息缺失';
            return false;
        }

        $mvavatar = empty($caldata['mv_avatar']) ? '' : $caldata['mv_avatar'];
        if (empty($mvavatar)) {
            $mvavatar = empty($caldata['mv_avatar2']) ? '' : $caldata['mv_avatar2'];
        }

        if ($idtype == "身份证") {
            $idinfo = Utils::getIdNumberInfo($idnumber);
            if (!empty($idinfo['birth'])) {
                $mvbirth = $idinfo['birth'];
            }
            if (!empty($idinfo['sex'])) {
                if ($idinfo['sex'] == 2)
                    $mvsex = '女';
                else
                    $mvsex = '男';
            }
        }


        try {//save member info

            if (!empty($usermemberid)) {
                $usermember = UserMember::findOne(['id' => $usermemberid, 'urid' => $urid]);
                if (!empty($usermember)) {
                    $membermodel = MemberInfo::findOne(['id' => $usermember->memberid]);

                    if ($membermodel->idtype == $idtype && $membermodel->idnumber == $idnumber) {
                        if ($membermodel->name != $mvname) {
                            $GLOBALS['errormsg'] = $idtype . ':' . $idnumber . "的对应姓名错误";
                            return false;
                        } else {
                            //idtype,idnumber,mvname all same
                            //
                        }
                    } else {
                        $membermodel = new MemberInfo();
                        $membermodel->name = $mvname;
                        $membermodel->idtype = $idtype;
                        $membermodel->idnumber = $idnumber;
                        $membermodel->score = 0;
                        $membermodel->create_time = time();
                        $membermodel->avatar = $mvavatar;
                        if (!empty($mvbirth))
                            $membermodel->birth = $mvbirth;
                        if (!empty($mvsex)) {
                            if ($mvsex == '女') {
                                $membermodel->gender = '2';
                            } else {
                                $membermodel->gender = '1';
                            }
                        }

                        $membermodel->save();
                    }
                } else {
                    $GLOBALS['errormsg'] = '错误的用户信息id';
                    return false;
                }
            } else {
                $membermodel = MemberInfo::findOne(['idtype' => $idtype, 'idnumber' => $idnumber, 'name' => $mvname]);
                if (empty($membermodel)) {
                    $membermodel = new MemberInfo();
                    $membermodel->name = $mvname;
                    $membermodel->idtype = $idtype;
                    $membermodel->idnumber = $idnumber;
                    $membermodel->score = 0;
                    $membermodel->create_time = time();
                    $membermodel->avatar = $mvavatar;
                    if (!empty($mvbirth))
                        $membermodel->birth = $mvbirth;
                    if (!empty($mvsex)) {
                        if ($mvsex == '女') {
                            $membermodel->gender = '2';
                        } else {
                            $membermodel->gender = '1';
                        }
                    }
                    $membermodel->save();
                }
            }

        } catch (Exception $e) {
            $GLOBALS['errormsg'] = '保存用户信息卡错误';
            return false;
        }

        if (empty($membermodel->id)) {
            $GLOBALS['errormsg'] = '保存用户信息卡错误2';
            return false;
        }

        try {    // save user member
            if (empty($usermember)) {
                $usermember = UserMember::findOne(['urid' => $urid, 'memberid' => $membermodel->id]);
                if (empty($usermember)) {
                    $usermember = new UserMember();
                    $usermember->create_time = time();
                    $usermember->urid = $urid;
                }
            }

            $usermember->memberid = $membermodel->id;
            $usermember->memberinfos = json_encode($data);
            $usermember->status = 1;
            $ret = $usermember->save();
            if (empty($usermember->id)) {
                $GLOBALS['errormsg'] = '保存用户信息卡错误4';
                return false;
            }
        } catch (Exception $ex) {
            $GLOBALS['errormsg'] = '保存用户信息卡错误3';
            return false;
        }

        try {
            if ($membermodel->idtype == '身份证') {
                $params['idtype'] = 1;
                $params['idnumber'] = $membermodel->idnumber;
                $params['fullname'] = $membermodel->name;
                $input['jsonparams'] = json_encode($params);
                $ret = MDCUtils::mdcCall('user/savemember', $input);
            }

        } catch (\Exception $e) {

        }

        return $usermember->id;
    }

    public static function addMembers($urid, $gid, $json, $memberid)
    {
        $data = json_decode($json, true);
        if (!is_array($data)) {
            $GLOBALS['errormsg'] = '信息格式错误';
            return false;
        }

        $ret = self::saveMember($urid, $gid, $data, $memberid);

        return $ret;


    }


    public static function delMembers($urid, $memberid)
    {
        $membermodel = UserMember::findOne(['id' => $memberid, 'urid' => $urid, 'status' => 1]);
        if (empty($membermodel)) {
            return true;
        }

        $membermodel->status = 2;
        $ret = $membermodel->save();
        return $ret;
    }

    public static function judgeAge($min, $max, $timebirth)
    {
        $agemin = date("Y-09-01 00:00:00", strtotime("-$min year"));
        $agemax = date("Y-09-01 00:00:00", strtotime("-$max year"));

        $timemin = strtotime($agemin);
        $timemax = strtotime($agemax);

        if ($timebirth >= $timemax && $timebirth < $timemin) {
            return true;
        }
        return false;
    }

    private static function saveItems($rrid, $onesession, $birth, $gender)
    {
        if (empty($onesession['ssid']) || empty($onesession['items']) || empty($rrid)) {
            $GLOBALS['errormsg'] = '格式错误';
            return false;
        }

        $modelSession = MatchSession::findOne($onesession['ssid']);
        if (!isset($modelSession)) {
            $GLOBALS['errormsg'] = '场次异常';
            return false;
        }
        $items = $onesession['items'];
        if (count($items) != $modelSession->register_count) {
            $GLOBALS['errormsg'] = '格式错误2';
            return false;
        }


        $session = MatchSession::findOne(['id' => $onesession['ssid'], 'status' => 1]);
        if (empty($session)) {
            $GLOBALS['errormsg'] = '无效场次';
            return false;
        }

        $item1 = MatchSessionItem::findOne(['id' => $items[0]]);
        $existItem2 = false;
        if (count($items) > 1) {
            $existItem2 = true;
            $item2 = MatchSessionItem::findOne(['id' => $items[1]]);
        }
        if (empty($item1) || ($existItem2 && empty($item2))) {
            $GLOBALS['errormsg'] = '无效项目';
            return false;
        }

        if ($item1->gender != 3) {
            if ($gender != $item1->gender) {
                $GLOBALS['errormsg'] = '' . $item1->name . '性别不符';
                return false;
            }
        }

        if ($existItem2 && ($item2->gender != 3)) {
            if ($gender != $item2->gender) {
                $GLOBALS['errormsg'] = '' . $item2->name . '性别不符';
                return false;
            }
        }

        $check1 = self::judgeAge($item1->agemin, $item1->agemax, $birth);
        if (empty($check1)) {
            $GLOBALS['errormsg'] = '' . $item1->name . '年龄不符';
            return false;
        }

        if ($existItem2) {
            $check2 = self::judgeAge($item2->agemin, $item2->agemax, $birth);
            if (empty($check2)) {
                $GLOBALS['errormsg'] = '' . $item2->name . '年龄不符';
                return false;
            }
        }

        $newdetail = new RegisterDetail();
        $newdetail->start_time = $session->start_time;
        $newdetail->rrid = $rrid;
        $newdetail->ssid = $session->id;
        $newdetail->province = $session->province;
        $newdetail->city = $session->city;
        $newdetail->district = $session->district;
        $newdetail->stadium = $session->stadium;
        $newdetail->longitude = $session->longitude;
        $newdetail->latitude = $session->latitude;
        $newdetail->itemid1 = $item1->id;
        $newdetail->itemname1 = $item1->name;
        $newdetail->itemid2 = $existItem2 ? $item2->id : 0;
        $newdetail->itemname2 = $existItem2 ? $item2->name : '';
        $newdetail->create_time = time();
        $newdetail->save();
        return true;
    }

    public static function addPreMembers($urid, $rrid, $gid, $json, $items)
    {

        $relationmodel = RegisterRelation::findOne(['id' => $rrid, 'urid' => $urid]);
        if (empty($relationmodel)) {
            $GLOBALS['errormsg'] = '报名信息错误';
            return false;
        }

        $matchid = $relationmodel->matchid;
        $typeid = $relationmodel->typeid;

        $matchinfo = Match::findOne(['id' => $matchid, 'status' => 1]);
        if (empty($matchinfo)) {
            $GLOBALS['errormsg'] = '错误赛事id';
            return false;
        }


        $typeinfo = RegisterType::findOne(['id' => $typeid, 'matchid' => $matchid]);
        if (empty($typeinfo)) {
            $GLOBALS['errormsg'] = '错误赛事组别';
            return false;
        }


        $data = json_decode($json, true);
        if (empty($data)) {
            $GLOBALS['errormsg'] = '信息格式错误';
            return false;
        }

        $itemlist = json_decode($items, true);
        if (empty($data)) {
            $GLOBALS['errormsg'] = '场次信息格式错误';
            return false;
        }
        if ($typeinfo->type == 1) {  //单场购票
            if (count($itemlist) > 1) {
                $GLOBALS['errormsg'] = '单场购票，不能选择多个场次';
                return false;
            }
        }


        if (time() > $matchinfo->reg_end_time) {
            $GLOBALS['errormsg'] = '修改时间已结束';
            return false;
        }

        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {

            RegisterInfo::deleteAll(['typeid' => $typeid, 'rrid' => $rrid]);
            RegisterDetail::deleteAll(['rrid' => $rrid]);

            $birthmin = -2825358329;
            $gendermin = 1;
            foreach ($data as $k => $v) {

                //check required
                foreach ($v as $oneitem) {
                    if ($oneitem['required'] == 1) {
                        if ($oneitem['value'] == "") {
                            $GLOBALS['errormsg'] = $oneitem['show_name'] . "不能为空";
                            return false;
                        }
                    }
                }

                $caldata = ArrayHelper::map($v, 'key_name', 'value');
                if (empty($caldata['mv_idtype'])) {
                    $caldata['mv_idtype'] = '身份证';
                }

                //  检查身份证,性别是否正确
                if (!empty($caldata['mv_idtype']) && !empty($caldata['mv_idnumber']) && $caldata['mv_idtype'] == '身份证') {

                    $ret = Utils::validation_filter_id_card($caldata['mv_idnumber']);
                    if (empty($ret)) {
                        $transaction->rollBack();
                        $GLOBALS['errormsg'] = '身份证格式错误';
                        return false;
                    }
                }


                //先保存到成员表
                $memberid = self::saveMember($urid, $gid, $v);
                if (empty($memberid)) {
                    $transaction->rollBack();
                    return false;
                }

                $newinfo = RegisterInfo::findOne(['rrid' => $rrid, 'typeid' => $typeid, 'memberid' => $memberid]);
                if (empty($newinfo)) {
                    $newinfo = new RegisterInfo();
                }

                $newinfo->name = empty($caldata['mv_name']) ? '' : $caldata['mv_name'];
                $newinfo->idtype = empty($caldata['mv_idtype']) ? '' : $caldata['mv_idtype'];
                $newinfo->idnumber = empty($caldata['mv_idnumber']) ? '' : $caldata['mv_idnumber'];
                $newinfo->mobile = empty($caldata['mv_mobile']) ? '' : $caldata['mv_mobile'];
                $newinfo->nation = empty($caldata['mv_nation']) ? '' : $caldata['mv_nation'];
                $newinfo->size = empty($caldata['mv_size']) ? '' : $caldata['mv_size'];
                $newinfo->avatar = empty($caldata['mv_avatar']) ? '' : $caldata['mv_avatar'];
                if (empty($newinfo->avatar)) {
                    $newinfo->avatar = empty($caldata['mv_avatar2']) ? '' : $caldata['mv_avatar2'];
                }

                if (isset($newinfo->idtype) && $newinfo->idtype == '身份证') {

                    $idinfo = Utils::getIdNumberInfo($newinfo->idnumber);
                    $newinfo->birth = empty($idinfo['birth']) ? '' : $idinfo['birth'];
                    if (!empty($idinfo['sex']) && $idinfo['sex'] == 2) {
                        $newinfo->sex = '女';
                    } else {
                        $newinfo->sex = '男';
                    }

                    if (!empty($caldata['mv_sex'])) {
                        if ($caldata['mv_sex'] != $newinfo->sex) {
                            $GLOBALS['errormsg'] = '身份证性别与所填性别不一致';
                            $transaction->rollBack();
                            return false;
                        }
                    }

                    if (!empty($caldata['mv_birth']) && !empty($newinfo->birth)) {
                        $birthtime2 = strtotime($caldata['mv_birth']);
                        $birthtime1 = strtotime($newinfo->birth);
                        if ($birthtime2 != $birthtime1) {
                            $GLOBALS['errormsg'] = '身份证生日与所填生日不一致';
                            $transaction->rollBack();
                            return false;
                        }
                    }

                } else {
                    $newinfo->birth = empty($caldata['mv_birth']) ? '' : $caldata['mv_birth'];
                    $newinfo->sex = empty($caldata['mv_sex']) ? '' : $caldata['mv_sex'];
                }

                if ($typeinfo->registerlimit == 1) {
                    //根据证件号码判断是否已报名  !!!0727 by lito
                    if (self::hasRegister($matchid, $newinfo->idnumber)) {
                        $GLOBALS['errormsg'] = "错误:选手:{$newinfo->name},证件号码:{$newinfo->idnumber},已经报名了本次活动,请确认后再提交!";
                        return false;
                    }
                }

                if (strtotime($newinfo->birth) > $birthmin) {
                    $birthmin = strtotime($newinfo->birth);
                    if ($newinfo->sex == '女') {
                        $gendermin = 2;
                    } else {
                        $gendermin = 1;
                    }

                }

                $newinfo->registerinfos = json_encode($v);
                $newinfo->matchid = $matchid;
                $newinfo->typeid = $typeid;
                $newinfo->rrid = $rrid;
                $newinfo->memberid = (int)$memberid;
                $newinfo->state = 1;
                $newinfo->save();
            }

            foreach ($itemlist as $onesession) {
                $ret = self::saveItems($rrid, $onesession, $birthmin, $gendermin);
                if (empty($ret)) {
                    $transaction->rollBack();
                    return false;
                }
            }

            $relationmodel->state = RegisterRelation::PAY_NO;
            $relationmodel->save();

            $riids = RegisterInfo::find()->select('id')
                ->andFilterWhere(['rrid' => $rrid, 'typeid' => $typeid])->asArray()->all();

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $GLOBALS['errormsg'] = $e->getMessage();
            return false;

        }

        if ($riids) {
            return ['riids' => json_encode(ArrayHelper::getColumn($riids, 'id'))];
        }

        $GLOBALS['errormsg'] = '报名信息插入失败';
        return false;
    }


    private static function submitState($matchinfo, $typeinfo)
    {
        if ($matchinfo) {
            $hasregisters = RegisterRelation::find()->andFilterWhere(['matchid' => $matchinfo->id, 'state' => 1])->count();
            $totalregisters = RegisterType::find()->andFilterWhere(['matchid' => $matchinfo->id])->sum('amount');

            if (time() > $matchinfo->reg_end_time) {
                return ['state' => REG_END, 'statetips' => '报名截止'];
            } elseif (time() > $matchinfo->end_time) {
                return ['state' => MATCH_END, 'statetips' => '比赛结束'];
            } elseif ($hasregisters >= $totalregisters) {
                return ['state' => REG_OVER, 'statetips' => '报名组名额已满' . $hasregisters];
            }
            return true;
        }

        return ['state' => MATCH_END, 'statetips' => '比赛结束'];
    }

    public static function hasRegister($matchid, $idnumber)
    {

        $ret = RegisterInfo::find()->from(RegisterInfo::tableName() . ' as a')
            ->joinWith('registerRelation as b', false)
            ->andFilterWhere(['a.matchid' => $matchid, 'a.idnumber' => $idnumber, 'a.state' => 1, 'b.state' => 1])
            ->one();

        if (empty($ret)) return false;
        return true;
    }

    public static function batchUpdateRegisterInfosState($usercode, $ids)
    {

        $data = '';
        $count = count($ids) - 1;
        foreach ($ids as $k => $v) {
            $data .= "($v,1,'{$usercode[$k]}')";
            if ($k < $count) $data .= ",";
        }

        $sql = "INSERT INTO mcloud_register_info (id,state,usercode) VALUES $data "
            . "ON DUPLICATE KEY UPDATE state=VALUES(state) , usercode=VALUES(usercode) ";

        $connection = Yii::$app->db;
        return $connection->createCommand($sql)->execute();


    }

    public static function checkRegisterMemberInfos($urid, $matchid, $gid, $rrid, $riids)
    {

        $matchinfo = Match::findOne(['id' => $matchid, 'gid' => $gid, 'status' => 1]);
        if (empty($matchinfo)) {
            $GLOBALS['errormsg'] = '错误赛事id';
            return false;
        }

        $relationmodel = RegisterRelation::findOne(['id' => $rrid, 'urid' => $urid]);
        if (empty($relationmodel)) {
            $GLOBALS['errormsg'] = '报名信息错误';
            return false;
        }

        $typeid = $relationmodel->typeid;

        $typeinfo = RegisterType::findOne(['id' => $typeid, 'matchid' => $matchid]);
        if (empty($typeinfo)) {
            $GLOBALS['errormsg'] = '错误赛事组别';
            return false;
        }

        $matchState = self::submitState($matchinfo, $typeinfo);
        if (is_array($matchState) && $matchState['state'] != REG_PRO) {
            $GLOBALS['errormsg'] = $matchState['statetips'];
            return false;
        }

        $rridattr = json_decode($riids, true);
        if (empty($rridattr)) {
            $GLOBALS['errormsg'] = '没有参赛选手,提交失败!';
            return false;
        }

        $prememberlist = RegisterInfo::find()->andFilterWhere(['in', 'id', $rridattr])->asArray()->all();

        if (empty($prememberlist)) {
            $GLOBALS['errormsg'] = '没有参赛选手,提交失败2!';
            return false;
        }

        $female = 0;
        $male = 0;

        if ($prememberlist) {
            foreach ($prememberlist as $k => $v) {

                if ($v['sex'] == '女') {
                    $female++;
                } else {
                    $male++;
                }


                //根据证件号码判断是否已报名
                if ($v['idtype'] && $v['idnumber']) {

                    if ($typeinfo->registerlimit == 1) {
                        if (self::hasRegister($matchid, $v['idnumber'])) {
                            $GLOBALS['errormsg'] = "错误:选手:{$v['name']},证件号码:{$v['idnumber']},已经报名了本次活动,请确认后再提交!";
                            return false;
                        }
                    }
                }
            }
        }
        if ($female > $typeinfo->fmaxcount || $female < $typeinfo->fmincount) {
            $GLOBALS['errormsg'] = "错误:要求女性选手:{$typeinfo->fmincount}-{$typeinfo->fmaxcount}名,提交名单中只有女性选手{$female}名!";
            return false;
        }


        $connection = Yii::$app->db;
        $trans = $connection->beginTransaction();

        try {
            if ($prememberlist) {
                //dbMatchRegister::updateDbAllRegisterInfo(['state'=>REGISTER_NO],['matchid'=>$matchid,'typeid'=>$typeid,'rgid'=>$rgid]);
                RegisterInfo::updateAll(array('state' => 2), ['matchid' => $matchid, 'typeid' => $typeid, 'rrid' => $rrid]);
                foreach ($prememberlist as $k => $v) {
                    $ids[] = $v['id'];
                    $usercode[] = $typeid . '-' . $v['id'];
                }
                $res = self::batchUpdateRegisterInfosState($usercode, $ids);
            }
            $trans->commit();
        } catch (Exception $e) {
            $trans->rollBack();
            $GLOBALS['errormsg'] = '出错啦';
            return false;
        }

        return true;
    }

    public static function addTeamPreMembers($urid, $gid, $matchid, $rrid, $json, $riid)
    {

        $matchinfo = Match::findOne(['id' => $matchid, 'gid' => $gid, 'status' => 1]);
        if (empty($matchinfo)) {
            $GLOBALS['errormsg'] = '错误赛事id';
            return false;
        }

        $relationmodel = RegisterRelation::findOne(['id' => $rrid, 'urid' => $urid]);
        if (empty($relationmodel)) {
            $GLOBALS['errormsg'] = '报名信息错误';
            return false;
        }

        $typeid = $relationmodel->typeid;

        $typeinfo = RegisterType::findOne(['id' => $typeid, 'matchid' => $matchid]);
        if (empty($typeinfo)) {
            $GLOBALS['errormsg'] = '错误赛事组别';
            return false;
        }

        $data = json_decode($json, true);
        if (empty($data)) {
            $GLOBALS['errormsg'] = '信息格式错误';
            return false;
        }

        if (time() > $matchinfo->reg_end_time) {
            $GLOBALS['errormsg'] = '修改时间已结束';
            return false;
        }


        foreach ($data as $k => $v) {
            $caldata = ArrayHelper::map($v, 'key_name', 'value');

            if (!empty($caldata['mv_idtype']) && !empty($caldata['mv_idnumber']) && $caldata['mv_idtype'] == '身份证') {

                $ret = Utils::validation_filter_id_card($caldata['mv_idnumber']);
                if (empty($ret)) {
                    $GLOBALS['errormsg'] = '身份证格式错误';
                    return false;
                }
            }

            //先保存到成员表
            $memberid = self::saveMember($urid, $gid, $v);
            if (empty($memberid)) {
                $GLOBALS['errormsg'] = '保存信息错误';
                return false;
            }

            $idtype = $caldata['mv_idtype'];
            $idnumber = $caldata['mv_idnumber'];

            if ($typeinfo->registerlimit == 1) {
                $newinfo = RegisterInfo::findOne(['matchid' => $matchid, 'idtype' => $idtype, 'idnumber' => $idnumber]);
                if (!empty($newinfo)) {
                    $GLOBALS['errormsg'] = "错误:选手:{$v->name},证件号码:{$v->idnumber},已经报名了本次活动,请确认后再提交!";
                    return false;
                }
            }

            $newinfo = new RegisterInfo();
            $newinfo->name = empty($caldata['mv_name']) ? '' : $caldata['mv_name'];
            $newinfo->idtype = empty($caldata['mv_idtype']) ? '' : $caldata['mv_idtype'];
            $newinfo->idnumber = empty($caldata['mv_idnumber']) ? '' : $caldata['mv_idnumber'];
            $newinfo->mobile = empty($caldata['mv_mobile']) ? '' : $caldata['mv_mobile'];
            $newinfo->nation = empty($caldata['mv_nation']) ? '' : $caldata['mv_nation'];
            $newinfo->size = empty($caldata['mv_size']) ? '' : $caldata['mv_size'];
            $newinfo->avatar = empty($caldata['mv_avatar']) ? '' : $caldata['mv_avatar'];

            if (isset($newinfo->idtype) && $newinfo->idtype == '身份证') {

                $idinfo = Utils::getIdNumberInfo($newinfo->idnumber);
                $newinfo->birth = empty($idinfo['birth']) ? '' : $idinfo['birth'];
                if (!empty($idinfo['sex']) && $idinfo['sex'] == 2) {
                    $newinfo->sex = '女';
                } else {
                    $newinfo->sex = '男';
                }
            } else {
                $newinfo->birth = empty($caldata['mv_birth']) ? '' : $caldata['mv_birth'];
                $newinfo->sex = empty($caldata['mv_sex']) ? '' : $caldata['mv_sex'];
            }

            $newinfo->registerinfos = json_encode($v);
            $newinfo->matchid = $matchid;
            $newinfo->typeid = $typeid;
            $newinfo->rrid = $rrid;
            $newinfo->memberid = (int)$memberid;
            $ret = $newinfo->save();
            if (empty($ret)) {
                $GLOBALS['errormsg'] = '保存信息错误';
                return false;
            }
        }

        $riids = RegisterInfo::find()->select('id')
            ->andFilterWhere(['rrid' => $rrid, 'typeid' => $typeid])->asArray()->all();

        if ($riids) {
            return ['riids' => json_encode(ArrayHelper::getColumn($riids, 'id'))];
        }

        return false;

    }


    public static function mymemberlist($urid, $page, $app)
    {

        $query = UserMember::find()->select('a.memberid, b.name, b.gender, b.idtype, b.idnumber, b.birth, b.avatar, b.score')
            ->from(UserMember::tableName() . ' as a')
            ->joinWith('memberinfo as b', false)
            ->andFilterWhere(['a.urid' => $urid, 'a.status' => 1]);

        $total = $query->count();
        $limit = 20;

        $pages = ceil($total / $page);
        $offset = ($page - 1) * $limit;

        $list = $query->orderBy('a.id desc')->limit($limit)->offset($offset)
            ->asArray()->all();


        $output['pages'] = $pages;
        $output['page'] = $page;
        $output['list'] = $list;

        return $output;
    }

    public static function memberscores($memberid, $page, $app)
    {
        $query = MemberScoreRecord::find()->select('memberid, type, value, description, create_time')
            ->andFilterWhere(['memberid' => $memberid]);

        $total = $query->count();
        $limit = 20;

        $pages = ceil($total / $page);
        $offset = ($page - 1) * $limit;

        $list = $query->orderBy('id desc')->limit($limit)->offset($offset)
            ->asArray()->all();

        $output['pages'] = $pages;
        $output['page'] = $page;
        $output['list'] = $list;

        return $output;
    }

    public static function addMember($urid, $idnumber, $idtype, $name)
    {
        $memberinfo = MemberInfo::findOne(['idtype' => $idtype, 'idnumber' => $idnumber, 'name' => $name]);
        if (empty($memberinfo)) {
            $GLOBALS['errormsg'] = '没有该选手信息';
            return false;
        }

        $usermember = UserMember::findOne(['memberid' => $memberinfo->id, 'urid' => $urid]);
        try {

            if (empty($usermember)) {
                $usermember = new UserMember();
                $usermember->urid = $urid;
                $usermember->memberid = $memberinfo->id;
                $usermember->status = 1;
                $usermember->create_time = time();
                $ret = $usermember->save();
                if (empty($ret)) {
                    $GLOBALS['errormsg'] = '保存失败';
                    return false;
                }
            } else {
                if ($usermember->status != 1) {
                    $usermember->status = 1;
                    $ret = $usermember->save();
                    if (empty($ret)) {
                        $GLOBALS['errormsg'] = '保存失败3';
                        return false;
                    }
                }
            }
        } catch (\Exception $e) {
            $GLOBALS['errormsg'] = '保存失败2';
            return false;
        }

        return true;
    }

    public static function delMember($urid, $memberid)
    {
        $usermember = UserMember::findOne(['memberid' => $memberid, 'urid' => $urid, 'status' => 1]);
        if (!empty($usermember)) {
            $usermember->status = 2;
            $ret = $usermember->save();
            if (empty($ret)) {
                $GLOBALS['errormsg'] = '删除失败';
                return false;
            }
        }

        return true;
    }

    /**
     * 通过邀请码绑定角色
     * @param $params
     * @return array
     */
    public static function getJurisdiction($params)
    {
        $channel_id = Utils::ecbDecrypt(Yii::$app->params['channelIDKey'], $params['channel_id']);
        if ($params['type'] == 1) {
            $address = Address::findOneArray(['account' => $params['invitation_code']]);
            if (!$address) {
                return [false, '验证码不存在'];
            }
            $is_checker = 2;
            $is_owner = $address['id'];
        } else {
            $check = CheckInfo::findOneArray(['mobile' => $params['invitation_code']]);
            if (!$check) {
                return [false, '手机号不存在'];
            }
            $is_checker = 1;
            $is_owner = 0;
            //检察员还是需要绑定一下channel_id
            CheckInfo::updateAll(['user_channel_id' => $channel_id], ['mobile' => $params['invitation_code'], 'status' => 1]);
        }
//        $channel_id =  $params['channel_id'];
        $extra_model = UserChannelExtra::findOne(['user_channel_id' => $channel_id, 'status' => UserChannelExtra::NORMAL_STATUS]);
        if (!$extra_model) {
            $extra_model = new UserChannelExtra();
            //如果用户渠道额外信息表没有名字还得把名字给他加上
            $user_channel = UserChannel::findOneArray(['id' => $channel_id]);
            $user = UserInfo::findOne(['urid' => $user_channel['urid'], 'status' => 1]);
            $extra_model->realname = $user->nickname;
        }
        $extra_model->user_channel_id = $channel_id;
        $extra_model->is_checker = $is_checker;
        $extra_model->is_owner = $is_owner;
        if ($extra_model->save()) {
            return [true, '绑定成功'];
        } else {
            return [false, $extra_model->getErrors()];
        }
    }

    /**
     * 不同角色的订单列表
     * @param $params
     * @return array|string|\yii\db\ActiveRecord[]
     */
    public static function workOrderList($params)
    {
        $page_info = Helper::makePageInfo($params, 20);
        $where_data = [
            [
                [
                    'status' => 'status',
                    'type' => 'type',
                    'examine_status' => 'examine_status',
                ], '='
            ],
        ];
        $where = Helper::makeWhere($where_data, $params);
        $channel_id = Utils::ecbDecrypt(Yii::$app->params['channelIDKey'], $params['channel_id']);
//        $channel_id = $params['channel_id'];
        if ($params['job_type'] == 1) {
            $extra_model = UserChannelExtra::findOneArray(['user_channel_id' => $channel_id, 'status' => UserChannelExtra::NORMAL_STATUS]);
            if (!$extra_model) {
                return [false, '该场馆负责人不存在，请检查'];
            }
            $address_id = $extra_model['is_owner'];
            $where[] = ['venue_id' => $address_id];
            $order = 'examine_status asc,status asc,create_time desc';
        } else {
            $is_super_checker = UserChannelExtra::findOneArray(['status' => UserChannelExtra::NORMAL_STATUS, 'user_channel_id' => $channel_id],
                    ['is_super_checker'])['is_super_checker'] ?? UserChannelExtra::SUPER_CHECKER_NO;
            if ($is_super_checker != UserChannelExtra::SUPER_CHECKER_YES) {
                $where[] = ['or', ['commit_id' => $channel_id], ['principal_channel_id' => $channel_id]];
            }
            $order = 'examine_status asc,status desc,create_time desc';
        }
//        $with = [['workOrder' => function ($query) {
//            $query->select('*');
//        }]];
        $re = WorkOrderIndex::findJoin('', [], ['*'], $where, $asArray = true, $all = true, $order,
            $index = '', $group = '', $with = [], $pages = $page_info);

        foreach ($re['list'] as &$v) {
            $v['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
//            $tmp = $v;
//            UserChannelExtra::findOneArray();
//            foreach ($tmp as $k => $v1){
//                $tmp[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
//            }
        }
        return $re;
    }

    public static function workOrderInfo($params)
    {
        $data = WorkOrder::findAllArray(['index_id' => $params['id']]);
        $info = WorkOrderIndex::findAllArray(['id' => $params['id']]);
        foreach ($data as &$v) {
            if ($v['status'] != WorkOrder::PENDING && $v['feedback_status'] == WorkOrder::NOT_APPROVED) {
                $v['job'] = '场馆管理员';
                $user = UserChannelExtra::findOneArray(['is_owner' => $v['venue_id'], 'status' => UserChannelExtra::NORMAL_STATUS]);
                $v['operation_name'] = $user['realname'];
            } else {
                $v['job'] = '检查人员';
                $user = UserChannelExtra::findOneArray(['user_channel_id' => $v['commit_id']]);
                $v['operation_name'] = $user['realname'];
            }
            $v['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
        }
        return [
            'info' => $info,
            'list' => $data
        ];
    }

    public static function workOrderHistory($params)
    {
        $data = WorkOrderHistory::findAllArray(['work_order_id' => $params['id']]);
        foreach ($data as &$v) {
            $v['operation_status_cn'] = WorkOrderHistory::OPERATION_STATUS_CN[$v['operation_status']];
            $v['operation_type_cn'] = WorkOrderHistory::OPERATION_TYPE_CN[$v['operation_type']];
            $v['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
        }
        return $data;
    }

    /**
     * 场馆整改
     * @param $params
     * @return array
     */
    public static function workOrderHandle($params)
    {
        $model = WorkOrder::findOne(['id' => $params['id']]);
        $model->handle_notes = $params['handle_notes'];
        $model->handle_img = $params['handle_img'];
        $model->status = $params['status'];
        //应前端要求的修改
        $model->feedback_status = 0;
        if ($model->save()) {
            //要保存操作记录
            $history_model = new WorkOrderHistory();
            $history_model->work_order_id = $params['id'];
            $user = UserChannelExtra::findOneArray(['is_owner' => $model->venue_id, 'status' => UserChannelExtra::NORMAL_STATUS]);
            $history_model->operation_id = $user['user_channel_id'] ?? 0;
            $history_model->operation_name = $user['realname'] ?? '';
            $history_model->operation_type = WorkOrderHistory::LEADER;
            $history_model->operation_status = WorkOrderHistory::SUBMITTED;
            $history_model->handle_img = $model->handle_img;
            $history_model->handle_notes = $model->handle_notes;
            $history_model->feedback_notes = $model->feedback_notes;
            $history_model->create_time = time();
            if ($history_model->save()) {
                //如果子工单全部处理完成需要将主工单状态修改
                $change_status = WorkOrder::findOneArray(['status' => WorkOrder::PENDING, 'index_id' => $model->index_id]);
                if (!$change_status) {
                    WorkOrderIndex::updateAll(['status' => WorkOrderIndex::NOT_APPROVE], ['id' => $model->index_id]);
                }
                return [true, '处理成功'];
            } else {
                return [false, $history_model->getErrors()];
            }
        } else {
            return [false, $model->getErrors()];
        }
    }

    /**
     * 场馆整改后的处理
     * @param $params
     * @return array
     */
    public static function workOrderFinalHandle($params)
    {
        //先验证一下是否有处理工单的权限
        $channel_id = Utils::ecbDecrypt(Yii::$app->params['channelIDKey'], $params['channel_id']);
        $check = WorkOrderIndex::findOneArray(['principal_channel_id' => [0, $channel_id]]);
        if (!$check) {
            return [false, '没有处理工单的权限'];
        }
        $model = WorkOrder::findOne(['id' => $params['id']]);
        $model->feedback_status = $params['feedback_status'];
        $model->feedback_notes = $params['feedback_notes'];
        if ($model->save()) {
            //保存操作记录
            $history_model = new WorkOrderHistory();
            $history_model->work_order_id = $params['id'];
            $user = UserChannelExtra::findOneArray(['user_channel_id' => $model->commit_id, 'status' => UserChannelExtra::NORMAL_STATUS]);
            $history_model->operation_id = $user['user_channel_id'];
            $history_model->operation_name = $user['realname'];
            $history_model->operation_type = WorkOrderHistory::INSPECTOR;
            $history_model->operation_status = $params['feedback_status'] == 1 ? WorkOrderHistory::APPROVED : WorkOrderHistory::AUDIT_FAILED;
            $history_model->handle_img = $model->handle_img;
            $history_model->handle_notes = $model->handle_notes;
            $history_model->feedback_notes = $model->feedback_notes;
            $history_model->create_time = time();
            if ($history_model->save()) {
                //如果不认可整改，则等所有的子项处理完之后重置不同意的子项
                $change_status = WorkOrder::findOneArray(['and', ['!=', 'status', WorkOrder::CLOSED], ['feedback_status' => WorkOrder::NOT_APPROVED], ['index_id' => $model->index_id]]);
                if (!$change_status) {
                    $has_disagree = WorkOrder::findOneArray(['and', ['feedback_status' => WorkOrder::DISAGREE], ['index_id' => $model->index_id]]);
                    if ($has_disagree) {
                        //如果有不同意的就更新主工单状态为未处理，并且把没有通过的子订单也刷新到初始状态
                        WorkOrderIndex::updateAll(['status' => WorkOrderIndex::UNTREATED], ['id' => $model->index_id]);
                        WorkOrder::updateAll(['status' => WorkOrder::PENDING, 'feedback_status' => WorkOrder::NOT_APPROVED,
                            'handle_notes' => '', 'handle_img' => '', 'feedback_notes' => '',
                        ], ['id' => $model->index_id, 'feedback_status' => WorkOrder::DISAGREE]);
                    } else {
                        //如果没有不同意的就更新主工单状态为已审核通过
                        WorkOrderIndex::updateAll(['examine_status' => WorkOrderIndex::APPROVED], ['id' => $model->index_id]);
                    }
                }
                return [true, '处理成功'];
            } else {
                return [false, $history_model->getErrors()];
            }
        } else {
            return [false, $model->getErrors()];
        }
    }

    /**
     * 检察员检查次数
     * @param $params
     * @return int[]
     */
    public static function workOrderCheckNum($params)
    {
        $channel_id = Utils::ecbDecrypt(Yii::$app->params['channelIDKey'], $params['channel_id']);
//        $channel_id = $params['channel_id'];
        $day = date('Y-m-d');
        $mouth = date('m');
        $week = (int)date('W');
        $day_num = AddressCheck::findOneArray(["FROM_UNIXTIME(create_time,'%Y-%m-%d')" => $day, 'user_channel_id' => $channel_id], ['count(*) num'])['num'] ?? 0;
        $mouth_num = AddressCheck::findOneArray(["FROM_UNIXTIME(create_time,'%m')" => $mouth, 'user_channel_id' => $channel_id], ['count(*) num'])['num'] ?? 0;
        $week_num = AddressCheck::findOneArray(["WEEK(FROM_UNIXTIME(create_time,'%Y-%m-%d'),1)" => $week, 'user_channel_id' => $channel_id], ['count(*) num'])['num'] ?? 0;
        return [
            'day_num' => $day_num,
            'mouth_num' => $mouth_num,
            'week_num' => $week_num,
        ];
    }
}
