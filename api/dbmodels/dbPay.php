<?php

/**
 * Created by wayne.
 * Date: 2019/2/1
 * Time: 1:27 PM
 */

namespace api\dbmodels;


use api\controllers\Controller;
use api\library\Wxpay\Pay_Wx_Utils;
use api\models\Match;
use api\models\RegisterDetail;
use api\models\RegisterInfo;
use api\models\RegisterRelation;
use api\models\RegisterType;
use api\models\User;
use api\models\UserChannel;
use api\models\UserInfo;
use api\models\WxTemplate;
use common\helpers\FileLockAPI;
use common\helpers\Utils;

use Yii;
use yii\db\Exception;

class dbPay
{

    private function updateInfoAfterPay($orderno,$tradeno,$paytype,$paytime,$payinfo){

        if(empty($orderno))
            return false;

        $order = RegisterRelation::find()->andFilterWhere(['order_no'=>$orderno])->one();
        if(empty($order)) return false;
        $order->payinfo = $payinfo;
        $order->state = RegisterRelation::PAY_YES;
        $order->paytime = $paytime;
        $order->trade_no = $tradeno;
        $ret = $order->save();
        if($ret){
            $matchinfo = Match::findOne(['id'=>$order->matchid]);
            self::sendRegisterNotice($order,$matchinfo->title,$order->app);
            return true;
        }

        return false;

    }

    public static function dueWxpayNotify(){

        $wxpay = new Pay_Wx_Utils();

        $notify = [];
        $result = $wxpay->paraseWxNotify($notify);

        if(isset($notify['out_trade_no']) && isset($notify['trade_no'])
            && isset($notify['gmt_payment']) && isset($notify['trade_status']) && $notify['trade_status'] == 'SUCCESS' ) {

            $data = self::updateInfoAfterPay($notify['out_trade_no'],$notify['trade_no'],PAY_WX,$notify['gmt_payment'],$notify['payinfo']);
        }

        return $result;
    }


    public static function registerPrePayOrder($urid,$matchid, $typeid, $gid, $app){

        $userinfo = UserInfo::findOne(['urid'=>$urid]);
        if(empty($userinfo)) {
            $GLOBALS['errormsg'] = '错误用户';
            return false;
        }

        $usermodel = User::findOne(['id'=>$urid]);
        if(empty($usermodel)) {
            $GLOBALS['errormsg'] = '错误用户';
            return false;
        }

        $matchinfo = Match::findOne(['id'=>$matchid, 'status'=>1]);
        if(empty($matchinfo)) {
            $GLOBALS['errormsg'] = '错误赛事id';
            return false;
        }

        $typeinfo = RegisterType::findOne(['id'=>$typeid, 'matchid'=>$matchid]);
        if(empty($typeinfo)) {
            $GLOBALS['errormsg'] = '错误赛事组别';
            return false;
        }

        $totals = RegisterRelation::find()->andFilterWhere(['matchid'=>$matchid, 'typeid'=>$typeid,'state'=>1,'ischeck'=>1])->count();
        if($totals >= $typeinfo->amount) {
            $GLOBALS['errormsg'] = '已报满';
            return false;
        }

        if(time() > $matchinfo->reg_end_time || time() < $matchinfo->reg_start_time) {
            $GLOBALS['errormsg'] = '报名未开始或者报名已结束';
            return false;
        }

        if($typeinfo->registerlimit > 0){
            $orderold = RegisterRelation::find()->select('id')
                ->andFilterWhere(['urid'=>$urid,'matchid'=>$matchid,'state'=>1])->count();
            if($orderold >= $typeinfo->registerlimit) {
                $GLOBALS['errormsg'] = '您已超过了比赛报名限额!';
                $GLOBALS['errorcode'] = Controller::REPEAT_ERR;
                return false;
            }

        }

        $connection = Yii::$app->db;
        $tranns = $connection->beginTransaction();
        try{

            $relation = new RegisterRelation();
            $relation->matchid = $matchid;
            $relation->app = $app;
            $relation->typeid = $typeid;
            $relation->urid = $urid;
            $relation->typename = $typeinfo->title;

            $relation->orgfees = $typeinfo->fees;
            $relation->fees = $typeinfo->fees;

            if(!empty($usermodel->phone)) {
                $relation->mobile = $usermodel->phone;
            }
            $relation->name = $userinfo->nickname;
            $relation->type = $typeinfo->type;
            if($typeinfo->needcheck == 2) {
                $relation->ischeck = 2;
            }
            $relation->groupcode = Utils::genGroupCode();

            $relation->state = RegisterRelation::PAY_NOVALID;
            $relation->create_time = time();
            $relation->save();
            $tranns->commit();
        }catch(Exception $e){
            $tranns->rollBack();
            $GLOBALS['errormsg'] = '异常错误';
            return false;
        }

        $data['showmessage'] = '成功';
        $data['state'] = '1';
        $data['rrid']  = $relation->id;
        return $data;

    }



