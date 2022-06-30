<?php
namespace backend\components;

use yii\web\ServerErrorHttpException;

class Utils{

    static public function getLetter($i,$key=""){
        if($i>701)
        {
            return "";
        }
        $y = ($i / 26);
        if ($y >= 1) {
            $y = intval($y);
            return   $key?chr($y+64).chr($i-$y*26 + 65).$key:chr($y+64).chr($i-$y*26 + 65);
        } else {
            return  $key?chr($i+65).$key:chr($i+65);
        }
    }


    static public function throwErrors($model){
        throw new ServerErrorHttpException(implode(',', $model->getErrorSummary(true)));
    }

}