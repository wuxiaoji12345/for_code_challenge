<?php

namespace backend\controllers;

use backend\models\BackendSignupForm;
use Yii;
use backend\models\BackendUser;
use backend\models\Search\BackendUserSearch;
use yii\rbac\Assignment;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AddressUserController implements the CRUD actions for User model.
 */
class AddressUserController extends Controller
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;
        $searchModel = new BackendUserSearch();
        $dataProvider = $searchModel->search($params);
        $dataProvider->sort = false;
        $dataProvider->query->andWhere([
            '>', 'swim_address_id', 0
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws
     */
    public function actionCreate()
    {
        $model = new BackendSignupForm();
        $model->isNew = true;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->swim_address_id == 0) {
                Yii::$app->session->setFlash('danger', '请选择对应场馆', false);
                $model = new BackendSignupForm();
                $model->isNew = true;
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            if ($model->signup()) {
                $modelUser = BackendUser::findByUsername($model->username);
                $sql = "INSERT INTO `swim_auth_assignment` (`item_name`, `user_id`, `created_at`) 
                        VALUES ('场馆检查（场馆方）', '{$modelUser->id}', {$modelUser->created_at});";
                Yii::$app->db->createCommand($sql)->execute();
                return $this->redirect(['index']);
            }
        }

        $model = new BackendSignupForm();
        $model->isNew = true;
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $modelUser = $this->findModel($id);
        if ($modelUser->swim_address_id == 0) {
            Yii::$app->session->setFlash('danger', '非法操作', false);
            return $this->goBack();
        }
        $model = new BackendSignupForm();
        $model->isNew = false;
        if ($model->load(Yii::$app->request->post()) && $model->modifyInfo($id)) {
            return $this->redirect(['index']);
        } else {
            $model->username = $modelUser->username;
            $model->swim_address_id = $modelUser->swim_address_id;
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->swim_address_id == 0) {
            Yii::$app->session->setFlash('danger', '非法操作', false);
            return $this->goBack();
        }
        $model->status = BackendUser::STATUS_DELETED;
        $model->save();

        if (Yii::$app->request->referrer) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BackendUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BackendUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
