<?php

namespace backend\controllers;

use common\helpers\UploadOss;
use Yii;
use backend\models\Match;
use backend\models\Search\MatchSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * SwimMatchController implements the CRUD actions for SwimMatch model.
 */
class SwimMatchController extends Controller
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
     * Lists all SwimMatch models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MatchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = false;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SwimMatch model.
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
     * Creates a new SwimMatch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     * @throws \Throwable
     */
    public function actionCreate()
    {
        $model = new Match();

        $flag = $model->load(Yii::$app->request->post());
        if ($flag) {
            $model->gid = 8;
            $model->userid = Yii::$app->user->getId();
            $model->reg_start_time = strtotime($model->reg_start_time);
            $model->reg_end_time = strtotime($model->reg_end_time);
            $model->start_time = strtotime($model->start_time);
            $model->end_time = strtotime($model->end_time);
            $model->status = Match::STATUS_VALID;
            $uploadData = $this->uploadFile();
            if (isset($uploadData['imgurl']) && !empty($uploadData['imgurl'])) {
                $model->imgurl = $uploadData['imgurl'];
            }
            if (isset($uploadData['qrcode']) && !empty($uploadData['qrcode'])) {
                $model->qrcode = $uploadData['qrcode'];
            }
            if (($model->reg_start_time < $model->reg_end_time)
                && ($model->start_time < $model->end_time)) {
                $flag = $model->save();
            }
        }
        if ($flag) {
            $this->saveDuplicateAction($model->id);
            //return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->reg_start_time = date('Y-m-d 00:00:00');
            $model->reg_end_time = date('Y-m-d 23:59:59', strtotime('+7 days'));
            $model->start_time = date('Y-m-d 00:00:00', strtotime(' + 8 days'));
            $model->end_time = date('Y-m-d 23:59:59', strtotime('+38 days'));
            $model->weight = 1;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SwimMatch model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $flag = $model->load(Yii::$app->request->post());
        if ($flag) {
            $model->reg_start_time = strtotime($model->reg_start_time);
            $model->reg_end_time = strtotime($model->reg_end_time);
            $model->start_time = strtotime($model->start_time);
            $model->end_time = strtotime($model->end_time);
            $model->userid = Yii::$app->user->getId();
            $uploadData = $this->uploadFile();
            if (isset($uploadData['imgurl']) && !empty($uploadData['imgurl'])) {
                $model->imgurl = $uploadData['imgurl'];
            }
            if (isset($uploadData['qrcode']) && !empty($uploadData['qrcode'])) {
                $model->qrcode = $uploadData['qrcode'];
            }
            if (($model->reg_start_time < $model->reg_end_time)
                && ($model->start_time < $model->end_time)) {
                $flag = $model->save();
            }
        }
        if ($flag) {
            $this->saveDuplicateAction($model->id);
            //return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->reg_start_time = date('Y-m-d H:i:s', $model->reg_start_time);
            $model->reg_end_time = date('Y-m-d H:i:s', $model->reg_end_time);
            $model->start_time = date('Y-m-d H:i:s', $model->start_time);
            $model->end_time = date('Y-m-d H:i:s', $model->end_time);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SwimMatch model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();

        if (($model = $this->findModel($id)) !== null) {
            $model->status = Match::STATUS_INVALID;
            $model->save();
        }

        if (Yii::$app->request->referrer) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionUpdatePublish()
    {
        $id = Yii::$app->request->post("id");
        $isPublish = Yii::$app->request->post("publish");
        $flag = false;
        if (($model = $this->findModel($id)) !== null) {
            $model->publish = ($isPublish ? Match::PUBLISH_YES : Match::PUBLISH_NO);
            $flag = $model->save();
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($flag) {
            return [
                'code' => 0,
                'data' => [],
                'msg' => '成功',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '失败',
            ];
        }
    }

    /**
     * Finds the SwimMatch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Match the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Match::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function uploadFile()
    {
        $ret = [
            'imgurl' => '',
            'qrcode' => '',
        ];
        foreach ($ret as $name => $url) {
            if ($_FILES['SwimMatch']['error'][$name] == 0) {
                /*move_uploaded_file($_FILES['SwimMatch']["tmp_name"][$name],
                    "/tmp/" . $_FILES['SwimMatch']["name"][$name]);*/

                $imgObj = UploadedFile::getInstanceByName("SwimMatch[{$name}]");
                if(empty($imgObj)) {
                    continue;
                }

                $ossUpload = new UploadOss();
                $ossUpload->fileobj = $imgObj;

                $ret[$name] = $ossUpload->uploadOss();
            }
        }

        return $ret;
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
