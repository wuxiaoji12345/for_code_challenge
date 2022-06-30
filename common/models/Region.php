<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "mcloud_region".
 *
 * @property int $id
 * @property string $name
 * @property int $level
 * @property int $pid
 * @property int $status 1-有效；2-删除
 * @property int|null $adcode
 * @property string|null $center 城市中心点的火星坐标
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%region}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'level', 'pid'], 'required'],
            [['level', 'pid', 'status', 'adcode'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['center'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'level' => 'Level',
            'pid' => 'Pid',
            'status' => 'Status',
            'adcode' => 'Adcode',
            'center' => 'Center',
        ];
    }

    public static function getRegion($parentId=0)
    {
        $result = static::find()->where(['pid'=>$parentId])->asArray()->all();

        return  ArrayHelper::map($result, 'id', 'name');


    }
}
