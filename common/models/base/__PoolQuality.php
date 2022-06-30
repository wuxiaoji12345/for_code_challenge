<?php

namespace common\models\base;

use Yii;

/**
 * This is the model class for table "swim_pool_quality".
 *
 * @property int $id
 * @property string $poid
 * @property string $checkname 检查员
 * @property string $cdate 记录日期
 * @property double $value 检测数值
 * @property int $type 水质类型：1，温度；2，ph；3，orp；4，余氯; 5, 浑浊度NTU
 * @property int $create_time
 * @property string $update_time
 */
class __PoolQuality extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_pool_quality';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['poid'], 'required'],
            [['cdate', 'update_time'], 'safe'],
            [['value'], 'number'],
            [['type', 'create_time'], 'integer'],
            [['poid'], 'string', 'max' => 16],
            [['checkname'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'poid' => 'Poid',
            'checkname' => 'Checkname',
            'cdate' => 'Cdate',
            'value' => 'Value',
            'type' => 'Type',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
