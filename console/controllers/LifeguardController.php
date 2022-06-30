<?php


namespace console\controllers;


use \common\helpers\Utils;
use common\models\AddressLifeguard;

class LifeguardController extends \yii\console\Controller
{
    const URL = 'https://moveclub-file.oss-cn-hangzhou.aliyuncs.com/lifeguard/';
    const KEY = '@swim#';

    /**
     * 更新救生员头像url
     * @return mixed
     */
    public function actionMakeImage()
    {
        $size = 1000;
        $count = AddressLifeguard::find()->count();
//        $page = ceil($count / $size);
        $ok = 0;
        for ($i = 0; $i < $count; $i += $size) {
            $models = AddressLifeguard::find()->where(['status' => 1])->offset($i)->limit($size)->all();
            foreach ($models as $model) {
                $model->avatar = self::URL . Utils::strEncrypt(self::KEY, $model->id_card) . '.jpg';
                if ($model->save()) {
                    $ok++;
                } else {
                    echo $model->getError();
                    return '';
                }
            }
        }
        echo '更新成功图片地址' . $ok . '条';
    }
}