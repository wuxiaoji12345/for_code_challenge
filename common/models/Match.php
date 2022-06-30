<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%match}}".
 *
 * @property int $id
 * @property int $gid 企业ID
 * @property int $userid 后台用户id
 * @property int $category_id 分类id
 * @property string $title
 * @property string $icon 赛事icon
 * @property string $imgurl 赛事图片
 * @property string $intro
 * @property int $reg_start_time 报名开始
 * @property int $reg_end_time 报名结束
 * @property int $start_time 赛事开始时间
 * @property int $end_time 赛事结束时间
 * @property string $province
 * @property string $city
 * @property string $district
 * @property int $province_id 省份id
 * @property int $city_id 市
 * @property int $district_id 区/县id
 * @property string $address 赛事举办地点
 * @property double $longitude 赛事经度
 * @property double $latitude 赛事纬度
 * @property int $matchtype 报名类型：1-单线上；2-单线下：3-线上+线下
 * @property string $tips 须知
 * @property string $disclaimer 免责声明
 * @property int $weight 权重
 * @property int $status 1-有效；2-无效
 * @property resource $qrcode 赛事小程序码
 * @property int $publish 1,发布；2，未发布
 * @property int $create_time
 * @property string $update_time
 */
class Match extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%match}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gid', 'userid', 'category_id', 'reg_start_time', 'reg_end_time', 'start_time', 'end_time', 'province_id', 'city_id', 'district_id', 'matchtype', 'weight', 'status', 'publish', 'create_time'], 'integer'],
            [['title', 'reg_start_time', 'reg_end_time', 'start_time', 'end_time'], 'required'],
            [['intro', 'disclaimer'], 'string'],
            [['longitude', 'latitude'], 'number'],
            [['update_time'], 'safe'],
            [['title', 'icon', 'imgurl', 'address', 'tips', 'qrcode'], 'string', 'max' => 255],
            [['province'], 'string', 'max' => 32],
            [['city'], 'string', 'max' => 64],
            [['district'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gid' => 'Gid',
            'userid' => 'Userid',
            'category_id' => 'Category ID',
            'title' => 'Title',
            'icon' => 'Icon',
            'imgurl' => 'Imgurl',
            'intro' => 'Intro',
            'reg_start_time' => 'Reg Start Time',
            'reg_end_time' => 'Reg End Time',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'province' => 'Province',
            'city' => 'City',
            'district' => 'District',
            'province_id' => 'Province ID',
            'city_id' => 'City ID',
            'district_id' => 'District ID',
            'address' => 'Address',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'matchtype' => 'Matchtype',
            'tips' => 'Tips',
            'disclaimer' => 'Disclaimer',
            'weight' => 'Weight',
            'status' => 'Status',
            'qrcode' => 'Qrcode',
            'publish' => 'Publish',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
