<?php

/**
 * Created by wayne.
 * Date: 2019/2/1
 * Time: 1:27 PM
 */

namespace api\dbmodels;


use api\controllers\Controller;
use api\library\Wxpay\Pay_Wx_Utils;
use api\models\User as MUser;
use api\models\UserChannel;
use api\models\UserInfo;
use api\models\WxTemplate;

use common\helpers\FileLockAPI;
use common\helpers\Utils;

use common\models\Event;
use common\models\EventGroup;
use common\models\EventInfo;
use common\models\EventRelation;
use common\models\EventType;
use Yii;
use yii\db\Exception;
use yii\web\ServerErrorHttpException;

class dbEventPay
{

    private function updateInfoAfterPay($orderno, $tradeno, $paytype, $paytime, $payinfo)
    {

        if (empty($orderno))
            return false;

        $order = EventRelation::find()->andFilterWhere(['order_no' => $orderno])->one();
        if (empty($order)) return false;
        $order->payinfo = $payinfo;
        $order->state = EventRelation::PAY_YES;
        $order->paytime = $paytime;
        $order->trade_no = $tradeno;
        $ret = $order->save();
        if ($ret) {
            $matchinfo = Event::findOne(['id' => $order->matchid]);
            self::sendRegisterNotice($order, $matchinfo->title, $order->app);
            return true;
        }

        return false;

    }

    public static function dueWxpayNotify()
    {

        $wxpay = new Pay_Wx_Utils();

        $notify = [];
        $result = $wxpay->paraseWxNotify($notify);

        if (isset($notify['out_trade_no']) && isset($notify['trade_no'])
            && isset($notify['gmt_payment']) && isset($notify['trade_status']) && $notify['trade_status'] == 'SUCCESS') {

            $data = self::updateInfoAfterPay($notify['out_trade_no'], $notify['trade_no'], PAY_WX, $notify['gmt_payment'], $notify['payinfo']);
        }

        return $result;
    }

    public static function dueAlipayNotify()
    {

        $alipay = new Pay_Ali_Utils();
        $notify = [];

        $result = $alipay->paraseAliNotify($notify);

        if (isset($notify['out_trade_no']) && isset($notify['trade_no']) &&
            isset($notify['gmt_payment']) && isset($notify['trade_status'])
            && $notify['trade_status'] == 'TRADE_SUCCESS') {

            $data = self::updateInfoAfterPay($notify['out_trade_no'], $notify['trade_no'], PAY_ALI, $notify['gmt_payment'], $notify['payinfo']);
        }

        return $result;
    }

    private function getAlipayRequest($relationmodel, $matchname, $paymethod, $returnurl)
    {
        $goodsname = $matchname . '(' . $relationmodel->typename . ')' . '报名费';
        $oinfo = [
            'out_trade_no' => $relationmodel->order_no,
            'subject' => str_replace(' ', '', $goodsname),
            'body' => str_replace(' ', '', '活动报名'),
            'total_amount' => (string)round($relationmodel->fees, 2),
            'timeout_express' => "5m",//5分钟支付失效
        ];

        if ($paymethod == PAY_METHOD_WAP) {
            $oinfo['product_code'] = "QUICK_WAP_WAY";
        } elseif ($paymethod == PAY_METHOD_PC) {
            $oinfo['product_code'] = "FAST_INSTANT_TRADE_PAY";
        }

        $alipay = new Pay_Ali_Utils();
        return $alipay->getAliRequest($oinfo, $returnurl, $paymethod);
    }

