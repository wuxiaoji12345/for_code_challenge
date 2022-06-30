<?php

namespace backend\controllers;

use backend\models\Address;
use backend\models\MatchSession;
use backend\models\ScoreStates;
use common\helpers\CurlTools;
use Yii;
use backend\models\MatchSessionItem;
use backend\models\Search\MatchSessionItemSearch;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SwimMatchSessionItemController implements the CRUD actions for SwimMatchSessionItem model.
 */
class SwimMatchSessionItemController extends Controller
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
     * Lists all SwimMatchSessionItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MatchSessionItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = false;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => Yii::$app->request->queryParams,
        ]);
    }

    /**
     * Displays a single SwimMatchSessionItem model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SwimMatchSessionItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionCreate()
    {
        $model = new MatchSessionItem();

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->status = MatchSessionItem::STATUS_VALID;
                $model->save();
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
            }
            $this->saveDuplicateAction($model->id);
            //return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->weight = 100;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
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
            $this->saveDuplicateAction($model->id);
            //return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SwimMatchSessionItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();

        if (($model = $this->findModel($id)) !== null) {
            $model->status = MatchSessionItem::STATUS_INVALID;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save();
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
            }
        }

        if (Yii::$app->request->referrer) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * 打印分组
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionPrintGroup($id)
    {
        $detail = [
            'datetime' => '',
            'title' => '',
            'maxLane' => 0,
            'group' => [],
        ];
        //
        $itemModel = $this->findModel($id);
        if (isset($itemModel)) {
            $sessionModel = MatchSession::findOne($itemModel->ssid);
            $detail['datetime'] = date('Y年n月j日 G点i分', strtotime($sessionModel->start_time));
            $detail['title'] = $itemModel->name;
            $modelAddress = Address::findOne($sessionModel->swim_address_id);
            $detail['maxLane'] = $modelAddress->lane;
            $initArr = [];
            for ($i = 1; $i <= $detail['maxLane']; $i++) {
                $initArr[$i] = '';
            }
            $groupData = (new ScoreStates())->getPrintGroupData($id);
            foreach ($groupData as $value) {
                if (!isset($detail['group'][$value['groupnum']])) {
                    $detail['group'][$value['groupnum']] = $initArr;
                }
                $detail['group'][$value['groupnum']][$value['lane']] = $value['enrollname'];
            }
        }

        return $this->renderAjax('print-group', ['data' => $detail]);
    }

    /**
     * 分组成绩排名
     * @param $id
     * @param $ssid
     * @return string
     */
    public function actionRank($id, $ssid)
    {
        $url = 'https://swimapi.moveclub.cn/score/sessioninfo';
        $params = [
            'itemid' => $id,
            'ssid' => $ssid,
        ];
        $ret = CurlTools::postCurl($url, $params);
        $pageArray = json_decode($ret,true);
        $title = '';
        $scores = $scores_o = [];
        if (isset($pageArray['data']['list'][0]['name'])) {
            $title = $pageArray['data']['list'][0]['name'];
        }

        if (isset($pageArray['data']['list'][0]['group'])) {
            $groups = $pageArray['data']['list'][0]['group'];
            foreach($groups as $gi => $group) {
                foreach($group['enrolls'] as $k => $v) {
                    $v['score_s'] = $this->ms2time($v['score']) ;
                    $v['groupnum'] = $group['groupnum'];
                    if ($v['isvalued'] == 2) {
                        $v['remark'] = 'DNF';
                    } elseif ($v['isvalued'] == 3) {
                        $v['remark'] = 'DNS';
                    } elseif($v['isvalued'] == 4) {
                        $v['remark'] = 'DQ';
                    } else {
                        $v['remark'] = '';
                    }

                    if ($v['score'] && $v['isvalued'] == 1) {
                        $scores[] = $v;
                    } else {
                        $scores_o[] = $v;
                    }
                }
            }
        }
        array_multisort(array_column($scores, 'score'), SORT_ASC, $scores);
        array_multisort(array_column($scores_o, 'isvalued'), SORT_DESC, $scores_o);
        $scores = array_merge($scores,$scores_o);
        return $this->renderAjax('rank',[
            'score' => $scores,
            'itemName' => $title,
        ]);
    }

    /**
     * Finds the SwimMatchSessionItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MatchSessionItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MatchSessionItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function ms2time($ms)
    {
        $seconds = intval($ms / 1000);
        $str = ":" . sprintf("%02d", $seconds % 60);//"%02d" 格式化为整数，2位，不足2位，左边补0
        $minutes = intval($seconds / 60);
        $str = sprintf("%02d", $minutes % 60) . $str;
        //$hours = intval($minutes / 60);
        $msc = intval($ms%1000/10);
        $str = $str.'.'.$msc;
        return $str;
    }

    protected function saveDuplicateAction($id)
    {
        $ckOption = Yii::$app->request->post('ckOption');
        if ($ckOption == 'view') {
            return $this->redirect(['view', 'id' => $id, 'ckOption' => 'view']);
        } elseif ($ckOption == 'create') {
            return $this->redirect(['create', 'ckOption' => 'create']);
        } elseif ($ckOption == 'update') {
            return $this->redirect(['update', 'id' => $id, 'ckOption' => 'update']);
        } else {
            return $this->redirect(['view', 'id' => $id, 'ckOption' => 'view']);
        }
    }
}
