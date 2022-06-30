<?php

namespace backend\controllers;

use backend\models\Address;
use backend\models\Match;
use backend\models\ScoreEnroll;
use backend\models\ScoreStates;
use Yii;
use backend\models\MatchSession;
use backend\models\MatchSessionItem;
use backend\models\Search\MatchSessionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use moonland\phpexcel\Excel;

/**
 * SwimMatchSessionController implements the CRUD actions for SwimMatchSession model.
 */
class SwimMatchSessionController extends Controller
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
     * Lists all SwimMatchSession models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MatchSessionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = false;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => Yii::$app->request->queryParams,
        ]);
    }

    /**
     * Displays a single SwimMatchSession model.
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
     * Creates a new SwimMatchSession model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionCreate()
    {
        $model = new MatchSession();

        if ($model->load(Yii::$app->request->post())
            && ($model->status = MatchSession::STATUS_VALID)
            && $model->save()) {
            $modelAddress = Address::findOne($model->swim_address_id);
            if (isset($modelAddress)) {
                $model->province = $modelAddress->province;
                $model->city = $modelAddress->city;
                $model->district = $modelAddress->district;
                $model->stadium = $modelAddress->name;
                $model->address = $modelAddress->address;
                $model->longitude = $modelAddress->longitude;
                $model->latitude = $modelAddress->latitude;
                $model->lane = $modelAddress->lane;
                $model->save();
            }
            if ($model->register_count > 2 || $model->register_count < 1) {
                $model->register_count = 2;
                $model->save();
            }
            $this->saveDuplicateAction($model->id);
            //return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->register_count = 2;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SwimMatchSession model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $modelAddress = Address::findOne($model->swim_address_id);
            if (isset($modelAddress)) {
                $model->province = $modelAddress->province;
                $model->city = $modelAddress->city;
                $model->district = $modelAddress->district;
                $model->stadium = $modelAddress->name;
                $model->address = $modelAddress->address;
                $model->longitude = $modelAddress->longitude;
                $model->latitude = $modelAddress->latitude;
                $model->lane = $modelAddress->lane;
                $model->save();
            }
            if ($model->register_count > 2 || $model->register_count < 1) {
                $model->register_count = 2;
                $model->save();
            }
            $this->saveDuplicateAction($model->id);
            //return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SwimMatchSession model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();

        if (($model = $this->findModel($id)) !== null) {
            $model->status = MatchSession::STATUS_INVALID;
            $model->save();
        }

        if (Yii::$app->request->referrer) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionPrintGroups($id)
    {
        $detail = [
            'datetime' => '',
            'title' => '',
            'maxLane' => 0,
            'group' => [],
        ];
        //
        $model = $this->findModel($id);
        if (isset($model)) {
            $matchModel = Match::findOne($model->matchid);
            $detail['title'] = $matchModel->title;
            $detail['datetime'] = date('Y年n月j日 G点i分', strtotime($model->start_time));
            $modelAddress = Address::findOne($model->swim_address_id);
            $detail['maxLane'] = $modelAddress->lane;
            $initArr = [];
            for ($i = 1; $i <= $detail['maxLane']; $i++) {
                $initArr[$i] = '';
            }
            $items = (new MatchSessionItem())->getAllItems($id);
            foreach ($items as $itemValue) {
                $itemGroupData = [
                    'title' => $itemValue['name'],
                    'group' => [],
                ];
                $groupData = (new ScoreStates())->getPrintGroupData($itemValue['id']);
                if (!empty($groupData)) {
                    foreach ($groupData as $value) {
                        if (!isset($itemGroupData['group'][$value['groupnum']])) {
                            $itemGroupData['group'][$value['groupnum']] = $initArr;
                        }
                        $itemGroupData['group'][$value['groupnum']][$value['lane']] = [
                            'enrollname' => $value['enrollname'],
                            'unit' => (new ScoreEnroll())->getUnit($value['enrollid'])
                        ];
                    }

                    $detail['group'][] = $itemGroupData;
                }
            }
        }


        return $this->renderAjax('print-groups', ['data' => $detail]);
    }

    public function actionGrouping($id)
    {
        $ret = (new MatchSessionItem())->groupSession($id);
        if ($ret) {
            Yii::$app->session->setFlash('success', '分组成功', false);
            $this->redirect('/swim-match-session-item/index?MatchSessionItemSearch[ssid]=' . $id);
        } else {
            Yii::$app->session->setFlash('danger', '分组失败', false);
            $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * 线上报名用户导入参赛表
     * @param $id
     */
    public function actionOnlineRegisterImport($id)
    {
        $ret = true;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            (new ScoreEnroll())->loadOnlineRegister($id);
            $transaction->commit();
        } catch (\Exception $e) {
            $ret = false;
            $transaction->rollBack();
        }

        if ($ret) {
            Yii::$app->session->setFlash('success', '导入成功', false);
            $this->redirect(Yii::$app->request->referrer);
        } else {
            Yii::$app->session->setFlash('danger', '导入失败', false);
            $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionImportView($id)
    {
        $model = $this->findModel($id);
        $matchModel = Match::findOne($model->matchid);

        return $this->render('import-view', ['model' => $model, 'matchModel' => $matchModel]);
    }

    public function actionOfflineEnrollImportUpload()
    {
        $ssid = Yii::$app->request->post('ssid', 0);
        if ($ssid != 0) {
            $model = $this->findModel($ssid);
            $matchModel = Match::findOne($model->matchid);

            $cnt = 0;
            if (isset($_FILES['enroll']) && $_FILES['enroll']['error'] == 0) {
                $excelData = Excel::import($_FILES['enroll']["tmp_name"], [
                    'setFirstRecordAsKeys' => true,
                    'setIndexSheetByName' => false,
                    'getOnlySheet' => 'sheet1',
                ]);

                //todo swimbk代码中有含extrainfo字段，但模版中没看到，暂未加入
                $initEnroll = [
                    'matchid' => $matchModel->id,
                    'ssid' => $ssid,
                    'itemid' => 0,
                    'type' => ScoreEnroll::TYPE_OFFLINE,
                    'name' => '',
                    'unit' => '',
                    'phone' => '',
                    'idcard' => '',
                    'gender' => '',
                ];
                $itemNames = [];
                $enrolls = [];
                foreach ($excelData as $row) {
                    $v = array_values($row);
                    $itemNames[] = $v[0];
                }
                $itemNames = array_unique($itemNames);
                $filterItemData = (new MatchSessionItem())->getItemIDsBySsidItemNames($ssid, $itemNames);
                if (!empty($filterItemData)) {
                    foreach ($excelData as $row) {
                        $v = array_values($row);
                        if (!isset($filterItemData[$v[0]])) {
                            continue;
                        }
                        $initEnroll['itemid'] = $filterItemData[$v[0]];
                        $initEnroll['name'] = $v[1];
                        $initEnroll['unit'] = $v[5];
                        $initEnroll['phone'] = $v[3];
                        $initEnroll['idcard'] = $v[4];
                        $initEnroll['gender'] = ($v[2] == '男' ? ScoreEnroll::GENDER_M : ScoreEnroll::GENDER_F);
                        $enrolls[] = $initEnroll;
                    }

                    $condScoreEnroll = [
                        'ssid' => $ssid,
                        'itemid' => array_values($filterItemData),
                        'type' => ScoreEnroll::TYPE_OFFLINE,
                    ];
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        //delete enroll
                        Yii::$app->db->createCommand()->delete(ScoreEnroll::tableName(),
                            $condScoreEnroll)->execute();
                        //add enroll
                        $cnt = Yii::$app->db->createCommand()
                            ->batchInsert(ScoreEnroll::tableName(), array_keys($initEnroll), $enrolls)
                            ->execute();
                        $transaction->commit();
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                    }
                }
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'msg' => '用户数据导入成功' . $cnt . '条',
            ];
        }
        return $this->renderContent('异常请求');
    }

    public function actionItemImportUpload()
    {
        $ssid = Yii::$app->request->post('ssid', 0);
        if ($ssid != 0) {
            $model = $this->findModel($ssid);
            $matchModel = Match::findOne($model->matchid);

            $cnt = 0;
            if (isset($_FILES['item']) && $_FILES['item']['error'] == 0) {
                $excelData = Excel::import($_FILES['item']["tmp_name"], [
                    'setFirstRecordAsKeys' => true,
                    'setIndexSheetByName' => false,
                    'getOnlySheet' => 'sheet1',
                ]);
                
                foreach ($excelData as $row) {
                    $v = array_values($row);
                    $flag = (new MatchSessionItem())->addOneFromUpload($matchModel->id, $ssid, $v[0], $v[1], $v[2],
                        $v[3], $v[4], $v[5]);
                    if ($flag) {
                        $cnt++;
                    }
                }
            }

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'msg' => '比赛项目导入成功' . $cnt . '条',
            ];
        }

        return $this->renderContent('异常请求');
    }

    public function actionGetSsid()
    {
        $matchid = Yii::$app->request->get('matchid', '');
        $data = (new MatchSession())->getSsidList($matchid);
        $ret = '';
        foreach ($data as $v) {
            $ret .= '<option value="' . $v['id'] . '">' . 'ID-' . $v['id'] . ' ' . $v['name'] . '</option>';
        }

        return $ret;
    }

    /**
     * Finds the SwimMatchSession model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MatchSession the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MatchSession::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
