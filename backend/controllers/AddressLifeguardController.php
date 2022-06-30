<?php

namespace backend\controllers;

use Yii;
use backend\models\AddressLifeguard;
use backend\models\Search\AddressLifeguardSearch;
use yii\web\Controller;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AddressLifeguardController implements the CRUD actions for AddressLifeguard model.
 */
class AddressLifeguardController extends Controller
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
     * Lists all AddressLifeguard models.
     * @return mixed
     */
    public function actionIndex()
    {
        Url::remember();
        $params = Yii::$app->request->queryParams;
        $searchModel = new AddressLifeguardSearch();
        $dataProvider = $searchModel->search($params);
        $dataProvider->sort = false;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
        ]);
    }

    /**
     * Displays a single AddressLifeguard model.
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
     * Creates a new AddressLifeguard model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws
     */
    public function actionCreate()
    {
        $addressID = Yii::$app->user->getIdentity()->swim_address_id;
        if ($addressID == 0) {
            Yii::$app->session->setFlash('danger', '当前操作仅适用于场馆用户', false);
            return $this->goBack();
        }

        $model = new AddressLifeguard();

        if ($model->load(Yii::$app->request->post())) {
            $model->swim_address_id = $addressID;
            $model->create_time = time();
            if ($model->save()) {
                $this->saveDuplicateAction($model->id);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AddressLifeguard model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws
     */
    public function actionUpdate($id)
    {
        $addressID = Yii::$app->user->getIdentity()->swim_address_id;
        if ($addressID == 0) {
            Yii::$app->session->setFlash('danger', '当前操作仅适用于场馆用户', false);
            return $this->goBack();
        }
        $model = $this->findModel($id);
        if ($addressID != $model->swim_address_id) {
            Yii::$app->session->setFlash('danger', '非法操作', false);
            return $this->goBack();
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->swim_address_id = $addressID;
            if ($model->save()) {
                $this->saveDuplicateAction($model->id);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AddressLifeguard model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return \yii\web\Response
     * @throws
     */
    public function actionDelete($id)
    {
        $addressID = Yii::$app->user->getIdentity()->swim_address_id;
        if ($addressID == 0) {
            Yii::$app->session->setFlash('danger', '当前操作仅适用于场馆用户', false);
            return $this->goBack();
        }
        $model = $this->findModel($id);
        if ($addressID != $model->swim_address_id) {
            Yii::$app->session->setFlash('danger', '非法操作', false);
            return $this->goBack();
        }
        $model->status = AddressLifeguard::STATUS_INVALID;
        $model->save();

        if (Yii::$app->request->referrer && (strpos(Yii::$app->request->referrer, '/view') === false)) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the AddressLifeguard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AddressLifeguard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AddressLifeguard::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function saveDuplicateAction($id)
    {
        $ckOption = Yii::$app->request->post('ckOption');
        if ($ckOption == 'view') {
            return $this->redirect(['view', 'id' => $id]);
        } elseif ($ckOption == 'create') {
            return $this->redirect(['create']);
        } elseif ($ckOption == 'update') {
            return $this->redirect(['update', 'id' => $id]);
        } else {
            return $this->redirect(['view', 'id' => $id]);
        }
    }
}
