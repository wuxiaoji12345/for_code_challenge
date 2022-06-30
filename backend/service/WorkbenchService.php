<?php


namespace backend\service;


use backend\models\AddressCheck;
use common\libs\Helper;
use common\models\Address;
use common\models\AddressCheckItem;

class WorkbenchService
{
    const PAGE_SIZE = 20;

    /**
     * 工作台-检查列表
     * @param $params
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]
     */
    public static function checkStatusList($params)
    {
        $page_info = Helper::makePageInfo($params, self::PAGE_SIZE);
        $area_code = AddressCheck::AREA_CODE_CN;
        $where = ['and'];
        $role = AddressCheck::isCheckerOrLeader();
        if ($role[0] == 'management') {
            $where[] = ['district' => $area_code[$role[1]['area_code']]];
        }
        if ($role[0] == 'leader') {
            $where[] = ['user_channel_id' => $role[1]['channel_id']];
        }
        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => Address::tableName() . ' a',
                'on' => 'ac.swim_address_id = a.id'],
        ];
        $where[] = ['ac.status' => AddressCheck::NORMAL_STATUS];
        $data = AddressCheck::findJoin('ac', $join, ['a.name address_name', 'a.district', 'ac.check_status', 'from_unixtime(ac.create_time,"%Y-%m-%d %H:%i:%s") time'],
            $where, true, true, 'ac.create_time desc', '', '', '', $page_info);

        if ($data['list']) {
            foreach ($data['list'] as &$v) {
                $v['check_status'] = AddressCheck::CHECK_STATUS_CN[$v['check_status']];
            }
            $today_time = strtotime(date('Y-m-d'));
            $where[] = ['>=', 'ac.create_time', $today_time];
//            $data['today_num'] = AddressCheck::findOneArray($where, ['count(*) num'])['num'] ?? 0;
            $data['today_num'] = AddressCheck::findJoin('ac', $join, ['count(*) num'],
                    $where, true, false)['num'] ?? 0;
        }
        return $data ?? '';
    }

    /**
     * 后台接口-子类检查项批量新增
     * @param $params
     * @return array
     * @throws \yii\db\Exception
     */
    public static function checkItemAdd($params)
    {
//        if (isset($params['id']) && $params['id']) {
//            $model = AddressCheckItem::findOne(['id' => $params['id']]);
//        } else {
//            $model = new AddressCheckItem();
//        }
//        $model->level = $params['level'] ?? 2;
//        $model->load($params, '');
//        if (!$model->save()) return [false, $model->getErrors()];
//        return [true, ''];
        foreach ($params['info_list'] as &$v) {
            if (empty($v['name'])) return [false, '检查项名称不能为空！'];
            $v['level'] = $params['level'];
            $v['info'] = isset($v['info']) ? json_encode($v['info'], JSON_UNESCAPED_UNICODE) : '';
            $v['create_time'] = time();
        }
        return [true, AddressCheckItem::insertOrUpdate('', $params['info_list'], true)];
    }

    public static function checkItemEdit($params)
    {
        $model = AddressCheckItem::findOne(['id' => $params['id']]);
        $params['info'] = isset($params['info']) ? json_encode($params['info']) : '';
        $model->load($params, '');
        if (!$model->save()) return [false, $model->getErrors()];
        return [true, ''];
    }


    /**
     * 后台接口-检查项列表
     * @param $params
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]
     */
    public static function checkItemList($params)
    {
        $page_info = Helper::makePageInfo($params, self::PAGE_SIZE);

        $join = [
            [
                'type' => 'LEFT JOIN',
                'table' => AddressCheckItem::tableName() . ' a1',
                'on' => 'a1.id = a.pid'],
        ];
        $data = AddressCheckItem::findJoin('a', $join, ['a.id', 'a.name', 'a1.name parent_name', 'a.weight', 'a.info', 'a.pid', 'a.update_time', 'a.national_standard'],
            ['a.status' => AddressCheckItem::NORMAL_STATUS], true, true, '', '', '', '', $page_info);

        if ($data['list']) {
            foreach ($data['list'] as &$v) {
                $check_item = self::getCheckItemInfo($v['info']);
                $v['parent_name'] = $v['parent_name'] ?? '';
                $v['info_cn'] = $check_item[0];
                $v['required'] = $check_item[1] ? '必填' : '非必填';
            }
        }
        return $data ?? '';
    }

    /**
     * 后台接口-父类检查项列表
     * @param $params
     * @return array|string|\yii\db\ActiveRecord[]
     */
    public static function checkItemParentList($params)
    {
        return AddressCheckItem::findAllArray(['status' => AddressCheckItem::NORMAL_STATUS, 'level' => 1]) ?? '';
    }

    /**
     * 检查项详情解析
     * @param $info
     * @return array
     */
    public static function getCheckItemInfo($info)
    {
        $arr = json_decode($info, true);
        $info = '';
        if (isset($arr['selectOption'])) {
            $info .= '选择项：';
            foreach ($arr['selectOption'] as $optionArr) {
                $info .= implode('，', array_keys($optionArr)) . ' | ';
            }
            $info = substr($info, 0, -2) . '<br/>';
        }
        if (isset($arr['inputOption'])) {
            $info .= '输入项：' . implode('，', $arr['inputOption']) . '<br/>';
        }
        if (isset($arr['imageOption'])) {
            $info .= '上传图片：最多' . $arr['imageOption']['max'] . '张<br/>';
        }
        if (isset($arr['signatureOption'])) {
            $info .= '电子签名：最多' . $arr['signatureOption']['max'] . '张<br/>';
        }
        $is_required = $arr['required'] ?? false;
        return [$info, $is_required];
    }


}