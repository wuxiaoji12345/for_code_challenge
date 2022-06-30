<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%match_image_config}}".
 *
 * @property int $id
 * @property int $matchid
 * @property int $sourcetype 0:vphoto; 1:美拍拍; 2:谱时
 * @property string $sourceurl 第三方图片地址
 * @property string $sourceid 照片来源id
 * @property string $sharetitle 分享标题
 * @property string $sharedesc 分享描述
 * @property string $sharelink 分享链接
 * @property string $shareimg 分享图片
 * @property int $create_time
 * @property string $update_time
 * @property string $values
 * @property int $total 照片数量
 * @property int $views page view
 * @property int $realviews
 * @property int $view_magic
 * @property string $watermark paipai水印
 * @property int $start_time 开始时间
 * @property int $end_time 结束时间
 * @property string $title 照片标题
 */
class MatchImageConfig extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%match_image_config}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['matchid', 'sourceurl'], 'required'],
            [['matchid', 'sourcetype', 'create_time', 'total', 'views', 'realviews', 'view_magic', 'start_time', 'end_time'], 'integer'],
            [['update_time'], 'safe'],
            [['values', 'watermark'], 'string'],
            [['sourceurl', 'sourceid', 'sharetitle', 'sharedesc', 'sharelink', 'shareimg', 'title'], 'string', 'max' => 255],
            [['matchid'], 'unique'],
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
            'sourcetype' => 'Sourcetype',
            'sourceurl' => 'Sourceurl',
            'sourceid' => 'Sourceid',
            'sharetitle' => 'Sharetitle',
            'sharedesc' => 'Sharedesc',
            'sharelink' => 'Sharelink',
            'shareimg' => 'Shareimg',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'values' => 'Values',
            'total' => 'Total',
            'views' => 'Views',
            'realviews' => 'Realviews',
            'view_magic' => 'View Magic',
            'watermark' => 'Watermark',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'title' => 'Title',
        ];
    }
}
