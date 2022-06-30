<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\data\Pagination;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use Yii;
use yii\helpers\ArrayHelper;

class BaseModel extends ActiveRecord
{
    const NORMAL_STATUS = 1;
    const ABNORMAL_STATUS = 2;

    const CITY = 'CH';
    const WORK_ORDER = 'WO';

    const SOON_ABNORMAL_STATUS = 3;
    const UNKNOWN = 4;

    const MAN = 1;
    const WOMEN = 2;

    const TIME_LIMIT_CN = [
        1 => '有效',
        2 => '过期',
        3 => '即将过期',
        4 => '未知',
    ];

    const CERT_TYPE_CN = [
        1 => '救生员证',
        2 => '国职证书'
    ];

    const POST_TYPE_CN = [
        1 => '救生员',
        2 => '教练'
    ];

    const CHECK_STATUS_CN = [
        1 => '正常',
        2 => '异常'
    ];

    const CERTIFICATES_STATUS_CN = [
        1 => '有效',
        2 => '失效'
    ];

    const EXAMINE_STATUS_CN = [
        1 => '未整改',
        2 => '整改通过'
    ];

    const GENDER_CN = [
        self::MAN => '男',
        self::WOMEN => '女'
    ];

    const WORK_ORDERS_TYPE_CN = [
        self::ORDINARY_TYPE => '普通',
        self::URGENT_TYPE => '紧急'
    ];

    const SOURCE_TYPE_CN = [
        1 => '检查整改',
        2 => '用户意见反馈'
    ];

    const COMMIT_TYPE_CN = [
        1 => '小程序提交',
        2 => '后台提交'
    ];

    const WORK_ORDER_STATUS_CN = [
        self::UNTREATED => '待处理',
        self::NOT_APPROVE => '待审核',
        self::APPROVED => '已完成'
    ];

    const THREE_PERSONNEL_TYPE_CN = [
        1 => '场所负责人',
        2 => '救生组长',
        3 => '水质管理员'
    ];

    const ORDINARY_TYPE = 1;
    const URGENT_TYPE = 2;

    const UNTREATED = 0;
    const NOT_APPROVE = 1;
    const APPROVED = 2;
    const DELETED = 3;

    const NOT_AGREE = 2;
    const AGREE = 1;

    const AREA_CODE_CN = [
        0 => "未知",
        1 => "上海市",
        9 => "浦东新区",
        2 => "黄浦区",
        5 => "静安区",
        3 => "徐汇区",
        4 => "长宁区",
        6 => "普陀区",
        7 => "虹口区",
        8 => "杨浦区",
        11 => "宝山区",
        10 => "闵行区",
        12 => "嘉定区",
        13 => "金山区",
        14 => "松江区",
        15 => "青浦区",
        16 => "奉贤区",
        17 => "崇明区",];

    const AREA_CODE_CN_NEW = [
        '上海市' => 100,
        '黄浦区' => 101,
        '徐汇区' => 104,
        '长宁区' => 105,
        '静安区' => 106,
        '普陀区' => 107,
        '虹口区' => 109,
        '杨浦区' => 110,
        '闵行区' => 112,
        '宝山区' => 113,
        '嘉定区' => 114,
        '浦东新区' => 115,
        '金山区' => 116,
        '松江区' => 117,
        '青浦区' => 118,
        '奉贤区' => 120,
        '崇明区' => 151];


    const CHECKER = '检查员';
    const SUPER_CHECKER = '超级检查员';
    const SUPER_MAN = '超级管理员';
    const VENUE_LEADER = '场所负责人';
    const UNIFIED_MANAGEMENT = '统管';

    public static $address_type = '区体育行政部门直属场馆池
市体育行政部门直属场馆池
大型游泳池（沙滩、水上乐园等）
学校游泳池
宾馆游泳池
度假村游泳池
社区游泳池
健身会所游泳池
其他游泳池';

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'create_time',
                'updatedAtAttribute' => false,
                'value' => time()
            ]
        ];
    }

    public static function deleteStatus($params)
    {
        $models = static::findAll(['id' => $params['id']]);
        if ($models) {
            foreach ($models as $model) {
                $model->status = static::ABNORMAL_STATUS;
                if (!$model->save()) {
                    return [false, $model->getErrors()];
                }
            }
        } else {
            return [false, 'id不存在'];
        }
        return [true, ''];
    }


    /**
     * @param $where
     * @param string[] $select
     * @param string $index
     * @param string $order
     * @param string $group
     * @param string $limit
     * @param bool $debug
     * @return array|string|ActiveRecord[]
     */
    public static function findAllArray($where = [], $select = ['*'], $index = '', $order = '', $group = '', $limit = '', $debug = false)
    {

        $query = self::find()->where($where)->select($select);
        if ($index != '') {
            $query->indexBy($index);
        }
        if ($order != '') {
            $query->orderBy($order);
        }
        if ($group != '') {
            $query->groupBy($group);
        }
        if ($limit != '') {
            $query->limit($limit);
        }
        if ($debug) {
            return $query->createCommand()->getRawSql();
        }
        $query->asArray();
        return $query->all();
    }

    /**
     * @param $where
     * @param string[] $select
     * @param string $order
     * @param string $group
     * @param string $index
     * @param bool $debug
     * @return array|ActiveRecord|null
     */
    public static function findOneArray($where = [], $select = ['*'], $order = '', $group = '', $index = '', $debug = false)
    {
//        $where = self::normalFilter($where, $bu_filter_flag);

        $query = self::find()->where($where)->select($select)->asArray();
        if ($order) $query->orderBy($order);
        if ($group) $query->groupBy($group);
        if ($index) $query->indexBy($index);
        if ($debug) return $query->createCommand()->getRawSql();
        return $query->one();
    }

    /**
     * 基础筛选条件
     * @param $where
     * @param $bu_filter_flag
     * @return array
     */
