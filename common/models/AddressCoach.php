<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "swim_address_coach".
 *
 * @property int $id
 * @property string $coach_id 救生员/教练id
 * @property string $address_id 所属泳馆id
 * @property int $type 人员类型（如：01-救生员、02-教练）
 * @property string $avatar 头像 Url 地址
 * @property string $name 姓名
 * @property int $gender 性别：0-女；1-男；
 * @property string $birth 出生年月
 * @property string $phone 手机
 * @property string $email 邮箱
 * @property string $introduction 个人简介
 * @property string $practice_certificate_code 执业证书编号
 * @property int $level 专业级别：01-初级；02-中级；03-高级；
 * @property int $last_access 最后更新时间
 * @property int $status 1-有效；2-删除
 * @property int|null $create_time
 * @property string|null $update_time
 */
class AddressCoach extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_address_coach';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'gender', 'level', 'last_access', 'status', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
            [['coach_id', 'address_id'], 'string', 'max' => 32],
            [['avatar', 'name', 'email', 'introduction', 'practice_certificate_code'], 'string', 'max' => 100],
            [['birth'], 'string', 'max' => 10],
            [['phone'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'coach_id' => '救生员/教练id',
            'address_id' => '所属泳馆id',
            'type' => '人员类型（如：01-救生员、02-教练）',
            'avatar' => '头像 Url 地址',
            'name' => '姓名',
            'gender' => '性别：0-女；1-男；',
            'birth' => '出生年月',
            'phone' => '手机',
            'email' => '邮箱',
            'introduction' => '个人简介',
            'practice_certificate_code' => '执业证书编号',
            'level' => '专业级别：01-初级；02-中级；03-高级；',
            'last_access' => '最后更新时间',
            'status' => '1-有效；2-删除',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
