<?php

namespace backend\controllers;

use backend\models\AddressCheck;
use backend\models\AddressCheckItem;
use backend\models\Pool;
use backend\models\Region;
use backend\service\AddressService;
use common\helpers\AddressCheckUtil;
use common\helpers\UploadOss;
use common\models\AddressFacilities;
use common\models\AddressLicence;
use Yii;
use backend\models\Address;
use backend\models\Search\AddressSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\web\Response;
use kartik\mpdf\Pdf;


/**
 * SwimAddressController implements the CRUD actions for Address model.
 */
class SwimAddressController extends \backend\controllers\Controller
{
    /**
     * {@inheritdoc}
     */
//    public function behaviors()
//    {
//        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
//        ];
//    }

    /**
     * Lists all Address models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AddressSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Address model.
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
     * Creates a new Address model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Address();

        if ($model->load(Yii::$app->request->post())
            && ($model->status = Address::STATUS_VALID)
            && $model->save()) {
            $uploadData = $this->uploadFile();
            if (isset($uploadData['imgurl']) && !empty($uploadData['imgurl'])) {
                $model->imgurl = $uploadData['imgurl'];
                $model->save();
            }
            $this->saveDuplicateAction($model->id);
            //return $this->redirect(['view', 'id' => $model->id]);
        }

        $model->province = '上海市';
        $city = (new Region())->getSonRegionByName($model->province);
        $model->lane = 3;
        $province = (new Region())->getSiblingRegionByPid(0);
        return $this->render('create', [
            'model' => $model,
            'province' => $this->pArray($province),
            'city' => $this->pArray($city),
            'district' => [],
        ]);
    }

    /**
     * Updates an existing Address model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $uploadData = $this->uploadFile();
            if (isset($uploadData['imgurl']) && !empty($uploadData['imgurl'])) {
                $model->imgurl = $uploadData['imgurl'];
                $model->save();
            }
            $this->saveDuplicateAction($model->id);
            //return $this->redirect(['view', 'id' => $model->id]);
        }

        $province = (new Region())->getSiblingRegionByPid(0);
        $city = (new Region())->getSiblingRegionByName($model->city);
        $district = (new Region())->getSonRegionByName($model->city);
        return $this->render('update', [
            'model' => $model,
            'province' => $this->pArray($province),
            'city' => $this->pArray($city),
            'district' => $this->pArray($district),
        ]);
    }

    /**
     * Deletes an existing Address model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (($model = $this->findModel($id)) !== null) {
            $model->status = Address::STATUS_INVALID;
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
            $model->publish = ($isPublish ? 1 : 2);
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
     * Finds the Address model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Address the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = Address::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function pArray(array $arr)
    {
        $t = [];
        foreach ($arr as $v) {
            $t[$v] = $v;
        }

        return $t;
    }

    protected function uploadFile()
    {
        $ret = [
            'imgurl' => '',
        ];
        foreach ($ret as $name => $url) {
            if ($_FILES['Address']['error'][$name] == 0) {
                $imgObj = UploadedFile::getInstanceByName("Address[{$name}]");
                if (empty($imgObj)) {
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

    /**
     * 后台接口-游泳场馆信息列表
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]|null
     */
    public function actionList()
    {
        $params = \Yii::$app->request->bodyParams;
        return AddressService::getList($params);
    }

    /**
     * 后台接口-游泳场馆信息详情
     * @return array|\yii\db\ActiveRecord|null
     */
    public function actionInfo()
    {
        self::getArrayParamErr(['id']);
        $params = \Yii::$app->request->bodyParams;
        return AddressService::getInfo($params);
    }

    /**
     * 后台接口-单个或者批量删除游泳场馆
     * @return mixed
     */
    public function actionAddressDelete()
    {
        self::getArrayParamErr(['id']);
        $params = \Yii::$app->request->bodyParams;
        return self::checkResponse(Address::deleteStatus($params));
    }

    /**
     * 后台接口-游泳场馆信息新增（第一步）
     * @return mixed
     */
    public function actionAddressAdd()
    {
        self::getArrayParamErr(['name', 'social_credit_code', 'legal_representative', 'address', 'license_url', 'open_license',
            'high_risk_deadline', 'principal', 'mobile', 'issuing_authority', 'nature_business', 'address_person', 'district', 'type',]);
        $params = \Yii::$app->request->bodyParams;
        $params['phone'] = $params['mobile'];
        return self::checkResponse(Address::add($params));
    }

    /**
     * 后台接口-游泳场馆信息新增泳池
     * @return mixed
     */
    public function actionAddressAddPool()
    {
        self::getArrayParamErr(['sid', 'name', 'temperature', 'long', 'wide', 'max_water_depth', 'quantity']);
        $params = \Yii::$app->request->bodyParams;
        $params['type'] = $params['name'];
        $params['area'] = (string)($params['long'] * $params['wide']);
        return self::checkResponse(Pool::add($params));
    }

    /**
     * 后台接口-删除泳池
     * @return mixed
     */
    public function actionDeletePool()
    {
        self::getArrayParamErr(['id']);
        $params = \Yii::$app->request->bodyParams;
        return self::checkResponse(Pool::deleteStatus($params));
    }

    /**
     * 后台接口-游泳场馆泳池列表
     * @return mixed
     */
    public function actionPoolList()
    {
        self::getArrayParamErr(['sid']);
        $params = \Yii::$app->request->bodyParams;
        return AddressService::poolList($params);
    }

