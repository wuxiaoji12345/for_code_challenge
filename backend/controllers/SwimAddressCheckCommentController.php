<?php

namespace backend\controllers;

use backend\models\AddressCheck;
use common\helpers\UploadOss;
use Yii;
use backend\models\AddressCheckComment;
use backend\models\Search\AddressCheckCommentSearch;
use yii\web\Controller;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
/**
 * SwimAddressCheckCommentController implements the CRUD actions for SwimAddressCheckComment model.
 */
class SwimAddressCheckCommentController extends Controller
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
     * Lists all AddressCheckComment models.
     * @param integer $id
     * @return mixed
     */
    public function actionIndex($id)
    {
        Url::remember();
        $params = Yii::$app->request->queryParams;
        $params['SwimAddressCheckComment']['swim_address_check_id'] = $id;
        $searchModel = new AddressCheckCommentSearch();
        $dataProvider = $searchModel->search($params);
        $dataProvider->sort = false;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
        ]);
    }

    /**
     * Displays a single AddressCheckComment model.
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
     * Creates a new AddressCheckComment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws
     */
    public function actionCreate($id)
    {
        $model = new AddressCheckComment();
        $model->swim_address_check_id = $id;
        $modelAddressCheck = AddressCheck::findOne($id);
        if (!isset($modelAddressCheck)) {
            Yii::$app->session->setFlash('danger', '非法操作', false);
            return $this->render('create', [
                'model' => $model,
            ]);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->swim_address_id = $modelAddressCheck->swim_address_id;
            $model->bkurid = Yii::$app->getUser()->id;
            $model->create_time = time();
            $addressID = Yii::$app->getUser()->getIdentity()->swim_address_id;
            if ($addressID == 0) {
                $model->is_stadium = 2;
            } else {
                if ($modelAddressCheck->swim_address_id != $addressID) {
                    Yii::$app->session->setFlash('danger', '非法操作', false);
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            }
            $modelAddressCheck->comment_num += 1;

            if ($modelAddressCheck->save() && $model->save()) {
                $imgUrl = $this->uploadFile();
                if (!empty($imgUrl)) {
                    $model->imgurl = $imgUrl;
                    $model->save();
                }
                $this->saveDuplicateAction($model->id);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AddressCheckComment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $imgUrl = $this->uploadFile();
            if (!empty($imgUrl)) {
                $model->imgurl = $imgUrl;
                $model->save();
            }
            $this->saveDuplicateAction($model->id);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }*/

    /**
     * Deletes an existing AddressCheckComment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    /*public function actionDelete($id)
    {
        //$this->findModel($id)->delete();

        if (($model = $this->findModel($id)) !== null) {
            $model->status = 2;
            $model->save();
        }

        if (Yii::$app->request->referrer && (strpos(Yii::$app->request->referrer, '/view') === false)) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->redirect(['index']);
        }
    }*/

    /**
     * Finds the AddressCheckComment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AddressCheckComment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AddressCheckComment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function saveDuplicateAction($id)
    {
        return $this->goBack();
    }

    protected function uploadFile()
    {
        $ret = '';
        $imgObj = UploadedFile::getInstanceByName("AddressCheckComment[imgurl]");
        if(empty($imgObj)) {
            return $ret;
        }

        $ossUpload = new UploadOss();
        $ossUpload->fileobj = $imgObj;
        $ret = $ossUpload->uploadOss();

        return $ret;
    }
}
