<?php


namespace console\controllers;


use api\models\AddressCheck;
use api\models\AddressCheckDetail;
use api\models\AddressCheckItem;
use \common\helpers\Utils;
use common\models\AddressLifeguard;

class HandleCheckStatusController extends \yii\console\Controller
{
    public $handle_num = 32000;
    public $start_num = 7692;

    /**
     * 处理检查项状态
     */
    public function actionHandle()
    {
        $num = 0;
        $items = AddressCheckItem::findAllArray([], ['*'], 'id');
        $ids = [];
        for ($i = $this->start_num; $i < $this->handle_num; $i++) {
            $model = AddressCheckDetail::findOne(['id' => $i]);
            if ($model) {
                $value = json_decode($model->result, true);
                $item_id = $model->swim_address_check_item_id;
//                echo $items[$item_id]['info'];die;
                $info_decode = json_decode($items[$item_id]['info'] ?? [], true);
                $check_status = AddressCheckDetail::NORMAL_STATUS;
                if (isset($value['select'])) {
                    $checkResult['select'] = [];
                    foreach ($value['select'] as $k => $selectItem) {
//                        $select_item = explode(',', $selectItem);
                        $select_item = $selectItem;
                        $checkResult['select'][$k] = $select_item;
                        //此处要判断是否有问题，要生成工单
                        foreach ($select_item as $v) {
                            if (!isset($info_decode['selectOption'][$k][$v])) {
                                $info_decode = json_decode($model->item_snapshot, true);
                            }
                            if ($info_decode['selectOption'][$k][$v] == 0) {
                                $check_status = AddressCheckDetail::ABNORMAL_STATUS;
                            }
                        }
                    }
                }
//                if (isset($value['input']) && isset($value['input'][0]) && !empty($value['input'][0])) {
//                    //填入项也有需要判断而产生工单的
//                    //实际在岗＜应在岗
//                    //在岗持国职并年检＜应在岗
//                    //以上两种情况属于异常，需生成相应工单
//                    if ($item_id == 14) {
//                        $item1 = mb_substr($value['input'][0], 7);
//                        $item2 = mb_substr($value['input'][1], 8);
//                        $item3 = mb_substr($value['input'][2], 13);
//                        if (($item1 > $item2) || ($item1 > $item3)) {
//                            $check_status = AddressCheckDetail::ABNORMAL_STATUS;
//                        }
//                    }
//                    //2. 水温与室温
//                    //正常：水温26-30℃，室温应比水温大1～2℃。
//                    //其他情况为异常，需生成相应工单
//                    if ($item_id == 23) {
//                        $item1 = mb_substr($value['input'][0], 6);
//                        $item2 = mb_substr($value['input'][1], 6);
//                        $diff = (int)$item2 - (int)$item1;
//                        if (($item1 < 26 || $item1 > 30) || ($diff < 1 || $diff > 2)) {
//                            $check_status = AddressCheckDetail::ABNORMAL_STATUS;
//                        }
//                    }
//                }
                if ($check_status == AddressCheckDetail::ABNORMAL_STATUS) {
                    $num++;
                    $ids[] = $model->swim_address_check_id;
                }
                $model->check_status = $check_status;
                if (!$model->save()) echo $model->getErrors();
                echo $i;
            }
        }
        AddressCheck::updateAll(['check_status' => AddressCheckDetail::ABNORMAL_STATUS], ['id' => $ids]);
        echo '成功发现异常项' . $num . '条';
    }
}