    /**
     * 后台接口-场馆设施设备拥有情况上传（新增场馆第二步）
     * @return mixed
     */
    public function actionFacilitiesAdd()
    {
        self::getArrayParamErr(['sid', 'locke_room', 'toilet', 'clinic', 'shower_room', 'circulating_equipment', 'ventilation_facilities', 'foot_soaking_tank', 'disinfection_facilities',]);
        $params = \Yii::$app->request->bodyParams;
        return self::checkResponse(AddressFacilities::add($params));
    }

    /**
     * 后台接口-场馆设施设备拥有情况详情
     * @return array|\yii\db\ActiveRecord|null
     */
    public function actionFacilitiesInfo()
    {
        self::getArrayParamErr(['sid']);
        $params = \Yii::$app->request->bodyParams;
        return AddressFacilities::findOneArray(['sid' => $params['sid'], 'status' => AddressFacilities::NORMAL_STATUS]) ?? [];
    }

    /**
     * 后台接口-场馆其他证照及其他照片上传（新增场馆第四步）
     * @return mixed
     */
    public function actionAddressImageAdd()
    {
        self::getArrayParamErr(['address_id', 'imgurl', 'remarks', 'type']);
        $params = \Yii::$app->request->bodyParams;
        return self::checkResponse(AddressLicence::add($params));
    }

    /**
     * 后台接口-场馆其他证照及其他照片列表
     * @return array|string|\yii\db\ActiveRecord[]
     */
    public function actionAddressImageInfo()
    {
        self::getArrayParamErr(['address_id']);
        $params = \Yii::$app->request->bodyParams;
        return AddressLicence::findAllArray(['address_id' => $params['address_id'], 'status' => AddressLicence::NORMAL_STATUS]);
    }

    /**
     * 后台接口-游泳场馆信息详情-救生员详情
     * @return array|\yii\db\ActiveRecord|null
     */
    public function actionLifeguard()
    {
        self::getArrayParamErr(['id']);
        $params = \Yii::$app->request->bodyParams;
        return AddressService::lifeguard($params);
    }

    /**
     * 后台接口-游泳场馆信息详情-救生员详情列表
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]|null
     */
    public function actionLifeguardList()
    {
        $params = \Yii::$app->request->bodyParams;
        return AddressService::lifeguardList($params);
    }

    /**
     * 最近场馆检查获取api
     * @return array
     */
    public function actionOneCheck()
    {
        self::getArrayParamErr(['address_id']);
        $addressID = Yii::$app->request->post("address_id");
        $checkItem = (new AddressCheckItem())->apiCheckList($addressID);
        return $checkItem;
    }

    /**
     * 后台接口-场馆检查列表
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]|null
     */
    public function actionCheckList()
    {
//        self::getArrayParamErr(['address_id']);
        $params = \Yii::$app->request->bodyParams;
        return AddressService::checkList($params);
    }

    /**
     * 后台接口-检查详情pdf
     * @return string|void
     * @throws \Mpdf\MpdfException
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCheckPdf()
    {
        self::getArrayParamErr(['check_id']);
        $checkID = Yii::$app->request->post("check_id");
        $model = AddressCheck::findOne($checkID);
        if (!$model) return self::errorOut('id不存在');
        $swimAddressModel = Address::findOne($model->swim_address_id);
        $arrayDataProvider = (new AddressCheckUtil())->getCheckDetail($checkID, false);
        $html = (new AddressCheckUtil())->complicateHtml($arrayDataProvider);

        //判断文件是否存在于oss，若存在就不用再次上传
        $dir = 'pdf';
        if (!file_exists($dir) || !is_dir($dir)) {
            mkdir($dir, 0777);
        }
        //此处根据环境添加pdf名称前缀，简易操作就不写在配置里了
//        $env = 'test-center';
        $env = 'prod-center';
        $localPath = $dir . '/' . $env . $checkID . '.pdf';
        $ossUpload = new UploadOss();
        $exist = $ossUpload->ossclient->doesObjectExist($ossUpload->bucket, $localPath);

        if (!$exist) {
            // get your HTML raw content without any layouts or scripts
            $content = $this->renderPartial('@backend/views/swim-address-check/view', [
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
                'filename' => $checkID . '.pdf',
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

            file_put_contents($localPath, $pdf->render());
            Yii::$app->response->format = Response::FORMAT_JSON;
//        $url = ((strpos($_SERVER['HTTP_HOST'], 'pudong') === false) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] .'/' . $localPath;
//        $imgObj = UploadedFile::getInstanceByName($url);
//        if(empty($imgObj)) {
//            return false;
//        }
            $url = dirname(dirname(__DIR__)) . '/backend/web/' . $localPath;

//        $ossUpload->fileobj = $imgObj;

            $url = $ossUpload->uploadOss(['file_name' => $localPath, 'file' => $url]);
        } else {
            $url = Yii::$app->params['oss']['oss_url'] . $localPath;
        }
        return $url;
    }

    /**
     * 后台接口-工单详情
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]|null
     */
    public function actionWorkOrderInfo()
    {
        self::getArrayParamErr(['work_order_id']);
        $params = \Yii::$app->request->bodyParams;
        return AddressService::workOrderInfo($params);
    }

    /**
     * 后台接口-场馆所需企查查模糊搜索接口
     * @return array
     */
    public function actionQiChaCha()
    {
        self::getArrayParamErr(['name']);
        $params = \Yii::$app->request->bodyParams;
        return AddressService::qiChaCha($params);
    }

    /**
     * 后台接口-新增游泳场馆第一步的高危证照ocr
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\db\Exception
     */
    public function actionOcr()
    {
        self::getArrayParamErr(['url']);
        $params = \Yii::$app->request->bodyParams;
        return AddressService::Ocr($params);
    }


}
