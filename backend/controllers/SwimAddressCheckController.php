<?php

namespace backend\controllers;

use backend\models\Address;
use backend\models\AddressCheckDetail;
use common\helpers\AddressCheckUtil;
use common\helpers\CurlTools;
use kartik\mpdf\Pdf;
use Yii;
use backend\models\AddressCheck;
use backend\models\Search\AddressCheckSearch;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\rest\Controller;

/**
 * SwimAddressCheckController implements the CRUD actions for AddressCheck model.
 */
class SwimAddressCheckController extends Controller
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
        $searchModel = new AddressCheckSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
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
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
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
     * Deletes an existing Address model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (($model = $this->findModel($id)) !== null) {
            $model->status = AddressCheck::STATUS_INVALID;
            $flag = $model->save();
            $flashKey = 'success';
            $flashValue = '删除成功';
            if (!$flag) {
                $flashKey = 'danger';
                $flashValue = '删除失败';
            }
            Yii::$app->session->setFlash($flashKey, $flashValue, false);
        }

        if (Yii::$app->request->referrer) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionExport($id)
    {
        $model = $this->findModel($id);
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

    public function actionPositionInfo($id)
    {
        $model = $this->findModel($id);
        $modelAddress = $model->address;
        if (isset($modelAddress) && !empty($modelAddress->longitude) && !empty($model->longitude)) {
            $url = 'https://restapi.amap.com/v3/staticmap?size=750*500&'
                . 'markers=mid,0xFF0000,检:' . $model->longitude . ',' . $model->latitude . '|'
                . 'mid,0xFF0000,馆:' . $modelAddress->longitude . ',' . $modelAddress->latitude
                . '&key=' . Yii::$app->params['gaodejskey'];
            $pngContent = CurlTools::Curl($url);
            return $this->renderAjax('position-info', ['data' => base64_encode($pngContent)]);
        }
        return $this->renderContent('当前检查没有位置信息');
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

    /**
     * @param $id
     * @param $isView boolean 页面展示和转html中 针对图片的样式不同
     * @return ArrayDataProvider
     * @throws NotFoundHttpException
     */
    protected function getCheckDetail($id, $isView = true)
    {
        $model = $this->findModel($id);
        $swimAddressModel = Address::findOne($model->swim_address_id);
        $checkData = (new AddressCheckDetail())->getDetailByCheckID($id);
        $resultData = [
            //['name' => 'ID', 'info' => $model->id],
            ['name' => '场所名称', 'info' => $swimAddressModel->district  . '&nbsp;' . $swimAddressModel->name],
            ['name' => '检查内容', 'info' =>
                '检查情况&nbsp;请打<span style="font-family: Arial Unicode MS, Lucida Grande">&#10004;</span>'],
        ];
        foreach ($checkData as $name => $value) {
            $html = '';
            $checkedSymbol = '<span style="font-family: Arial Unicode MS, Lucida Grande">&#9745;</span>';
            $uncheckedSymbol = '<span style="font-family: Arial Unicode MS, Lucida Grande">&#9744;</span>';
            //$checkedSymbol = '<span style="font-family: Arial Unicode MS, Lucida Grande">&#10004;</span>';
            //$uncheckedSymbol = '<span style="font-family: Arial Unicode MS, Lucida Grande">&#10008;</span>';
            //select option
            $resultSelectExist = isset($value['result']['select']);
            if (isset($value['list']['selectOption'])) {
                foreach ($value['list']['selectOption'] as $kOpt => $options) {
                    //mpdf 不支持checkbox，用css实现
                    //https://stackoverflow.com/questions/658044/tick-symbol-in-html-xhtml
                    $optionSelected = isset($value['result']['select'][$kOpt]);
                    foreach ($options as $option => $defaultSelect) {
                        if ($resultSelectExist && $optionSelected
                            && in_array($option, $value['result']['select'][$kOpt])) {
                            $symbol = $checkedSymbol;
                        } else {
                            $symbol = $uncheckedSymbol;
                        }
                        $html .= $symbol . $option . '&nbsp;&nbsp;';
                    }
                    $html .= '|&nbsp;&nbsp;';
                }
                $html = substr($html, 0, -1 * strlen('|&nbsp;&nbsp;'));
            }
            //input option
            if (isset($value['list']['inputOption'])) {
                if ($html != '') {
                    $html .= '|&nbsp;&nbsp;';
                }
                if (isset($value['result']['input'])) {
                    $html .= implode('&nbsp;&nbsp;|&nbsp;&nbsp;', $value['result']['input']);
                } else {
                    $value['list']['inputOption'] = array_map(
                        function($str){return str_replace('xxx', '&nbsp;&nbsp;&nbsp;', $str);},
                        $value['list']['inputOption']);
                    $html .= implode('&nbsp;&nbsp;|&nbsp;&nbsp;', $value['list']['inputOption']);
                }
            }
            //image option
            if ((isset($value['list']['imageOption']) || isset($value['list']['signatureOption'])) && isset($value['result']['image'])) {
                foreach ($value['result']['image'] as $idx => $url) {
                    /*$mod = $isView ? 5 : 3;
                    if ($idx != 0 && $idx % $mod == 0) {
                        $html .= '<br/><br/>';
                    }*/
                    if ($isView) {
                        $html .= '&nbsp;&nbsp;<img src="' . $url . '" style="width:250px;"/>';
                    } else {
                        //官方说明：https://mpdf.github.io/what-else-can-i-do/images.html
                        /*$html .= '&nbsp;&nbsp;<div style="position: absolute; left:0; right: 0; top: 0; bottom: 0;">'
                            . '<img src="' . $url . '" style="width:100px; margin:0;"/></div>';*/
                        $html .= '&nbsp;&nbsp;<img src="' . $url . '" style="width:150px; margin:0;"/>';
                    }
                }
            }
            $resultData[] = [
                'name' => $name,
                'info' => $html,
                'list' => $value['list'],
                'result' => $value['result'],
            ];
        }
        $resultData[] = ['name' => '检查日期', 'info' => $model->check_date];
        return new ArrayDataProvider([
            'allModels' => $resultData,
            'pagination' => false, // 可选 不分页
            'sort' => false,
        ]);
    }

    protected function complicateHtml(ArrayDataProvider $provider)
    {
        $data = $provider->allModels;
        $len = count($data);
        $html = '';

        $rowspan = 1;
        $rowspanName = '';
        $dupIndex = 1;
        for ($i = 0; $i < $len ; $i++) {
            $pos = strpos($data[$i]['name'], '-');
            if ($pos === false) {
                $html .= '<tr data-key="' . $i . '"><td colspan="2" style="width:280px;">' . $data[$i]['name']
                    . '</td><td>' . $data[$i]['info'] .  '</td></tr>';
            } else {
                if ($rowspanName == '') {
                    $rowspanName = substr($data[$i]['name'], 0, $pos);
                    $dupIndex = $i + 1;
                    while (true) {
                        if (substr($data[$dupIndex]['name'], 0, $pos) == $rowspanName) {
                            $dupIndex++;
                            $rowspan++;
                        } else {
                            break;
                        }
                    }
                    $rowSpanHtml = ($rowspan != 1) ? 'rowspan="' . $rowspan . '"' : '';
                    $html .= '<tr data-key="' . $i . '"><td ' . $rowSpanHtml . ' style="width:80px;">'
                        . $rowspanName
                        . '</td><td>' . substr($data[$i]['name'], $pos + 1)
                        . '</td><td>' . $data[$i]['info'] .  '</td></tr>';
                } else {
                    $html .= '<tr data-key="' . $i . '">'
                        . '<td style="width:200px;">' . substr($data[$i]['name'], $pos + 1)
                        . '</td><td>' . $data[$i]['info'] .  '</td></tr>';
                }
            }

            if (($rowspanName != '') && ($dupIndex == ($i + 1))) {
                $dupIndex = 1;
                $rowspanName = '';
                $rowspan = 1;
            }
        }

        return '<table class="table table-striped table-bordered"><tbody>' . $html . '</tbody></table>';
    }
}
