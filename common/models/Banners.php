<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%banners}}".
 *
 * @property int $id
 * @property int $position 1 首页 2 详情
 * @property string $imgurl 图片地址
 * @property int $jumptype 跳转类型：1，内部跳转；2，外部url跳转
 * @property string $jumpurl 外部跳转url
 * @property string $jumpvalue 内部跳转所带参数，json格式，例如：{["gid":"55"]}
 * @property string $starttime 展示开始时间
 * @property string $endtime 展示结束时间
 * @property int $status 状态：1，有效；2，删除
 * @property int $create_time
 * @property string $update_time
 * @property int $weight
 */
class Banners extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%banners}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['position', 'jumptype', 'status', 'create_time', 'weight'], 'integer'],
            [['starttime', 'endtime', 'update_time'], 'safe'],
            [['imgurl', 'jumpurl'], 'string', 'max' => 1024],
            [['jumpvalue'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'position' => 'Position',
            'imgurl' => 'Imgurl',
            'jumptype' => 'Jumptype',
            'jumpurl' => 'Jumpurl',
            'jumpvalue' => 'Jumpvalue',
            'starttime' => 'Starttime',
            'endtime' => 'Endtime',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'weight' => 'Weight',
        ];
    }
}