    public static function registerPrePayOrder($urid, $matchid, $typeid, $gid, $app, $apph5 = null, $invitecode = null)
    {

        $userinfo = UserInfo::findOne(['urid' => $urid]);
        if (empty($userinfo)) {
            $GLOBALS['errormsg'] = '错误用户';
            return false;
        }

        $usermodel = MUser::findOne(['id' => $urid]);
        if (empty($usermodel)) {
            $GLOBALS['errormsg'] = '错误用户';
            return false;
        }

        $matchinfo = Event::findOne(['id' => $matchid, 'status' => 1]);

        if (empty($matchinfo)) {
            $GLOBALS['errormsg'] = '错误赛事id';
            return false;
        }

        $typeinfo = EventType::findOne(['id' => $typeid, 'matchid' => $matchid]);
        if (empty($typeinfo)) {
            $GLOBALS['errormsg'] = '错误赛事组别';
            return false;
        }
        //PS 志愿者检测 ptsa无需次功能
        // if(!((new EventType())->checkLimit($typeinfo,$urid,$gid))){
        //     $GLOBALS['errormsg'] = '错误赛事组别';
        //     return false;
        // }


        $totals = EventRelation::find()->andFilterWhere(['matchid' => $matchid, 'typeid' => $typeid, 'state' => 1, 'ischeck' => 1])->count();

        if ($totals >= $typeinfo->amount || $typeinfo->num<=0) {
            $GLOBALS['errormsg'] = '已报满';
            return false;
        }

        if (time() > $matchinfo->reg_end_time || time() < $matchinfo->reg_start_time) {
            $GLOBALS['errormsg'] = '报名未开始或者报名已结束';
            return false;
        }

        if ($typeinfo->registerlimit > 0) {
            $orderold = EventRelation::find()->select('id')
                ->andFilterWhere(['urid' => $urid, 'matchid' => $matchid, 'state' => 1, 'typeid' => $typeid])->count();
            if ($orderold >= $typeinfo->registerlimit) {
                $GLOBALS['errormsg'] = '您已超过了比赛报名限额!';
                $GLOBALS['errorcode'] = Controller::REPEAT_ERR;
                return false;
            }
        }


        //判断InviteCode合法性
        if ($typeinfo->isinvited == 1) {
            if (empty($invitecode)) {
                $GLOBALS['errormsg'] = '邀请码不能为空!';
                return false;
            }
            $mim = MatchInvitecode::find()
                ->andWhere(['state' => 1, 'code' => $invitecode])
                ->andWhere(['>', 'surplus_quantity', 0])
                ->one();
            if (empty($mim)) {
                $GLOBALS['errormsg'] = '邀请码输入有误或已达到使用上限!';
                return false;
            }
//            $mim->updateCounters(['surplus_quantity' => -1]);
        }


        $connection = Yii::$app->db;
        $tranns = $connection->beginTransaction();
        try {


            $groupmodel = new EventGroup();
            $groupmodel->urid = $urid;
            $groupmodel->category_id = $matchinfo->category_id;
            $groupmodel->matchid = $matchid;
            $groupmodel->typeid = $typeid;
            $groupmodel->groupcode = Utils::genGroupCode();
            $groupmodel->save();

            $relation = new EventRelation();
            $relation->matchid = $matchid;
            if (empty($apph5)) {
                $relation->app = $app;
            } else {
                $relation->app = $apph5;
            }
            $relation->gid = $matchinfo->gid;
            $relation->rgid = $groupmodel->id;
            $relation->typeid = $typeid;
            $relation->urid = $urid;
            $relation->typename = $typeinfo->title;
            $relation->specfees = 0;
            $relation->orgfees = $typeinfo->fees;
            $relation->fees = $typeinfo->fees;
            //邀请码
            $relation->invitecode = $invitecode;


            if (!empty($usermodel->phone)) {
                $relation->mobile = $usermodel->phone;
            }
            $relation->name = $userinfo->nickname;
            $relation->type = $typeinfo->type;
            if ($typeinfo->needcheck == EventType::NEEDCHECK_CHECKFIRST) {
                $relation->ischeck = EventRelation::REGISTER_CHECK_UNPASS;
            } else if ($typeinfo->needcheck == EventType::NEEDCHECK_CHECKLAST) {
                $relation->ischeck = EventRelation::REGISTER_CHECK_UNPASS;
            } else {
                $relation->ischeck = EventRelation::REGISTER_CHECK_PASS;
            }

            $relation->state = EventRelation::PAY_NOVALID;
            $relation->create_time = time();
            $relation->save();
            $tranns->commit();
        } catch (Exception $e) {
            $tranns->rollBack();
            $GLOBALS['errormsg'] = '异常错误';
            return false;
        }

        $data['showmessage'] = '成功';
        $data['state'] = '1';
        $data['rgid'] = $groupmodel->id;
        $data['rrid'] = $relation->id;
        return $data;

    }


