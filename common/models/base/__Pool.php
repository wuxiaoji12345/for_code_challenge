<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "swim_pool".
 *
 * @property int $id
 * @property int $sid 场馆id
 * @property string $name 池子名称
 * @property int $weight 排序权重
 * @property int $status 1-有效；2-无效
 * @property string $update_time
 */
class __Pool extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_pool';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sid', 'weight', 'status'], 'integer'],
            [['update_time'], 'safe'],
            [['name'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sid' => 'Sid',
            'name' => 'Name',
            'weight' => 'Weight',
            'status' => 'Status',
            'update_time' => 'Update Time',
        ];
    }
}
