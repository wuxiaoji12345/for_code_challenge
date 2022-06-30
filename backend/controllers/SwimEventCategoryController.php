<?php

namespace backend\controllers;

use common\models\UploadOss;
use Yii;
use common\models\MatchCategory;
use common\models\search\MatchCategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\UploadForm;
use common\components\Helper;
use yii\web\UploadedFile;

/**
 * MatchCategoryController implements the CRUD actions for MatchCategory model.
 */
class SwimEventCategoryController extends Controller
{
    
    public function actions()
    {
        return [
            'uploadPhoto' => [
                'class' => 'budyaga\cropper\actions\UploadAction',
                'url' => 'http://your_domain.com/uploads/user/photo',
                'path' => '@frontend/web/uploads/user/photo',
            ]
        ];
    }
    
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
     * Lists all MatchCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MatchCategorySearch();
        
        $queryParams    =   Yii::$app->request->queryParams;
        $queryParams[$searchModel->formName()]['gid']    =   Yii::$app->user->identity->group->id;
        $dataProvider = $searchModel->search($queryParams);
        

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MatchCategory model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MatchCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MatchCategory();
        
        if(Yii::$app->request->isPost)
        {
            $post   =   Yii::$app->request->post();
            $post['MatchCategory']['gid']   =   Yii::$app->user->identity->group->id;

            $logo = UploadedFile::getInstance($model, 'imgurl');


            if ($logo) {

                $ossupload              =   new UploadOss();
                $ossupload->fileobj     =   $logo;
                $post['MatchCategory']['imgurl'] = $ossupload->uploadOss();

            } else {
                $post['MatchCategory']['imgurl'] = isset( $model->oldAttributes['imgurl'])? $model->oldAttributes['imgurl']:"";
            }

            $bg = UploadedFile::getInstance($model, 'bkgurl');

            if ($bg) {
                $ossupload              =   new UploadOss();
                $ossupload->fileobj     =   $bg;
                $post['MatchCategory']['bkgurl'] = $ossupload->uploadOss();

            } else {
                $post['MatchCategory']['bkgurl'] = isset( $model->oldAttributes['bkgurl'])? $model->oldAttributes['bkgurl']:"";
            }


            if(!$model->load($post)||!$model->save())
            {
                Helper::setFlash($model, 'error');
            }
            return $this->redirect(['index']);
            
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
        
        
       
    }

    /**
     * Updates an existing MatchCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
         
        if(Yii::$app->request->isPost)
        {
            $post   =   Yii::$app->request->post();


            $logo = UploadedFile::getInstance($model, 'imgurl');


            if ($logo) {

                $ossupload              =   new UploadOss();
                $ossupload->fileobj     =   $logo;
                $post['MatchCategory']['imgurl'] = $ossupload->uploadOss();

            } else {
                $post['MatchCategory']['imgurl'] = isset( $model->oldAttributes['imgurl'])? $model->oldAttributes['imgurl']:"";
            }


            $bg = UploadedFile::getInstance($model, 'bkgurl');

            if ($bg) {
                $ossupload              =   new UploadOss();
                $ossupload->fileobj     =   $bg;
                $post['MatchCategory']['bkgurl'] = $ossupload->uploadOss();

            } else {
                $post['MatchCategory']['bkgurl'] = isset( $model->oldAttributes['bkgurl'])? $model->oldAttributes['bkgurl']:"";
            }




            if(!$model->load($post)||!$model->save())
            {
                Helper::setFlash($model, 'error');
            }
            return $this->redirect(['index']);
        }
        
        return $this->render('update', [
            'model' => $model,
        ]);
       
    }

    /**
     * Deletes an existing MatchCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MatchCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MatchCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MatchCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
