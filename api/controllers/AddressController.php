<?php

namespace api\controllers;

use api\dbmodels\dbPool;
use api\dbmodels\dbWorkOrder;
use api\models\AddressCheck;
use api\models\AddressCheckDetail;
use api\models\AddressCheckItem;
use api\models\AddressUserComment;
use api\models\UserChannelExtra;
use api\models\UserInfo;
use common\helpers\AddressCheckUtil;
use common\helpers\UploadOss;
use common\helpers\Utils;
use common\models\AddressNeighborhood;
use common\models\BaseModel;
use common\models\NationalStandard;
use common\models\WorkOrderIndex;
use kartik\mpdf\Pdf;
use Yii;
use api\models\Address;
use yii\db\Expression;
use api\controllers\Controller;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * AddressController implements the CRUD actions for Address model.
 */
class AddressController extends Controller
{
    /**
     * 场馆列表api
     * @return array
     */
    public function actionList()
    {
        $name = Yii::$app->request->get("name");
        $district = Yii::$app->request->get("district");
        $list = (new Address())->apiSearch($name, $district);
        foreach ($list as &$value) {
            if ($value['comment_num'] == 0) {
                $value['comment_score'] = 5;
            } else {
                $value['comment_score'] = number_format($value['comment_sum_score'] / $value['comment_num'], 1);
            }
            unset($value['comment_sum_score']);
            //新增返回最后一次检查时间
            $work = AddressCheck::findOneArray(['swim_address_id' => $value['id']], ['create_time'], 'create_time desc')['create_time'] ?? '';
            $work = $work ? date('Y-m-d H:i:s', $work) : '';
            $value['check_time'] = $work;
        }
        return self::dataOut($list);
    }

    /**
     * 场馆详情api
     * @return array
     */
    public function actionDetail()
    {
        $addressID = Yii::$app->request->get("address_id");
        $page = Yii::$app->request->get("page", 1);
        $length = Yii::$app->request->get("length", 20);
        $model = Address::findOne($addressID);
        if (isset($model)) {
            $comment = (new AddressUserComment())->apiCommentList($addressID, $page, $length);
            foreach ($comment as &$value) {
                $userInfoModel = UserInfo::findOne(['urid' => $value['user_id']]);
                $value['nickname'] = (isset($userInfoModel) ? $userInfoModel->nickname : '');
                $value['avatar'] = (isset($userInfoModel) ? $userInfoModel->avatarurl : '');
            }
            $poolinfo = dbPool::getPoolinfo($addressID);
            //新增返回最后一次检查时间
            $work = AddressCheck::findOneArray(['swim_address_id' => $addressID], ['create_time'], 'create_time desc')['create_time'] ?? '';
            $work = $work ? date('Y-m-d H:i:s', $work) : '';
            $data = [
                'name' => $model->name,
                'address' => $model->province . $model->city . $model->district . $model->address,
                'imgurl' => $model->imgurl,
                'comment_score' => ($model->comment_num == 0) ? 5 : number_format($model->comment_sum_score / $model->comment_num, 1),
                'comment_num' => $model->comment_num,
                'comment' => $comment,
                'poolinfo' => $poolinfo,
                'longitude' => $model->longitude,
                'latitude' => $model->latitude,
                'check_time' => $work,
            ];
            return self::dataOut($data);
        }
        self::errorOut('获取场馆详情失败');
    }

    /**
     * 获取用户评论内容api
     * @return mixed
     */
    public function actionUserCommentHistory()
    {
        $data = [
            'comment_score' => 5,
            'comment' => '',
        ];
        $addressID = Yii::$app->request->post("address_id");
        $userID = Yii::$app->request->post("urid");
        $modelUserComment = (new AddressUserComment())->getUserAddressComment($addressID, $userID);
        if (isset($modelUserComment)) {
            $data['comment_score'] = $modelUserComment->score;
            $data['comment'] = $modelUserComment->comment;
        }

        return self::dataOut($data);
    }

