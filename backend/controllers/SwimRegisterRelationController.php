<?php

namespace backend\controllers;

use backend\models\Match;
use backend\models\RegisterDetail;
use backend\models\RegisterInfo;
use moonland\phpexcel\Excel;
use Yii;
use backend\models\RegisterRelation;
use backend\models\Search\RegisterRelationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SwimRegisterRelationController implements the CRUD actions for SwimRegisterRelation model.
 */
class SwimRegisterRelationController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all SwimRegisterRelation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RegisterRelationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = false;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => Yii::$app->request->queryParams,
        ]);
    }

    /**
     * Updates an existing SwimMatchSessionItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionExport()
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="用户报名信息' . date('Y-m-d') . '.xlsx');
        header('Cache-Control: max-age=1');

        $searchModel = new RegisterRelationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Excel::export([
            'models' => $dataProvider->query->all(),
            'columns' => [
                [
                    'label' => '订单号',
                    'attribute' => 'order_no',
                    'value' => function($model) {
                        return $model->order_no;
                    }
                ],
                [
                    'label' => '赛事名称',
                    'attribute' => 'matchid',
                    'value' => function($model)  {
                        return (new Match())->getTitleByID($model->matchid);
                    },
                ],
                [
                    'label' => '场次id/名称',
                    'attribute' => 'ssid',
                    'value' => function($model) {
                        $data = (new RegisterDetail())->getSSidName($model->id);
                        return !empty($data) ? $data['ssid'] . '/' . $data['name'] : '';
                    }
                ],
                [
                    'label' => '姓名',
                    'attribute' => 'name',
                    'value' => function($model) {
                        return (new RegisterInfo())->getFiledByRridMatchid($model->id, $model->matchid, 'name');
                    }
                ],
                [
                    'label' => '手机',
                    'attribute' => 'mobile',
                    'value' => function($model) {
                        return (new RegisterInfo())->getFiledByRridMatchid($model->id, $model->matchid, 'mobile');
                    }
                ],
                [
                    'label' => '费用',
                    'attribute' => 'fees',
                    'value' => function($model) {
                        return $model->fees;
                    }
                ],
                [
                    'label' => '支付状态',
                    'attribute' => 'state',
                    'value' => function($model) {
                        return isset(RegisterRelation::$stateList[$model->state])
                            ? RegisterRelation::$stateList[$model->state] : '-';;
                    }
                ],
                [
                    'label' => '报名项目',
                    'attribute' => 'item',
                    'value' => function($model) {
                        $itemNames =  (new RegisterDetail())->getItemNamesByOrderID($model->id);
                        return implode(PHP_EOL, $itemNames);
                    }
                ],
                [
                    'label' => '报名信息',
                    'attribute' => 'info',
                    'value' => function($model) {
                        $extra = [];
                        $infosJson =  (new RegisterInfo())->getFiledByRridMatchid($model->id, $model->matchid, 'registerinfos');
                        $infoArr = json_decode($infosJson, true);
                        if (!empty($infoArr) && is_array($infoArr)) {
                            foreach ($infoArr as $infoValue) {
                                if (isset($infoValue['key_name']) && (strpos($infoValue['key_name'], 'mv_') === false)
                                    && isset($infoValue['show_name']) && isset($infoValue['value'])) {
                                    $extra[] = $infoValue['show_name']. ':' . $infoValue['value'];
                                }
                            }
                        }
                        return implode(PHP_EOL, $extra);
                    }
                ],
            ],
            'headers' => [
                'order_no' => '订单号',
                'matchid' => '赛事名称',
                'ssid' => '场次id/名称',
                'name' => '姓名',
                'mobile' => '手机',
                'fees' => '费用',
                'state' => '支付状态',
                'item' => '报名项目',
                'info' => '报名信息',
            ],
        ]);
    }

    /**
     * Finds the SwimRegisterRelation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RegisterRelation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RegisterRelation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
