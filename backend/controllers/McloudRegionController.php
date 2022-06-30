<?php


namespace backend\controllers;

use backend\models\Region;
use Yii;
use yii\web\Controller;

class McloudRegionController extends \api\controllers\Controller
{
    public function actionGetRegionByName()
    {
        $pid = Yii::$app->request->get('name', '');
        $data = (new Region())->getSonRegionByName($pid);
        $ret = '';
        foreach ($data as $v) {
            $ret .= '<option value="' . $v . '">' . $v . '</option>';
        }

        return $ret;
    }
}