    /**
     * 用户场馆评论提交api
     * @return array
     */
    public function actionUserComment()
    {
        $addressID = Yii::$app->request->post("address_id");
        $userID = Yii::$app->request->post("urid");
        $score = Yii::$app->request->post("score");
        $comment = Yii::$app->request->post("comment");

        $flag = true;
        if ($addressID != 0 && $userID != 0 && $score != 0 && !empty($comment)) {
            if ($score < 0 || $score > 5) {
                self::errorOut('评分异常');
            }
            $score = intval($score);
            $modelUserComment = (new AddressUserComment())->getUserAddressComment($addressID, $userID);
            $transaction = Yii::$app->db->beginTransaction();
            try {
                //comment num & score update
                $updateData = [
                    'comment_num' => new Expression('comment_num + 1'),
                    'comment_sum_score' => new Expression('comment_sum_score + ' . $score),
                ];
                if (isset($modelUserComment)) {
                    unset($updateData['comment_num']);
                    $remainScore = $score - $modelUserComment->score;
                    $updateData['comment_sum_score'] = new Expression('comment_sum_score + ' . $remainScore);
                }
                Yii::$app->db->createCommand()->update(Address::tableName(),
                    $updateData, ['id' => $addressID])->execute();
                //
                (new AddressUserComment())->addOne($addressID, $userID, $score, $comment);
                $transaction->commit();
            } catch (\Exception $e) {
                $flag = false;
                $transaction->rollBack();
            }
        }

        if ($flag) {
            return self::dataOut([]);
        } else {
            self::errorOut('评论提交失败');
        }
    }

    /**
     * 场馆检查列表获取api
     * @return array
     */
    public function actionCheckList()
    {
        $addressID = Yii::$app->request->post("address_id");
        $checkItem = (new AddressCheckItem())->apiCheckList($addressID);
        return self::dataOut($checkItem);
    }

    /**
     * 场馆检查历史记录
     * @return mixed
     */
    public function actionCheckHistory()
    {
        $addressID = Yii::$app->request->post("address_id");
        $check_status = Yii::$app->request->post("check_status", '');
        $page = intval(Yii::$app->request->post("page", 1));
        $length = intval(Yii::$app->request->post("length", 10));
        $data = (new AddressCheck())->apiList($addressID, $page, $length, $check_status);
        foreach ($data['list'] as $idx => $value) {
            $data['list'][$idx]['item'] = (new AddressCheckItem())->apiCheckList($addressID, $value['id']);
            $data['list'][$idx]['swim_address_name'] = Address::findOneArray(['id' => $data['list'][$idx]['swim_address_id']])['name'] ?? '';
        }

        return self::dataOut($data);
    }

