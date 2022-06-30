<?php

namespace common\helpers;


use backend\models\AddressCheckDetail;
use common\models\Address;
use common\models\AddressCheck;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;

class AddressCheckUtil
{
    public function getCheckDetail($id, $isView = true)
    {
        $model = $this->findModel($id);
        $swimAddressModel = Address::findOne($model->swim_address_id);
        $checkData = (new AddressCheckDetail())->getDetailByCheckID($id);
        $resultData = [
            //['name' => 'ID', 'info' => $model->id],
            ['name' => '场所名称', 'info' => $swimAddressModel->district . '&nbsp;' . $swimAddressModel->name],
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
                    $html .= implode('&nbsp;&nbsp;|&nbsp;&nbsp;', str_replace('xxx ', '', $value['result']['input']));
                } else {
                    $value['list']['inputOption'] = array_map(
                        function ($str) {
                            return str_replace('xxx', '&nbsp;&nbsp;&nbsp;', $str);
                        },
                        $value['list']['inputOption']);
                    $html .= implode('&nbsp;&nbsp;|&nbsp;&nbsp;', $value['list']['inputOption']);
                }
            }
            //image option
            if ((isset($value['list']['imageOption']) || isset($value['list']['signatureOption'])) && isset($value['result']['image'])) {
                $maxShowNum = isset($value['list']['imageOption']) ? $value['list']['imageOption']['max'] : $value['list']['signatureOption']['max'];
                foreach ($value['result']['image'] as $idx => $url) {
                    if (($idx + 1) > $maxShowNum) {
                        break;
                    }
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
                'check_status' => $value['check_status'],
            ];
        }
        $resultData[] = ['name' => '检查日期', 'info' => $model->check_date];
        return new ArrayDataProvider([
            'allModels' => $resultData,
            'pagination' => false, // 可选 不分页
            'sort' => false,
        ]);
    }

    public function complicateHtml(ArrayDataProvider $provider)
    {
        $data = $provider->allModels;
        $len = count($data);
        $html = '';

        $rowspan = 1;
        $rowspanName = '';
        $dupIndex = 1;
//        $html .= "<span>".print_r($data)."</span>";
        for ($i = 0; $i < $len; $i++) {
            $pos = strpos($data[$i]['name'], '-');
            if ($pos === false) {
                $html .= '<tr data-key="' . $i . '"><td colspan="2" style="vertical-align:middle;text-align:center;width:280px;font-weight:bold;">' . $data[$i]['name']
                    . '</td><td>' . $data[$i]['info'] . '</td></tr>';
            } else {
                $color = $data[$i]['check_status'] == AddressCheckDetail::NORMAL_STATUS ? '' : 'color:red';
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
                    $html .= '<tr data-key="' . $i . '"><td ' . $rowSpanHtml . ' style="vertical-align:middle;text-align:center;width:80px;font-weight:bold;">'
                        . $rowspanName
                        . '</td><td  style="vertical-align:middle;text-align:center;font-weight:bold;'.$color.'">' . substr($data[$i]['name'], $pos + 1)
                        . '</td><td style="'.$color.'">' . $data[$i]['info'] . '</td></tr>';
                } else {
                    $html .= '<tr data-key="' . $i . '">'
                        . '<td style="vertical-align:middle;text-align:center;width:200px;font-weight:bold;'.$color.'">' . substr($data[$i]['name'], $pos + 1)
                        . '</td><td style="'.$color.'">' . $data[$i]['info'] . '</td></tr>';
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

    protected function findModel($id)
    {
        if (($model = AddressCheck::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}