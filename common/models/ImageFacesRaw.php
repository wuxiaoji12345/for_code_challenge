<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%image_faces_raw}}".
 *
 * @property int $id
 * @property int $matchid 赛事id
 * @property string $imgurl 图片url
 * @property string $imgthumburl 图片小图url
 * @property string $imgsize 大图size
 * @property int $face_num 人脸个数
 * @property string $content_body 原始数据
 */
class ImageFacesRaw extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%image_faces_raw}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['matchid'], 'required'],
            [['matchid', 'face_num'], 'integer'],
            [['content_body'], 'string'],
            [['imgurl', 'imgthumburl'], 'string', 'max' => 1024],
            [['imgsize'], 'string', 'max' => 32],
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
            'imgurl' => 'Imgurl',
            'imgthumburl' => 'Imgthumburl',
            'imgsize' => 'Imgsize',
            'face_num' => 'Face Num',
            'content_body' => 'Content Body',
        ];
    }
}
