<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "swim_event_group".
 *
 * @property int $id 组织id
 * @property string $regname 团队名称
 * @property int $category_id 分类id
 * @property int $urid 关联match_user id
 * @property int $typeid
 * @property int $matchid
 * @property string $groupcode 队伍码
 * @property int $state 状态，1-有效；2-无效
 * @property string $unit 单位
 * @property string $leader 领队姓名
 * @property string $mobile 领队手机号
 * @property string $grouptype
 * @property string $groupinfos
 * @property int $create_time 创建时间
 * @property string $update_time 更新时间
 */
class __EventGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_event_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'urid', 'typeid', 'matchid', 'state', 'create_time'], 'integer'],
            [['urid'], 'required'],
            [['groupinfos'], 'string'],
            [['update_time'], 'safe'],
            [['regname'], 'string', 'max' => 255],
            [['groupcode', 'grouptype'], 'string', 'max' => 16],
            [['unit'], 'string', 'max' => 128],
            [['leader'], 'string', 'max' => 32],
            [['mobile'], 'string', 'max' => 13],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'regname' => 'Regname',
            'category_id' => 'Category ID',
            'urid' => 'Urid',
            'typeid' => 'Typeid',
            'matchid' => 'Matchid',
            'groupcode' => 'Groupcode',
            'state' => 'State',
            'unit' => 'Unit',
            'leader' => 'Leader',
            'mobile' => 'Mobile',
            'grouptype' => 'Grouptype',
            'groupinfos' => 'Groupinfos',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
