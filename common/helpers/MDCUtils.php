<?php

namespace common\helpers;

use Yii;

class MDCUtils
{


    public static function mdcCall($interface, $param)
    {
        return true;
        if(empty($interface)) {
            $GLOBALS['errormsg'] = 'empty mdc call';
            return false;
        }

        $mdcpath = Yii::$app->params['mdc'];

        $callparam = '';
        if(!empty($param) && is_array($param)) {
            foreach ($param as $k=>$v) {
                $callparam .= ' --'.$k.'='.urlencode($v);
            }
        }

        $shell = $mdcpath.' '.$interface.$callparam;

        $ret = exec($shell, $output, $status);
        if(empty($ret) && $status != 0) {
            $GLOBALS['errormsg'] = 'exec fail';
            return false;
        }

        return json_decode($ret, true);
    }


}