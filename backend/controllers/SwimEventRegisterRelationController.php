<?php

namespace backend\controllers;

use backend\models\EventRelation;
use backend\models\Search\EventRelationSearch;
use common\models\Event;
use Yii;
use yii\web\Controller;
use moonland\phpexcel\Excel;

/**
 * SwimEventRegisterRelationController implements the CRUD actions for SwimEventRegisterRelation model.
 */
class SwimEventRegisterRelationController extends Controller
{
    /**
     * Lists all SwimEventRegisterRelation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;
        if (!isset($params['EventRelationSearch']['state'])) {
            return $this->redirect('/swim-event-register-relation/index?EventRelationSearch[state]=1');
        }
        $searchModel = new EventRelationSearch();
        $dataProvider = $searchModel->search($params);
        $dataProvider->sort = false;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => Yii::$app->request->queryParams,
        ]);
    }

    public function actionExport()
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="用户报名信息' . date('Y-m-d') . '.xlsx');
        header('Cache-Control: max-age=1');

        $searchModel = new EventRelationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $data = $dataProvider->query->asArray()
            ->select(['swim_event_relation.matchid', 'typename', 'swim_event_info.name', 'sex', 'swim_event_info.mobile',
                'swim_event_info.idnumber', 'swim_event_relation.state', 'fees'])
            ->all();
        foreach ($data as $idx => $value) {
            $data[$idx]['idx'] = $idx + 1;
        }

        Excel::export([
            'models' => $data,
            'columns' => [
                [
                    'label' => '序号',
                    'attribute' => 'order_no',
                    'value' => function($model) {
                        return $model['idx'];
                    }
                ],
                [
                    'label' => '培训名称',
                    'attribute' => 'matchid',
                    'value' => function($model)  {
                        return (new Event())->getTitleByID($model['matchid']);
                    },
                ],
                [
                    'label' => '组别',
                    'attribute' => 'typename',
                    'value' => function($model) {
                        return $model['typename'];
                    }
                ],
                [
                    'label' => '姓名',
                    'attribute' => 'name',
                    'value' => function($model) {
                        return $model['name'];
                    }
                ],
                [
                    'label' => '性别',
                    'attribute' => 'sex',
                    'value' => function($model) {
                        return $model['sex'];
                    }
                ],
                [
                    'label' => '手机',
                    'attribute' => 'mobile',
                    'value' => function($model) {
                        return " " . $model['mobile'];
                    }
                ],
                [
                    'label' => '身份证',
                    'attribute' => 'idnumber',
                    'value' => function($model) {
                        return " " . $model['idnumber'];
                    }
                ],
                [
                    'label' => '费用',
                    'attribute' => 'fees',
                    'value' => function($model) {
                        return $model['fees'];
                    }
                ],
                [
                    'label' => '支付状态',
                    'attribute' => 'state',
                    'value' => function($model) {
                        return isset(EventRelation::$stateList[$model['state']])
                            ? EventRelation::$stateList[$model['state']] : '-';
                    }
                ],
            ],
            'headers' => [
                'order_no' => '序号',
                'matchid' => '培训名称',
                'typename' => '组别',
                'name' => '姓名',
                'sex' => '性别',
                'mobile' => '手机',
                'idnumber' => '身份证',
                'fees' => '费用',
                'state' => '支付状态',
            ],
        ]);
        exit;
    }
}