    public static function payOrder($app, $urid, $gid, $matchid, $typeid, $rrid, $paytype, $paymethod, $returnurl, $code = null, $seosource = null, $speccode = null, $apph5 = 0)
    {

        $userinfo = UserInfo::findOne(['urid' => $urid]);
        if (empty($userinfo)) {
            $GLOBALS['errormsg'] = '错误用户';
            return false;
        }

        $matchinfo = Event::findOne(['id' => $matchid, 'status' =>1]);
        if (empty($matchinfo)) {
            $GLOBALS['errormsg'] = '错误赛事id';
            return false;
        }

        $typeinfo = EventType::findOne(['id' => $typeid, 'matchid' => $matchid]);
        if (empty($typeinfo)) {
            $GLOBALS['errormsg'] = '错误赛事组别';
            return false;
        }
        // if(!((new EventType())->checkLimit($typeinfo,$urid,$gid))){
        //     $GLOBALS['errormsg'] = '错误赛事组别';
        //     return false;
        // }
        $relationmodel = EventRelation::findOne(['id' => $rrid, 'urid' => $urid]);
        if (empty($relationmodel)) {
            $GLOBALS['errormsg'] = '报名信息错误';
            return false;
        }

        if ($typeinfo->type != 3) {  // 团队报名忽略报名成员不能为空的约束
            $infos = EventInfo::findOne(['rrid' => $rrid]);
            if (empty($infos)) {
                $GLOBALS['errormsg'] = '缺少报名用户信息';
                return false;
            }
        }

        if ($relationmodel->state == EventRelation::PAY_YES) {
            $GLOBALS['errormsg'] = '已支付订单，无需重复支付';
            return false;
        }

        if ($relationmodel->state == EventRelation::PAY_RETURN) {
            $GLOBALS['errormsg'] = '已退款订单无法重新支付';
            return false;
        }

        if ($typeinfo->registerlimit > 0) {
            $orderold = EventRelation::find()->select('id')
                ->andFilterWhere(['urid' => $urid, 'matchid' => $matchid, 'state' => 1, 'typeid' => $typeid])->count();
            if ($orderold >= $typeinfo->registerlimit) {
                $GLOBALS['errormsg'] = '您已超过了比赛报名限额!';
                $GLOBALS['errorcode'] = Controller::REPEAT_ERR;
                return false;
            }

        }

        if (time() > $matchinfo->reg_end_time || time() < $matchinfo->reg_start_time) {
            $GLOBALS['errormsg'] = '报名未开始或者报名已结束';
            return false;
        }

        $rgid = $relationmodel->rgid;
        $groupinfo = EventGroup::findOne(['id' => $rgid]);
        if (empty($groupinfo)) {
            $GLOBALS['errormsg'] = '获取信息失败';
            return false;
        }


        if ($typeinfo->type == 3) {//团队
            //!! by Lito mcloud 只限制团队名称为必填
//            if(empty($groupinfo->unit)&& empty($groupinfo->regname) && empty($groupinfo->leader)&& empty($groupinfo->mobile)){
            if (empty($groupinfo->regname)) {
                $GLOBALS['errormsg'] = '请填写团队信息后再提交!';
                return false;
            }

            //如果不是高百,检查是否有成员信息
            $infocount = EventInfo::find()->andFilterWhere(['rgid' => $rgid, 'typeid' => $typeid])->count();

            if ($typeinfo->allforpay == 2 && ($infocount > $typeinfo->maxcount || $infocount < $typeinfo->mincount)) {
                $GLOBALS['errormsg'] = "请按要求提交{$typeinfo->mincount}至{$typeinfo->maxcount}选手信息!";
                return false;
            }
        }

        if ($typeinfo->needcheck == EventType::NEEDCHECK_CHECKFIRST) {
            if ($relationmodel->ischeck != EventRelation::REGISTER_CHECK_PASS) {
                $GLOBALS['errormsg'] = '还未审核通过，请耐心等待';
                return false;
            }
        }

        $path = Yii::$app->getRuntimePath();
        $lockname = 'RelationOrder' . '_' . $relationmodel->id . '.lock';
        $lockfile = $path . '/' . $lockname;

        $fp = FileLockAPI::getFileLock($lockfile);
        if (empty($fp)) {
            $GLOBALS['errormsg'] = '重复支付';
            return false;
        }
        $connection = Yii::$app->db;
        $tranns = $connection->beginTransaction();
        try {

            //判断InviteCode合法性
            if (!empty($relationmodel->invitecode)) {
                $mim = MatchInvitecode::find()
                    ->andWhere(['state' => 1, 'code' => $relationmodel->invitecode])
                    ->andWhere(['>', 'surplus_quantity', 0])
                    ->one();
                if (empty($mim)) {
                    $GLOBALS['errormsg'] = '邀请码输入有误或已达到使用上限!';
                    return false;
                }
                if(!$mim->updateCounters(['surplus_quantity' => -1])){
                    $GLOBALS['errormsg'] = '邀请码输入有误或已达到使用上限!';
                    return false;
                }
            }


            if (empty($relationmodel->order_no)) {
                $typeinfo->updateCounters(['num' => -1]);
            }

            $relationmodel->paytype = $paytype;
            $relationmodel->specfees = 0;
            $relationmodel->orgfees = $typeinfo->fees;
            $relationmodel->fees = $typeinfo->fees;

            if (empty($relationmodel->fees) || $relationmodel->fees == '0.00') {

                $relationmodel->state = EventRelation::PAY_YES;
                $relationmodel->paytype = PAY_FREE;
                $relationmodel->paytime = date('Y-m-d H:i:s', time());

                if (empty($relationmodel->order_no))
                    $relationmodel->order_no = Utils::build_order_no();

            } else {

                $relationmodel->paytype = $paytype;
                $relationmodel->state = EventRelation::PAY_NO;

                if (empty($relationmodel->order_no)) {   //first time pay
                    $relationmodel->order_no = Utils::build_order_no();
                    $relationmodel->lastpaytime = time();
                } else {
                    if (!empty($relationmodel->lastpaytime) && $relationmodel->lastpaytime < time() - 60 * 5) { //pay time out,renew no. and lastpaytime
                        $relationmodel->order_no = Utils::build_order_no();
                        $relationmodel->lastpaytime = time();
                    }
                }

            }

            if ($seosource) {
                $relationmodel->seosource = is_numeric($seosource) ? $seosource : 1;
            }

            $relationmodel->save();
            $tranns->commit();
        } catch (Exception $e) {
            $tranns->rollBack();
            $GLOBALS['errormsg'] = "报名名额已满！";
            return false;
        }

        if ($relationmodel->state == EventRelation::PAY_YES) {
            $data['paystate'] = EventRelation::PAY_YES;

            if ($relationmodel->ischeck == EventRelation::REGISTER_CHECK_PASS) {
                if (empty($apph5)) {
                    self::sendRegisterNotice($relationmodel, $matchinfo->title, $app);
                } else {
                    self::sendRegisterNotice($relationmodel, $matchinfo->title, $apph5);
                }
            }

        } else {
            //获取支付凭证

            $shipment = '';
            if (empty($apph5)) {
                if ((int)$paytype === PAY_ALI) {

                } elseif ((int)$paytype === PAY_WX) {
                    $notifyurl  =   Yii::$app->params['eventwxpaynotify'];
                    $shipment = self::getWxpayRequest($relationmodel, $matchinfo->title, $app,$notifyurl);
                }
            } else {
//获取支付凭证
                $shipment = '';
                if ((int)$paytype === PAY_ALI) {

                    $shipment = self::getAlipayRequest($relationmodel, $matchinfo->title, $paymethod, $returnurl);
                    if ($shipment && $paymethod == PAY_METHOD_QR) {
                        $url = 'https://openapi.alipay.com/gateway.do?' . $shipment;
                        $alires = CurlTools::Curl($url);
                        if ($alires) {
                            $rs = ArrayHelper::toArray(json_decode($alires));
                            if ($rs && isset($rs['alipay_trade_precreate_response'])) {
                                if (isset($rs['alipay_trade_precreate_response']['code']) && $rs['alipay_trade_precreate_response']['code'] == 10000) {
                                    $shipment = $rs['alipay_trade_precreate_response']['qr_code'];
                                }
                            }
                        }

                    }
                } elseif ((int)$paytype === PAY_WX) {
                    $shipment = self::getH5WxpayRequest($relationmodel, $matchinfo->title, $paymethod, $code, $apph5);
                }
            }

            $data['paystate'] = EventRelation::PAY_NO;
            if ($shipment) $data['shipment'] = $shipment;
        }


        $data['rrid'] = $relationmodel->id;
        $data['rgid'] = $groupinfo->id;
        $data['order_no'] = $relationmodel->order_no;
        $data['paymethod'] = $paymethod;
        $data['paytype'] = $paytype;
        $data['ischeck'] = $relationmodel->ischeck;
        return $data;
    }