    public function actionCheckPdf()
    {
        $checkID = Yii::$app->request->post("check_id");
        $model = AddressCheck::findOne($checkID);
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

//        $localPath = 'pdf/' . $checkID . '.pdf';
            file_put_contents($localPath, $pdf->render());
            Yii::$app->response->format = Response::FORMAT_JSON;
            //        $url = ((strpos($_SERVER['HTTP_HOST'], 'pudong') === false) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . '/api/' . $localPath;
//        $url = ((strpos($_SERVER['HTTP_HOST'], 'pudong') === false) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] .'/' . $localPath;
//        $imgObj = UploadedFile::getInstanceByName($url);
//        if(empty($imgObj)) {
//            return false;
//        }
            $url = dirname(dirname(__DIR__)) . '/api/web/' . $localPath;

//            $ossUpload = new UploadOss();
//        $ossUpload->fileobj = $imgObj;

            $url = $ossUpload->uploadOss(['file_name' => $localPath, 'file' => $url]);
        } else {
            $url = Yii::$app->params['oss']['oss_url'] . $localPath;
        }
        return self::dataOut(['url' => $url]);
    }

    /**
     * 场馆每日检查内容提交api
     * @return array
     */
    public function actionDailyCheck()
    {
        $addressID = Yii::$app->request->post("address_id");
        $checkInfo = Yii::$app->request->post("check_info");
        $longitude = floatval(Yii::$app->request->post("longitude"));
        $latitude = floatval(Yii::$app->request->post("latitude"));
        $userChannelIDEncrypt = Yii::$app->request->post("user_channel_id");

        $checkInfo = json_decode($checkInfo, true);
        if (!is_array($checkInfo)) {
            self::errorOut('检查内容数据异常');
        }

        $userChannelID = Utils::ecbDecrypt(Yii::$app->params['channelIDKey'], $userChannelIDEncrypt);
        $userChannelExtra = UserChannelExtra::findOne([
            'user_channel_id' => $userChannelID,
            'status' => UserChannelExtra::STATUS_VALID
        ]);
        if (!isset($userChannelExtra) || ($userChannelExtra->is_checker != UserChannelExtra::CHECKER_YES)) {
            self::errorOut('角色不匹配,请检查');
        }

        $flag = false;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            //check
            $checkDate = date('Y-m-d H:i:s');
            $checkModel = new AddressCheck();
            $checkModel->swim_address_id = $addressID;
            $checkModel->user_channel_id = $userChannelID;
            $checkModel->check_date = $checkDate;
            $checkModel->longitude = $longitude;
            $checkModel->latitude = $latitude;
            $checkModel->status = AddressCheck::STATUS_VALID;
            if (!$checkModel->save()) {
                Yii::error(json_encode($checkModel->getErrors()));
                throw new \Exception('保存失败');
            }
            //detail
            $checkID = $checkModel->id;
            $submitItemIDs = [];
            $address = Address::findOneArray(['id' => $addressID]);
            $check_status = 1;
            $work_order = [];
            foreach ($checkInfo as $value) {
                $submitItemIDs[] = $value['item_id'];
                $checkResult = [];
                $checkItemModel = AddressCheckItem::findOne($value['item_id']);
                $info = $checkItemModel->info;
                $name = $checkItemModel->name;
                //要将检查类型加入检查表
                if ($name == '检查类型') {
                    $checkModel->type = array_values($value['select'])[0];
                }
                $info_decode = json_decode($info, true);

                if (isset($value['select'])) {
                    $checkResult['select'] = [];
                    foreach ($value['select'] as $k => $selectItem) {
                        $select_item = explode(',', $selectItem);
                        $checkResult['select'][$k] = $select_item;
                        //此处要判断是否有问题，要生成工单
                        foreach ($select_item as $v) {
                            if ($info_decode['selectOption'][$k][$v] == 0) {
                                $check_status = 2;
                                $data['title'] = $name . '异常';
                                $data['info'] = $name . ' ' . $v;
                                $data['create_time'] = time();
                                $data['venue_id'] = $addressID;
                                $data['venue_name'] = $address['name'];
                                $data['commit_id'] = $userChannelID;
                                $work_order[] = $data;
//                                 dbWorkOrder::create($data);
                            }
                        }
                    }
                }
                if (isset($value['input']) && isset($value['input'][0]) && !empty($value['input'][0])) {
                    //填入项也有需要判断而产生工单的
                    //实际在岗＜应在岗
                    //在岗持国职并年检＜应在岗
                    //以上两种情况属于异常，需生成相应工单
                    if ($value['item_id'] == 14) {
                        $item1 = (int)mb_substr($value['input'][1], -2, 1) == 0 ? mb_substr($value['input'][1], -1) : mb_substr($value['input'][1], -2, 1) . mb_substr($value['input'][1], -1);
                        $item2 = (int)mb_substr($value['input'][2], -2, 1) == 0 ? mb_substr($value['input'][2], -1) : mb_substr($value['input'][2], -2, 1) . mb_substr($value['input'][2], -1);
                        $item3 = (int)mb_substr($value['input'][3], -2, 1) == 0 ? mb_substr($value['input'][3], -1) : mb_substr($value['input'][3], -2, 1) . mb_substr($value['input'][3], -1);;
                        //现在有第四项
                        if (($item1 > $item2) || ($item1 > $item3)) {
                            $check_status = 2;
                            $data['title'] = $name . '异常';
                            $data['info'] = $name . ' ' . $value['input'][0] . ' ' . $value['input'][1] . ' ' . $value['input'][2];
                            $data['create_time'] = time();
                            $data['venue_id'] = $addressID;
                            $data['venue_name'] = $address['name'];
                            $data['commit_id'] = $userChannelID;
                            $work_order[] = $data;
                        }
                    }
                    //2. 水温与室温
                    //正常：水温26-30℃，室温应比水温大1～2℃。
                    //其他情况为异常，需生成相应工单
                    if ($value['item_id'] == 23) {
                        $item1 = mb_substr($value['input'][0], 6);
                        $item2 = mb_substr($value['input'][1], 6);
                        $diff = (int)$item2 - (int)$item1;
                        if (($item1 < 26 || $item1 > 30) || ($diff < 1 || $diff > 2)) {
                            $check_status = 2;
                            $data['title'] = $name . '异常';
                            $data['info'] = $name . ' ' . $value['input'][0] . ' ' . $value['input'][1];
                            $data['create_time'] = time();
                            $data['venue_id'] = $addressID;
                            $data['venue_name'] = $address['name'];
                            $data['commit_id'] = $userChannelID;
                            $work_order[] = $data;
                        }
                    }
                    $checkResult['input'] = $value['input'];
                }
                if (isset($value['image'])) {
                    $checkResult['image'] = $value['image'];
                }

                $result = json_encode($checkResult, JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT);
                //异常状态写入检查详情表
                (new AddressCheckDetail())->addOne($checkID, $value['item_id'], $result, $info, $check_status);
                if ($check_status == AddressCheckDetail::ABNORMAL_STATUS) $checkModel->check_status = $check_status;
                $check_status = AddressCheckDetail::NORMAL_STATUS;
            }
            //新增一个检查单号
            $checkModel->check_num = BaseModel::CITY . BaseModel::AREA_CODE_CN_NEW[$address['district']] . date('YmdHis') . substr(time(), -4);
            if (!$checkModel->save()) {
                Yii::error(json_encode($checkModel->getErrors()));
                throw new \Exception('保存失败');
            }
            //有异常就产生工单
            if ($work_order) {
                $index['index_title'] = $index['venue_name'] = $address['name'];
                $index['address_check_id'] = $checkID;
                $index['venue_id'] = $addressID;
                $index['type'] = WorkOrderIndex::ORDINARY_TYPE;
                $index['commit_id'] = $userChannelID;
                $index['create_time'] = time();
                //先查一下今天有多少工单
                $num = WorkOrderIndex::findOneArray(['from_unixtime(create_time)' => date('Y-m-d')], ['count(*) num'])['num'] ?? 0;
                $index['work_order_num'] = BaseModel::WORK_ORDER . BaseModel::AREA_CODE_CN_NEW[$address['district']] . date('YmdHi') . ($num + 1);
                $index_info = [];
                foreach ($work_order as $k => $tmp) {
                    $index_info[] = ($k + 1) . '、' . $tmp['title'];
                }
                $index['info'] = implode($index_info, "\n");
                dbWorkOrder::create($index, $work_order);
            }
            //没有的自动补上 检查项目
            $fullCheckList = (new AddressCheckItem())->checkList();
            foreach ($fullCheckList as $value) {
                if (!in_array($value['id'], $submitItemIDs)) {
                    (new AddressCheckDetail())->addOne($checkID, $value['id'], '{}', $value['info']);
                }
            }
            $transaction->commit();
            $flag = true;
        } catch (\Exception $e) {
            $flag = false;
            $transaction->rollBack();
//            self::errorOut($e->getMessage());
        }

        if ($flag) {
            return self::dataOut([]);
        } else {
            self::errorOut('场馆检查提交失败');
        }
    }

    public function actionUpload()
    {
        $fileKey = Yii::$app->request->post("fileKey");
        $ret = '';
        $imgObj = UploadedFile::getInstanceByName($fileKey);
        if (empty($imgObj)) {
            self::errorOut('没有上传文件: ' . $fileKey);
        }

        $ossUpload = new UploadOss();
        $ossUpload->fileobj = $imgObj;
        $ret = $ossUpload->uploadOss();
        if (empty($ret)) {
            self::errorOut('上传失败');
        }
        return self::dataOut(['url' => $ret]);
    }

    /**
     * 检察员的检查历史
     * @return mixed
     */
    public function actionCheckManHistory()
    {
        self::getArrayParamErr(['channel_id']);
        $channel_id = Yii::$app->request->post("channel_id");
        $check_status = Yii::$app->request->post("check_status", '');
        $district = Yii::$app->request->post("district", '');
        $item_id = Yii::$app->request->post("item_title", '');
        $page = intval(Yii::$app->request->post("page", 1));
        $length = intval(Yii::$app->request->post("length", 10));
        $data = (new AddressCheck())->checkManHistory($channel_id, $page, $length, $check_status, $district, $item_id);
        foreach ($data['list'] as $idx => &$value) {
            $value['id'] = $value['check_id'];
            $data['list'][$idx]['item'] = (new AddressCheckItem())->apiCheckList($channel_id, $value['id']);
            $data['list'][$idx]['swim_address_name'] = Address::findOneArray(['id' => $data['list'][$idx]['swim_address_id']])['name'] ?? '';
        }
        return self::dataOut($data);
    }

    /**
     * 街道列表
     * @return mixed
     */
    public function actionNeighborhoodList()
    {
        $data = AddressNeighborhood::findAllArray([]);
        $data = ArrayHelper::getColumn($data, 'name');
        return self::dataOut($data);
    }

    /**
     * 场馆检查国标说明列表
     * @return mixed
     */
    public function actionStandardList()
    {
        $data = NationalStandard::findAllArray([]);
        $data = ArrayHelper::getColumn($data, 'info');
        return self::dataOut($data);
    }

    /**
     * 检查项列表
     * @return mixed
     */
    public function actionCheckItemList()
    {
        //只有选择题才需要筛选
        $data = AddressCheckItem::findAllArray(['and', ['status' => BaseModel::NORMAL_STATUS], ['like', 'info', 'selectOption']]);
//        $data = ArrayHelper::getColumn($data, 'info');
        return self::dataOut($data);
    }
}
