<?php

namespace api\library\Wxpay;
use api\models\wxapp\Weixin;
use common\helpers\Utils;

require_once "../library/Wxpay/lib/WxPay.Api.php";
require_once "../library/Wxpay/lib/WxPay.Config.php";

class Pay_Wx_Utils {
    
        private $release_notify_url = 'https://swimapi.moveclub.cn/pay/wxpaynotify';
        private $alpha_notify_url = 'https://alpha.swimapi.moveclub.cn/pay/wxpaynotify';
         
        private static $mWeixin;
    
        protected static function mWeixin(){
          if(!(self::$mWeixin instanceof Weixin)) self::$mWeixin = new Weixin();
          return self::$mWeixin;
        }
    
	public function __construct(){
            
	}
	
	public function getWxRequest($orderinfo, $app,$notifyurl=null){
            
            $inputObj = new  \WxPayUnifiedOrder();
            $inputObj->SetOut_trade_no($orderinfo['out_trade_no']);
            $inputObj->SetTotal_fee($orderinfo['total_fee']);
           
            $inputObj->SetBody($orderinfo['body']);
            $inputObj->SetDetail($orderinfo['subject']);


            if(!empty($notifyurl)) {
                $inputObj->SetNotify_url($notifyurl);
            } else {
                if( Utils::isAlpha ())
                    $inputObj->SetNotify_url($this->alpha_notify_url);
                else
                    $inputObj->SetNotify_url($this->release_notify_url);
            }
            
            $starttime = date("YmdHis");
            $inputObj->SetTime_start($starttime);
            $time_expire = date("YmdHis",strtotime($starttime)+$orderinfo['it_b_pay']*60);
            $inputObj->SetTime_expire($time_expire);

            $inputObj->SetTrade_type ('JSAPI' );
            if(isset($orderinfo['openid'])) $inputObj->SetOpenid($orderinfo['openid']);

            $result = \WxPayApi::unifiedOrder($inputObj, 6, $app);
            
            //生成新签名及返回结果
            if(array_key_exists('return_code',$result)){
                if($result['return_code'] == 'FAIL')
                    return $result;
                else
                    return \WxPayApi::genResult($result, $app);
            }
            
	}
        
        
        /**
     * 异步解析wx回调
     * 
     * @auther Eagle
     * @since V1.6
     * @version V1.6
     * @ctime 2016-10-09 18:14:13
     * @utime 2016-10-09 18:14:14
     * @param type  
     * @return 成功返回数据,异常返回fasle或null
     **/
        public function paraseWxNotify(&$notify){
            $msg = '';
            
            $result = \WxPayApi::notifyUrl($msg); //自己的回调
            
            
            if($result && key_exists("result_code" , $result)){
                
               
                if($result['result_code'] =='SUCCESS'){
                    
                    $notify['payinfo'] = json_encode($result );
                
                    
                    //商户订单号
                    $notify['out_trade_no'] = $result['out_trade_no'];

                    //微信交易号
                    $notify['trade_no'] = $result['transaction_id'];

                    //交易状态
                    $notify['trade_status'] = $result['result_code'];
                
                    //微信支付openid
                    $notify['buyer_email'] = $result['openid'];
                
                    //买家支付时间
                    $notify['gmt_payment'] = $result['time_end'];
                    
                }
                
                //return to wx
                return $this->ToXml(['return_code'  => 'SUCCESS','return_msg'  => 'OK']);
                    
            }
            return $msg;
        }
        
        
	 /**
	 * 输出xml字符
	 * @throws WxPayException
	**/
	private function ToXml($input)
	{
		if(!is_array($input) 
			|| count($input) <= 0)
		{
    		return 1 ;
            }
    	
            $xml = "<xml>";
            foreach ($input as $key=>$val)
            {
                    if (is_numeric($val)){
                            $xml.="<".$key.">".$val."</".$key.">";
                    }else{
                            $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
                    }
            }
            $xml.="</xml>";
            return $xml; 
	}
    /**
     * 获得微信用户信息
     */
//    public function getWechatInfo($code,$userid){
//
//        $appid  = \WxPayConfig::APPID;
//        $secret = \WxPayConfig::APPSECRET;
//
//        $result = self::mWeixin()->getWechatUserInfo($appid,$secret,$code,$userid);
//
//        if($result){
//            $data['openid']     = $result['openid'];
//            $data['sex']        = $result['sex'];
//            $data['nickname']   = $result['nickname'];
//            $data['imgkey']     = $result['headimgurl'];
//            return $data;
//        }
//        return false;
//    }
    
    
    
    /**
     * 查询订单状态
     * @param type $orderinfo
     * @param type $paymethod
     * @return type
     */
    public function queryWxPayinfo($orderno){
            
        $inputObj = new  \WxPayOrderQuery();

        $inputObj->SetOut_trade_no($orderno);

        $result = \WxPayApi::orderQuery($inputObj);
        return $result;

    }
    
    
//    public function getAppId(){
//
//            return \WxPayConfig::APPID;
//    }
//
//    public function getSecret(){
//
//        return \WxPayConfig::APPSECRET;
//    }


    public function getNonceStr(){

        return \WxPayApi::getNonceStr();
    }
    
}

?>