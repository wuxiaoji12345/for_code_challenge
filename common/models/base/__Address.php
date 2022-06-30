<?php

namespace common\models\base;

use common\models\BaseModel;
use Yii;

/**
 * This is the model class for table "swim_address".
 *
 * @property int $id
 * @property string|null $name 场馆名称
 * @property string|null $imgurl 场馆图片
 * @property string|null $province
 * @property string|null $city
 * @property string|null $district
 * @property string|null $address 某场比赛地点
 * @property float|null $longitude 赛事经度
 * @property float|null $latitude 赛事纬度
 * @property int|null $lane
 * @property int|null $comment_num
 * @property int|null $comment_sum_score
 * @property int|null $publish 是否发布
 * @property int|null $status 1-有效；2-无效
 * @property int|null $create_time
 * @property string|null $update_time
 */
class __Address extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['longitude', 'latitude'], 'number'],
            [['lane', 'comment_num', 'comment_sum_score', 'publish', 'status', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
            [['name', 'imgurl', 'address'], 'string', 'max' => 255],
            [['province', 'city', 'district'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '场馆名称',
            'imgurl' => '场馆图片',
            'province' => '省份',
            'city' => '城市',
            'district' => '区',
            'address' => '地址',
            'longitude' => '经度',
            'latitude' => '纬度',
            'lane' => '泳道数',
            'comment_num' => '评论数',
            'comment_sum_score' => '评论分数',
            'publish' => '发布',
            'status' => '状态',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];
    }
}
