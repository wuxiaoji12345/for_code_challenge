<?php

namespace backend\controllers;

use common\helpers\UploadOss;
use Yii;
use backend\models\Banners;
use backend\models\Search\BannersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * SwimBannersController implements the CRUD actions for Banners model.
 */
class SwimBannersController extends Controller
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
     * Lists all Banners models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BannersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = false;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Banners model.
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
     * Creates a new Banners model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionCreate()
    {
        $model = new Banners();

        $flag = $model->load(Yii::$app->request->post());
        if ($flag && $this->isJson($model->jumpvalue)) {
            $model->status = Banners::STATUS_VALID;
            $uploadData = $this->uploadFile();
            if (isset($uploadData['imgurl']) && !empty($uploadData['imgurl'])) {
                $model->imgurl = $uploadData['imgurl'];
            }
            if ($model->starttime < $model->endtime) {
                $flag = $model->save();
            }
        }
        if ($flag) {
            $this->saveDuplicateAction($model->id);
            //return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Banners model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $flag = $model->load(Yii::$app->request->post());
        if ($flag && $this->isJson($model->jumpvalue)) {
            $uploadData = $this->uploadFile();
            if (isset($uploadData['imgurl']) && !empty($uploadData['imgurl'])) {
                $model->imgurl = $uploadData['imgurl'];
            }
            if ($model->starttime < $model->endtime) {
                $flag = $model->save();
            }
        }

        if ($flag) {
            $this->saveDuplicateAction($model->id);
            //return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Banners model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();

        if (($model = $this->findModel($id)) !== null) {
            $model->status = Banners::STATUS_INVALID;
            $model->save();
        }

        if (Yii::$app->request->referrer) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Banners model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Banners the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Banners::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function uploadFile()
    {
        $ret = [
            'imgurl' => '',
        ];
        foreach ($ret as $name => $url) {
            if ($_FILES['Banners']['error'][$name] == 0) {
                $imgObj = UploadedFile::getInstanceByName("Banners[{$name}]");
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

    protected function isJson($strJson)
    {
        if ($strJson == '') {
            return true;
        }
        json_decode($strJson);
        return (json_last_error() == JSON_ERROR_NONE);
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
