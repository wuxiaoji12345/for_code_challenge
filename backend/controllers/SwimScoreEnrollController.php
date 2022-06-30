<?php

namespace backend\controllers;

use Yii;
use backend\models\ScoreEnroll;
use backend\models\Search\ScoreEnrollSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SwimScoreEnrollController implements the CRUD actions for SwimScoreEnroll model.
 */
class SwimScoreEnrollController extends Controller
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
     * Lists all SwimScoreEnroll models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ScoreEnrollSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = false;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'params' => Yii::$app->request->queryParams,
        ]);
    }

    /**
     * Finds the SwimScoreEnroll model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScoreEnroll the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScoreEnroll::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