    public static function payOrder($app, $urid,$gid, $rrid, $paytype, $paymethod, $returnurl, $code=null, $seosource=null, $speccode=null){
        $relationmodel = RegisterRelation::findOne(['id'=>$rrid,'urid'=>$urid]);
        if(empty($relationmodel)) {
            $GLOBALS['errormsg'] = '报名信息错误';
            return false;
        }

        $infos = RegisterInfo::findOne(['rrid'=>$rrid]);
        if(empty($infos)) {
            $GLOBALS['errormsg'] = '缺少报名用户信息';
            return false;
        }

        $details = RegisterDetail::findOne(['rrid'=>$rrid]);
        if(empty($details)) {
            $GLOBALS['errormsg'] = '缺少报名项目';
            return false;
        }

        $matchid = $relationmodel->matchid;
        $typeid = $relationmodel->typeid;

        $matchinfo = Match::findOne(['id'=>$matchid, 'status'=>1]);
        if(empty($matchinfo)) {
            $GLOBALS['errormsg'] = '错误赛事id';
            return false;
        }

        $typeinfo = RegisterType::findOne(['id'=>$typeid, 'matchid'=>$matchid]);
        if(empty($typeinfo)) {
            $GLOBALS['errormsg'] = '错误赛事组别';
            return false;
        }

        if($relationmodel->state == RegisterRelation::PAY_YES) {
            $GLOBALS['errormsg'] = '已支付订单，无需重复支付';
            return false;
        }

        if(time() > $matchinfo->reg_end_time || time() < $matchinfo->reg_start_time) {
            $GLOBALS['errormsg'] = '报名未开始或者报名已结束';
            return false;
        }

        if($typeinfo->registerlimit > 0){
            $orderold = RegisterRelation::find()->select('id')
                ->andFilterWhere(['urid'=>$urid,'matchid'=>$matchid,'state'=>1])->count();
            if($orderold >= $typeinfo->registerlimit) {
                $GLOBALS['errormsg'] = '您已超过了比赛报名限额!';
                $GLOBALS['errorcode'] = Controller::REPEAT_ERR;
                return false;
            }

        }
        
        $path = Yii::$app->getRuntimePath();
        $lockname = 'RelationOrder'.'_'.$relationmodel->id.'.lock';
        $lockfile = $path.'/'.$lockname;

        $fp = FileLockAPI::getFileLock($lockfile);
        if(empty($fp)) {
            $GLOBALS['errormsg'] = '重复支付';
            return false;
        }
        $connection = Yii::$app->db;
        $tranns = $connection->beginTransaction();
        try{
            if(empty($relationmodel->order_no)) {
                $typeinfo->updateCounters(['num' => -1]);
            }

            $relationmodel->paytype = $paytype;
            $relationmodel->orgfees = $typeinfo->fees;
            $relationmodel->fees = $typeinfo->fees;

            if(empty($relationmodel->fees) || $relationmodel->fees=='0.00'){

                if($typeinfo->needcheck == 2) {
                    $relationmodel->state = RegisterRelation::PAY_YES;
                    $relationmodel->ischeck = RegisterRelation::REGISTER_CHECK_UNPASS;
                } else {
                    $relationmodel->state = RegisterRelation::PAY_YES;
                    $relationmodel->ischeck = RegisterRelation::REGISTER_CHECK_PASS;
                }

                $relationmodel->paytype = PAY_FREE;
                $relationmodel->paytime = date('Y-m-d H:i:s', time());

                if(empty($relationmodel->order_no))
                    $relationmodel->order_no = Utils::build_order_no();

            }else {
                $relationmodel->state = RegisterRelation::PAY_NO;
                $relationmodel->paytype = $paytype;

                if(empty($relationmodel->order_no)) {   //first time pay
                    $relationmodel->order_no = Utils::build_order_no();
                    $relationmodel->lastpaytime = time();
                } else {
                    if(!empty($relationmodel->lastpaytime) && $relationmodel->lastpaytime < time()-60*5) { //pay time out,renew no. and lastpaytime
                        $relationmodel->order_no = Utils::build_order_no();
                        $relationmodel->lastpaytime = time();
                    }
                }

            }

            $relationmodel->save();
            $tranns->commit();
        }catch(Exception $e){
            $tranns->rollBack();
            $GLOBALS['errormsg'] = "报名名额已满！";
            return false;
        }

        if($relationmodel->state == RegisterRelation::PAY_YES){
            $data['paystate'] = RegisterRelation::PAY_YES;

            self::sendRegisterNotice($relationmodel, $matchinfo->title, $app);

        }else{
            //获取支付凭证
            $shipment = '';
            if((int)$paytype === PAY_ALI ){

            }elseif((int)$paytype === PAY_WX){
                $shipment = self::getWxpayRequest($relationmodel,$matchinfo->title, $app);
            }
            $data['paystate'] = RegisterRelation::PAY_NO;
            if($shipment)   $data['shipment']    = $shipment;
        }

        $data['rrid']         = $relationmodel->id;
        $data['order_no']     = $relationmodel->order_no;
        $data['paymethod']    = $paymethod;
        $data['paytype']      = $paytype;
        return $data;
    }


