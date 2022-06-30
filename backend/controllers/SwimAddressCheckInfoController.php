<?php

namespace backend\controllers;

use backend\models\Address;
use common\helpers\AddressCheckUtil;
use kartik\mpdf\Pdf;
use Yii;
use backend\models\AddressCheck;
use backend\models\Search\AddressCheckSearch;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\rest\Controller;

/**
 * SwimAddressCheckInfoController implements the CRUD actions for AddressCheck model.
 */
class SwimAddressCheckInfoController extends Controller
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
     * Lists all AddressCheck models.
     * @return mixed
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;
        $searchModel = new AddressCheckSearch();
        $dataProvider = $searchModel->search($params);
        $dataProvider->sort = false;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AddressCheck model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $addressID = Yii::$app->user->getIdentity()->swim_address_id;
        if ($addressID > 0) {
            if ($addressID != $model->swim_address_id) {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        $swimAddressModel = Address::findOne($model->swim_address_id);
        $arrayDataProvider = (new AddressCheckUtil())->getCheckDetail($id);
        $html = (new AddressCheckUtil())->complicateHtml($arrayDataProvider);

        return $this->render('view', [
            'model' => $model,
            'detail' => $arrayDataProvider,
            'html' => $html,
            'province' => $swimAddressModel->province,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionExport($id)
    {
        $model = $this->findModel($id);
        $addressID = Yii::$app->user->getIdentity()->swim_address_id;
        if ($addressID > 0) {
            if ($addressID != $model->swim_address_id) {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        $swimAddressModel = Address::findOne($model->swim_address_id);
        $arrayDataProvider = (new AddressCheckUtil())->getCheckDetail($id, false);
        $html = (new AddressCheckUtil())->complicateHtml($arrayDataProvider);

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('view', [
            'model' => $model,
            'detail' => $arrayDataProvider,
            'html' => $html,
            'province' => $swimAddressModel->province,
        ]);

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8, //防止中文乱码
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_DOWNLOAD, //DEST_DOWNLOAD
            'filename' => (new Address())->getNameByID($model->swim_address_id) . '检查情况.pdf',
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => [
                'title' => '每日检查报告',
                'autoLangToFont' => true,    //这几个配置加上可以显示中文
                'autoScriptToLang' => true,  //这几个配置加上可以显示中文
                'autoVietnamese' => true,    //这几个配置加上可以显示中文
                'autoArabic' => true,        //这几个配置加上可以显示中文
            ],
            // call mPDF methods on the fly
            'methods' => [
                //'SetHeader'=>['Krajee Report Header'],
                //'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    /**
     * Finds the AddressCheck model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AddressCheck the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AddressCheck::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
