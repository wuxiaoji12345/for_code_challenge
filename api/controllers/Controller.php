<?php
/**
 * Created by wayne.
 * Date: 2019/1/5
 * Time: 10:03 PM
 */
namespace api\controllers;

use api\models\UserChannel;
use common\helpers\Utils;
use Yii;
use yii\base\Exception;

class Controller extends \yii\web\Controller
{

    const MSG200 = "成功";

//define("MSG201", "异常错误,Token过期,请重新获取");
    const MSG202 = "参数错误";
//define("MSG203", "其他错误");
//define("MSG204", "用户未登录");
//define("MSG210", "您有活动正在置顶中，置顶本活动将替换原有活动，确定替换?");

    const EMPTY_DATA = "暂无数据";

    const  MV_SUCCESS = "200";
    const  TOKEN_ERR = "201";

    const PARAM_ERR = "202";
    const OTHER_ERR = "203";
    const GID_ERR = "204";
    const REPEAT_ERR = "205";

//define("REAL_ERR", "205");
//define("TOP_ERR", "210");

//define("ACTIVITY_REG_CLOSED", "301");
//define("REGISTER_ERR", "250");
//define("FORCE_DEL_ERR", "302");//强制删除


    public function __construct ($id , $module , $config = array ()) {

        header('Access-Control-Allow-Origin: *');
        $GLOBALS['errormsg'] = '';
        $GLOBALS['errorcode'] = '';

        parent::__construct ($id , $module , $config) ;
    }


    public static function dataOut($data,$message = self::MSG200,$status = self::MV_SUCCESS)
    {
        if(empty($status)) $status = self::OTHER_ERR;
        $out_data['status']     = $status;
        $out_data['message']    = $message;
        $out_data['data'] = is_array($data) ? $data : [$data];
        $out_data['sys_time'] = time();
        return $out_data;

    }

    public static function errorOut($message = self::MSG202, $status = self::PARAM_ERR)
    {
        $out['status']     = $status;
        $out['message']    = $message;

        Yii::$app->response->data = $out;
        Yii::$app->end();
    }


    /**
     * 获取用户输入的数据
     * @return array|mixed
     */
    public static function requestData()
    {
        $data = new \stdClass();
        $request = Yii::$app->request;
        if($request->isPost){

            if(is_array(json_decode($request->rawBody, true))){
                $data = json_decode($request->rawBody, true);
            } else {
                $data = $request->post();
            }
        } else if($request->isGet) {
            $data = $request->get();
        }

        return $data;
    }

    /**
     * @param $key
     * @param string $default
     * @return string
     */
    public static function getJsonParam($key, $default = ""){
        $requestData = self::requestData();
        return array_key_exists($key, $requestData) ? $requestData[$key] : $default;
    }

    /**
     * @param $key
     * @param string $default
     * @param string $tips
     * @return string
     */
    public  static function getJsonParamErr($key, $default = "",$tips=""){
        $requestData = self::requestData();
        $value = array_key_exists($key, $requestData) ? $requestData[$key] : $default;

        if(empty($value)){
            if(empty($tips))self::errorOut("{$key}上传错误");
            else self::errorOut($tips);
        }
        return $value;
    }

    public static function getArrayParamErr(array $array)
    {
        foreach ($array as $key){
            self::getJsonParamErr($key);
        }
    }

    protected function encodeGid($gid) {
        $key = Yii::$app->params['gidKey'];
        $ciphertext = Utils::ecbEncrypt($key, $gid);

        return $ciphertext;
    }

    protected function decodeGid($cipher) {
        $key = Yii::$app->params['gidKey'];
        $gid = Utils::ecbDecrypt($key, $cipher);
        return $gid;
    }

    protected function checkGid($cipher) {
        $gid = $this->decodeGid($cipher);
        if(empty($gid)) {
            $GLOBALS['errormsg'] = '企业信息错误';
            return false;
        }


        return $gid;
    }

    public function checkData($data,$message=self::EMPTY_DATA,$status = self::OTHER_ERR){
        try{
            if(is_array ($data)){

                if (key_exists ("code", $data) ){
                    $message    = $data['msg'];
                    $status     = $data['code'];

                }else{

                    if(!empty($data)){

                        return true;
                    }else{

                        $status = self::MV_SUCCESS;
                    }
                }
            }else{

                if(!empty($data)) return true;
            }

        }  catch (Exception $e){
            self::errorOut($message , $status);
        }
        self::errorOut($message , $status);
        return false;
    }

  
    static public function checkUser($urid, $token) {
        $channeluser = UserChannel::findOne(['urid'=>$urid, 'token'=>$token, 'status'=>1]);
        if(empty($channeluser))
            return false;
        else
            return true;

    }
}