    private function getWxpayRequest($relationmodel,$matchname,$app){
        $goodsname = $matchname.'('.$relationmodel->typename.')'.'报名费';

        $wxpay = new Pay_Wx_Utils();
        $oinfo = [
            'out_trade_no'   => $relationmodel->order_no,
            'subject'        => str_replace(' ','', $goodsname),
            'body'           => str_replace(' ','','活动报名'),
            'total_fee'      => $relationmodel->fees * 100,
            'it_b_pay'       => "5" ,//5分钟支付失效
            'product_id'     => $relationmodel->order_no,
        ];

        $channel = UserChannel::findOne(['urid'=>$relationmodel->urid, 'app'=>$app]);
        if($channel) {
            $oinfo['openid'] = $channel->openid;
        }

        return  $wxpay->getWxRequest($oinfo, $app);
    }


    /**
     * 根据订单号发送通知
     * @param type $orderno
     * @return boolean
     */
    private static function sendRegisterNotice($relationmodel,$matchname, $app){

        if(empty($relationmodel)) {
            return false;
        }

        $template = WxTemplate::findOne(['type'=>1, 'app'=>$app]);
        if(empty($template)) {
            return false;
        }

        if(!empty($relationmodel->mobile)) {
            $sign = $template->sms_sign;
            $templatecode = $template->sms_template;
            $msgcode['title'] = $matchname;
            $msgcode['type'] = $relationmodel->typename;

            $ret = Utils::sendPhoneMessage($sign, $templatecode, $relationmodel->mobile, json_encode($msgcode));
            if($ret) {
                $relationmodel->sendnotice = 1;
                $relationmodel->save();
            }
        }



        $templateid = $template->wx_template;
        $msgdata['data']['keyword1']['value'] = $matchname;
        $msgdata['data']['keyword2']['value'] = $relationmodel->typename;
        $msgdata['data']['keyword3']['value'] = date('Y-m-d H:i:s', time());

        $page = 'pages/match/myregister/index';
        $ret = Utils::sendWXMessage($relationmodel->urid, $app, $templateid, $msgdata, $page);
        return $ret;
    }

    public static function doCheckin($itemid, $urid, $gid, $orderid, $app){
        $relationmodel = RegisterRelation::findOne(['urid'=>$urid,'id'=>$orderid]);
        if(empty($relationmodel)) {
            $GLOBALS['errormsg'] = "错误订单";
            return false;
        }
        if($relationmodel->state != RegisterRelation::PAY_YES) {
            $GLOBALS['errormsg'] = "无效订单";
            return false;
        }

        $detailmodel = RegisterDetail::find()
            ->andFilterWhere(['rrid'=>$orderid])
            ->andFilterWhere(['or',['itemid1'=>$itemid],['itemid2'=>$itemid]])
            ->one();

        if(empty($detailmodel)) {
            $GLOBALS['errormsg'] = "没有预订该项目";
            return false;
        }

        if($detailmodel->itemid1 == $itemid) {
            if($detailmodel->check_state1 == 1) {
                $GLOBALS['errormsg'] = "已检录过";
                return false;
            }

            $detailmodel->check_state1 = 1;
            $ret = $detailmodel->save();
            return true;
        }

        if($detailmodel->itemid2 == $itemid) {
            if($detailmodel->check_state2 == 1) {
                $GLOBALS['errormsg'] = "已检录过";
                return false;
            }

            $detailmodel->check_state2 = 1;
            $ret = $detailmodel->save();
            return true;
        }

    }
}