//    public static function normalFilter($where, $bu_filter_flag)
//    {
//        if (static::DEL_FLAG == true) {
//            $where = self::delFilter($where);
//        }
//        if ($bu_filter_flag) {
//            $where = self::buFilter($where);
//        }
//        return $where;
//    }

    /**
     * 删除标识
     * @param $where
     * @return array
     */
//    private static function delFilter($where)
//    {
//        $where = ['and', $where];
//        $where[] = ['=', 'status', static::DEL_STATUS_NORMAL];
//        return $where;
//    }

    /**
     * 获取错误信息
     * @param bool $all 是否全部返回
     * @return string
     */
    public function getErrStr($all = true)
    {
        if (!$this->hasErrors()) {
            return '';
        }
        if ($all) {
            $strArr = [];
            $errors = $this->getErrorSummary(true);
            foreach ($errors as $error) {
                $strArr[] = $error;
            }
            $errStr = implode(' ', $strArr);
        } else {
            $err = $this->getFirstErrors();
            $attr = array_keys($err);
            $errStr = $err[$attr[0]];
        }
        return $errStr;
    }

    /**
     * {@inheritdoc}
     * @param $bu_filter_flag boolean
     * @return static|ActiveRecord
     */
//    public static function findOne($condition, $bu_filter_flag = false)
//    {
//        return parent::find()->where($condition)->one();
//    }

    public static function findAll($condition)
    {
        return parent::findAll($condition);
    }

//    public function load($data, $formName = null)
//    {
//        return parent::load($data, $formName);
//    }

    public static function findJoin($alias, $join, $select = ['*'], $where = [], $asArray = true, $all = true, $order = '',
                                    $index = '', $group = '', $with = [], $pages = [], $limit = 0, $debug = false)
    {
        $model = parent::find();
        $prefix = '';
        if ($alias) {
            $model->alias($alias);
            $prefix = $alias . '.';
        }
        foreach ($join as $v) {
            $model->join($v['type'], $v['table'], $v['on']);
        }
        if (!isset($where[0]) || $where[0] != 'and') {
            $model->select($select)->where($where);
        } else {
            $model->select($select)->andWhere($where);
        }
        if ($group) {
            $model->groupBy($group);
        }
        if ($limit) {
            $model->limit($limit);
        }
        if ($pages) {
            $pagination = new Pagination(['pageSize' => $pages['page_size'], 'page' => $pages['page']]);
            $count = $model->count();
            $model->offset($pagination->offset)->limit($pagination->limit);
        }
        if ($with) {
            foreach ($with as $v) {
                $model->with($v);
            }
        }
        if ($order) {
            $model->orderBy($order);
        }
        if ($asArray) {
            $model->asArray();
        }
        if ($index) {
            $model->indexBy($index);
        }
        if ($debug) {
            return $model->createCommand()->getRawSql();
        }
        if ($pages) {
            $list = $model->all();
            return [
                'list' => $list,
                'count' => (int)$count
            ];
        }
        if ($all) {
            return $model->all();
        } else {
            return $model->one();
        }
    }