    private function getH5WxpayRequest($relationmodel, $matchname, $paymethod, $code, $app)
    {

        $goodsname = $matchname . '(' . $relationmodel->typename . ')' . '报名费';
        $wxpay = new Pay_Wx_Utils();
        $oinfo = [
            'out_trade_no' => $relationmodel->order_no,
            'subject' => str_replace(' ', '', $goodsname),
            'body' => str_replace(' ', '', '活动报名'),
            'total_fee' => $relationmodel->fees * 100,
            'it_b_pay' => "5",//5分钟支付失效
            'product_id' => $relationmodel->order_no,
        ];

        if ($paymethod != PAY_METHOD_QR) {
            $wxusrinfo = $wxpay->getWechatInfo($code);
            if ($wxusrinfo) {
                $oinfo['openid'] = $wxusrinfo['openid'];
            }

        }
        return $wxpay->getWxRequest($oinfo, $app, $paymethod);
    }

    private function getWxpayRequest($relationmodel, $matchname, $app,$notifyurl=null)
    {
        $goodsname = $matchname . '(' . $relationmodel->typename . ')' . '报名费';

        $wxpay = new Pay_Wx_Utils();
        $oinfo = [
            'out_trade_no' => $relationmodel->order_no,
            'subject' => str_replace(' ', '', $goodsname),
            'body' => str_replace(' ', '', '活动报名'),
            'total_fee' => $relationmodel->fees * 100,
            'it_b_pay' => "5",//5分钟支付失效
            'product_id' => $relationmodel->order_no,
        ];

        $channel = UserChannel::findOne(['urid' => $relationmodel->urid, 'app' => $app]);
        if ($channel) {
            $oinfo['openid'] = $channel->openid;
        }

        return $wxpay->getWxRequest($oinfo, $app,$notifyurl);
    }

