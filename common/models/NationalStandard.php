<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%national_standard}}".
 *
 * @property int $id
 * @property string $info 详情
 * @property int $is_qualified 是否合格 1合格 2不合格
 * @property string|null $update_time
 */
class NationalStandard extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%national_standard}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_qualified'], 'integer'],
            [['update_time'], 'safe'],
            [['info'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'info' => '详情',
            'is_qualified' => '是否合格 1合格 2不合格',
            'update_time' => 'Update Time',
        ];
    }
}
