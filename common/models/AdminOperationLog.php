<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "admin_operation_log".
 *
 * @property int $id
 * @property int $user_id
 * @property string $path
 * @property string $method
 * @property string $ip
 * @property string $input
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class AdminOperationLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_operation_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'path', 'method', 'ip', 'input'], 'required'],
            [['user_id'], 'integer'],
            [['input'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['path', 'ip'], 'string', 'max' => 255],
            [['method'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'path' => 'Path',
            'method' => 'Method',
            'ip' => 'Ip',
            'input' => 'Input',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