    /**
     * 根据订单号发送通知
     * @param type $orderno
     * @return boolean
     */
   static  private function sendRegisterNotice($relationmodel, $matchname, $app)
    {

        if (empty($relationmodel)) {
            return false;
        }

        if (!empty($relationmodel->sendnotice)) {
            return true;
        }

        $template = WxTemplate::findOne(['type' => 1, 'app' => $app]);
        if (empty($template)) {
            return false;
        }


        $sign = $template->sms_sign;
        $templatecode = $template->sms_template;
        $msgcode['title'] = $matchname;
        $msgcode['type'] = $relationmodel->typename;

        $infos = EventInfo::find()->select('mobile')
            ->andFilterWhere(['rrid' => $relationmodel->id])
            ->groupBy('mobile')->asArray()->all();
        $num = 0;
        if (!empty($infos)) {
            foreach ($infos as $oneinfo) {
                $ret = Utils::sendPhoneMessage($sign, $templatecode, $oneinfo['mobile'], json_encode($msgcode), $relationmodel->gid);
                if ($ret) $num++;
            }
        }
        if (!empty($relationmodel->mobile)) {
            $ret = Utils::sendPhoneMessage($sign, $templatecode, $relationmodel->mobile, json_encode($msgcode), $relationmodel->gid);
            if ($ret) $num++;
        }
        if ($num) {
            $relationmodel->sendnotice = 1;
            $relationmodel->save();
        }

        if (!empty($template->wx_template)) {
            $templateid = $template->wx_template;
            $msgdata['data']['keyword1']['value'] = $matchname;
            $msgdata['data']['keyword2']['value'] = $relationmodel->typename;
            $msgdata['data']['keyword3']['value'] = date('Y-m-d H:i:s', time());
            //判断跳转地址
            $registerType = EventType::findOne($relationmodel->typeid);
            $page = '/train/pages/match/myregister/index';
            if ($registerType->type == 1) {
                $page = '/train/pages/match/roleregister/view?rrid=' . $relationmodel->id . '&rgid=' . $relationmodel->rgid . '&typeid=' . $relationmodel->typeid . '&matchid=' . $relationmodel->matchid;
            } elseif ($registerType->type == 3) {
                $page = '/train/pages/match/groupregister/manage?rrid=' . $relationmodel->id . '&rgid=' . $relationmodel->rgid . '&typeid=' . $relationmodel->typeid . '&matchid=' . $relationmodel->matchid;
            }
            $ret = Utils::sendWXMessage($relationmodel->urid, $app, $templateid, $msgdata, $page);
        }

        if (!empty($template->wx_subscribe_template)) {
            $templateid = $template->wx_subscribe_template;
            $msgdata['data']['thing2']['value'] = $matchname;
            $msgdata['data']['date4']['value'] = date('Y-m-d H:i:s', time());
            $msgdata['data']['thing19']['value'] = $relationmodel->typename;
            //判断跳转地址
            $registerType = EventType::findOne($relationmodel->typeid);
            $page = '/train/pages/match/myregister/index';
            if ($registerType->type == 1) {
                $page = '/train/pages/match/roleregister/view?rrid=' . $relationmodel->id . '&rgid=' . $relationmodel->rgid . '&typeid=' . $relationmodel->typeid . '&matchid=' . $relationmodel->matchid;
            } elseif ($registerType->type == 3) {
                $page = '/train/pages/match/groupregister/manage?rrid=' . $relationmodel->id . '&rgid=' . $relationmodel->rgid . '&typeid=' . $relationmodel->typeid . '&matchid=' . $relationmodel->matchid;
            }
            $ret = Utils::sendWXMessageSubscribe($relationmodel->urid, $app, $templateid, $msgdata, $page);
        }

        return true;
    }
}