//    public static function find()
//    {
//        return new bQuery(get_called_class());
//    }

    /**
     * @param $attribute
     * @param $class string baseModel
     * @param $id
     * @param string $primary_key
     */
    public function getOne($attribute, $class, $id, $primary_key = 'id')
    {
        /* @var $class baseModel */
        $this->{$attribute} = $class::findOne([$primary_key => $id]);
    }

    /**
     * 批量插入
     * @param $value
     * @param $key
     * @return array
     * @throws \yii\db\Exception
     */
    public static function batchSave($value, $key)
    {
        $model = \Yii::$app->db->createCommand()->batchInsert(static::tableName(), $key, $value)->execute();
        if ($model) {
            return [true, '成功'];
        } else {
            return [false, '批量插入失败'];
        }
    }

    /**
     * 获取表的所有字段名
     * @param bool $trim
     * @param string $table_name
     * @return array
     */
    public static function getModelKey($trim = false, $table_name = '')
    {
        if (!$table_name) $table_name = static::tableName();
        $tableSchema = Yii::$app->db->schema->getTableSchema($table_name);
        $data = \yii\helpers\ArrayHelper::getColumn($tableSchema->columns, 'name', false);
        if ($trim) {
            unset($data[0]);
            array_pop($data);
            $data = array_values($data);
        }
        return $data;
    }

    /**
     * 通用批量插入
     * @param $value
     * @return array
     * @throws \yii\db\Exception
     */
    public static function trimBatchSave($value)
    {
        return self::batchSave($value, self::getModelKey(true));
    }

    /**
     * 自动拼凑不存在即插入存在即更新的语句
     * @param $class_name
     * @param $value
     * @param bool $is_values
     * @return string
     */
    public static function makeDataWithDuplicate($class_name, $value, $is_values = false)
    {
        $sql1 = $sql2 = $sql3 = "";
        if ($is_values) {
            foreach ($value as $flag => $tmp) {
                $part = "";
                foreach ($tmp as $k => $v) {
                    if ($flag == 0) {
                        $sql1 .= $k . ",";
                        $sql3 .= $k . "=VALUES(" . $k . "),";
                    }
                    $part .= "'" . $v . "',";
                }
                $sql2 .= '(' . substr($part, 0, -1) . '),';
            }
            $sql1 = substr($sql1, 0, -1);
            $sql2 = substr($sql2, 0, -1);
            $sql3 = substr($sql3, 0, -1);
            return "INSERT INTO " . $class_name . "(" . $sql1 . ")" . " VALUES" . $sql2 . " ON DUPLICATE KEY UPDATE " . $sql3;
        } else {
            foreach ($value as $k => $v) {
                $sql1 .= $k . ",";
                $sql2 .= ":" . $k . ",";
                $sql3 .= $k . "=:" . $k . ",";
            }
            $sql1 = substr($sql1, 0, -1);
            $sql2 = substr($sql2, 0, -1);
            $sql3 = substr($sql3, 0, -1);
            return "INSERT INTO " . $class_name . "(" . $sql1 . ")" . " VALUE(" . $sql2 . ") ON DUPLICATE KEY UPDATE " . $sql3;
        }
    }

    /**
     * 通用完成不存在即插入存在即更新的操作
     * @param $class_name
     * @param $value
     * @param bool $is_values
     * @return int
     * @throws \yii\db\Exception
     */
    public static function insertOrUpdate($class_name, $value, $is_values = false)
    {
        $class_name = $class_name ?: static::tableName();
        return self::commandExec(self::makeDataWithDuplicate($class_name, $value, $is_values), !$is_values ? $value : []);
//        return self::makeDataWithDuplicate($class_name, $value, $is_values);
    }

    /**
     * 原生执行sql
     * @param $sql
     * @param $value
     * @return int
     * @throws \yii\db\Exception
     */
    public static function commandExec($sql, $value = [])
    {
        return Yii::$app->db->createCommand($sql, $value)->execute();
    }

    /**
     * 原生查询
     * @param $sql
     * @param bool $s
     * @return array|false|\yii\db\DataReader
     * @throws \yii\db\Exception
     */
    public static function commandSelect($sql, $s = true)
    {
        $command = Yii::$app->db->createCommand($sql);
        if ($s) {
            return $command->queryAll();
        } else {
            return $command->queryOne();
        }
    }

    public static function getRoleNames()
    {
        $user_info = \Yii::$app->user->identity;
        $roleIDs = (new AuthAssignment())->userRoleIDs($user_info['id']);
        return [ArrayHelper::getColumn($roleNames = (new AuthRole())->roles($roleIDs), 'name'), $user_info];
    }

    /**
     * 登录用户的常用角色名
     * @return array
     */
    public static function isCheckerOrLeader()
    {
        $role = self::getRoleNames();
        if (in_array(self::CHECKER, $role[0])) {
            return ['checker', $role[1]];
        }
        if (in_array(self::VENUE_LEADER, $role[0])) {
            return ['leader', $role[1]];
        }
        if (in_array(self::UNIFIED_MANAGEMENT, $role[0])) {
            return ['management', $role[1]];
        }
        return ['other', $role[1]];
    }

    /**
     * 通用添加方法
     * @param $params
     * @return array
     */
    public static function add($params)
    {
        $model = isset($params['id']) && $params['id'] ? static::findOne(['id' => $params['id']]) : new static();
        $model->load($params, '');
        if ($model->save()) {
            return [true, $model];
        } else {
            return [false, $model->getErrors()];
        }
    }

    /**
     * 权限控制
     * @param $where
     * @param bool $is_area
     * @param string $key
     * @return mixed
     */
    public static function jurisdiction($where, $is_area = false, $key = 'area_code')
    {
        $area_code = AddressCheck::AREA_CODE_CN;
        $role = self::isCheckerOrLeader();
        if ($role[0] == 'management') {
            if (!$is_area) {
                $where[] = ['district' => $area_code[$role[1]['area_code']]];
            } else {
                $where[] = [$key => $role[1]['area_code']];
            }
        }
        if ($role[0] == 'leader') {
            $where[] = ['user_channel_id' => $role[1]['channel_id']];
        }
        return $where;
    }
}
