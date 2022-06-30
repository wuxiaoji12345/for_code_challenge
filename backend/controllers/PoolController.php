<?php

namespace backend\controllers;

use backend\models\Address;
use Yii;
use backend\models\Pool;
use backend\models\Search\PoolSearch;
use yii\web\Controller;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Search\PoolQualitySearch;

/**
 * PoolController implements the CRUD actions for Pool model.
 */
class PoolController extends Controller
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
     * Lists all Pool models.
     * @return mixed
     */
    public function actionIndex()
    {
        Url::remember();
        $params = Yii::$app->request->queryParams;
        $searchModel = new PoolSearch();
        $dataProvider = $searchModel->search($params);
        $dataProvider->sort = false;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
        ]);
    }

    /**
     * Displays a single Pool model.
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
     * Creates a new Pool model.
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
        $model = new Pool();

        if ($model->load(Yii::$app->request->post())) {
            $model->sid = $addressID;
            if ($model->save()) {
                $this->saveDuplicateAction($model->id);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Pool model.
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
        if ($addressID != $model->sid) {
            Yii::$app->session->setFlash('danger', '非法操作', false);
            return $this->goBack();
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->sid = $addressID;
            if ($model->save()) {
                $this->saveDuplicateAction($model->id);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Pool model.
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
        if ($addressID != $model->sid) {
            Yii::$app->session->setFlash('danger', '非法操作', false);
            return $this->goBack();
        }

        $model->status = Pool::STATUS_INVALID;
        $model->save();

        if (Yii::$app->request->referrer && (strpos(Yii::$app->request->referrer, '/view') === false)) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionQuality($id)
    {
        $params = Yii::$app->request->queryParams;
        $searchModel = new PoolQualitySearch();
        $params['PoolQualitySearch']['poid'] = $id;
        $dataProvider = $searchModel->search($params);
        $dataProvider->sort = false;

        $info = '';
        $modelPool = $this->findModel($id);
        $modelAddress = Address::findOne($modelPool->sid);
        if (isset($modelAddress)) {
            $info = $modelAddress->name . ' ';
        }
        $info .= $modelPool->name;

        //
        $addressID = Yii::$app->user->getIdentity()->swim_address_id;
        if (($addressID != 0) && ($addressID != $modelPool->sid)) {
            Yii::$app->session->setFlash('danger', '非法操作', false);
            return $this->redirect('index');
        }

        return $this->render('quality', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => $params,
            'info' => $info,
        ]);
    }

    /**
     * Finds the Pool model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pool the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pool::findOne($id)) !== null) {
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
