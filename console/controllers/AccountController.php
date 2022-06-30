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
use backend\models\Address;
use common\helpers\FileLockAPI;

use common\models\MatchImageConfig;
use common\models\RegisterRelation;
use common\models\RegisterType;
use console\models\dbScript;
use yii\console\Controller;
use Yii;

class AccountController extends Controller
{
    public function actionCreateAccount() {
        $address_infos = Address::find()->where([])->select(['*'])->asArray()->all();
    }

    public function actionGii()
    {
        $tableNames = [
            'swim_national_standard',
        ];
        $params = [
            '--tableName' => '',
            '--ns' => 'common\\models',
            '--modelClass' => '',
            '--useTablePrefix' => 1,
            '--overwrite' => 1,
            '--generateLabelsFromComments' => 1,
        ];
        foreach ($tableNames as $name) {
            $params['--tableName'] = $name;
            $name = str_replace('swim_', '', $name);
            $modelName = $modelName = implode('', array_map('ucfirst', explode('_', $name)));
            $params['--modelClass'] = $modelName;
            Yii::$app->runAction('gii/model', $params);
        }

    }

}