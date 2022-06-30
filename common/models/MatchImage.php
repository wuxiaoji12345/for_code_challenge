<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%match_image}}".
 *
 * @property int $id
 * @property int $matchid
 * @property int $pic_id image唯一码
 * @property string $imageurl 第三方图片地址
 * @property string $imageurl_thumb 小图url
 * @property string $imageurl_origin 原图
 * @property string $imagesize_origin 原图大小
 * @property string $imagesize 大图size
 * @property string $md5 文件md5
 * @property int $status 1,有效；2,删除
 * @property int $create_time
 * @property string $update_time
 */
class MatchImage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%match_image}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['matchid', 'imageurl'], 'required'],
            [['matchid', 'pic_id', 'status', 'create_time'], 'integer'],
            [['update_time'], 'safe'],
            [['imageurl', 'imageurl_thumb', 'imageurl_origin'], 'string', 'max' => 1024],
            [['imagesize_origin', 'imagesize'], 'string', 'max' => 255],
            [['md5'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'matchid' => 'Matchid',
            'pic_id' => 'Pic ID',
            'imageurl' => 'Imageurl',
            'imageurl_thumb' => 'Imageurl Thumb',
            'imageurl_origin' => 'Imageurl Origin',
            'imagesize_origin' => 'Imagesize Origin',
            'imagesize' => 'Imagesize',
            'md5' => 'Md5',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
