<?php

namespace backend\controllers;

use Yii;
use backend\models\AddressUserComment;
use backend\models\Search\AddressUserCommentSearch;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SwimAddressUserCommentController implements the CRUD actions for AddressUserComment model.
 */
class SwimAddressUserCommentController extends Controller
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
     * Lists all AddressUserComment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AddressUserCommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = false;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing AddressUserComment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();

        if (($model = $this->findModel($id)) !== null) {
            $model->status = AddressUserComment::STATUS_INVALID;
            $model->save();
            $updateData = [
                'comment_num' => new Expression('comment_num - 1'),
                'comment_sum_score' => new Expression('comment_sum_score - ' . $model->score),
            ];
            Yii::$app->db->createCommand()->update('swim_address',
                $updateData, ['id' => $model->swim_address_id])->execute();
        }

        if (Yii::$app->request->referrer) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the AddressUserComment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AddressUserComment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AddressUserComment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
