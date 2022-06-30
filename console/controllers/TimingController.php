<?php
/**
 * Created by wayne.
 * Date: 2019/1/8
 * Time: 4:23 PM
 */
namespace console\controllers;



use api\models\dbEnrollGroupinfo;
use api\models\dbEnrollInfo;
use api\models\dbTimesTrack;
use api\models\dbTimingStates;
use common\helpers\FileLockAPI;

use common\models\MatchImageConfig;
use common\models\RegisterRelation;
use common\models\RegisterType;
use console\models\dbScript;
use yii\console\Controller;
use Yii;

class TimingController extends Controller
{

    public function __construct($id, $module, $config = [])
    {
        set_time_limit(0);
        date_default_timezone_set('Asia/Shanghai');
        parent::__construct($id, $module, $config);
    }

    public function actionRegister() {
        $fp = FileLockAPI::getActionLock();
        if(empty($fp))
            return;


        $query = RegisterRelation::find()->andFilterWhere(['state'=>2])
            ->andFilterWhere(['>','lastpaytime',0]);
        $total = $query->count();
        $limit = 20;
        $pages = ceil($total/$limit);
        for($page = 1; $page<=$pages; $page++) {
            $offset = ($page - 1)*$limit;
            $orders = $query->orderBy('id asc')->limit($limit)->offset($offset)->all();
            if(empty($orders)) continue;
            foreach ($orders as $one) {
                if($one->lastpaytime + 5*60 + 30 < time()) {
                    $matchtype = RegisterType::findOne(['id'=>$one->typeid]);
                    $connection     =   Yii::$app->db;
                    $transaction    = $connection->beginTransaction();
                    try {
                        $one->state = 101;
                        $one->save();
                        $matchtype->updateCounters(['num'=>1]);
                        $transaction->commit();
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                    }
                }
            }

        }



        echo 'done';
        FileLockAPI::unlockFile($fp);




    }



}