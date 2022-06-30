<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "swim_auth_item".
 *
 * @property int $id id
 * @property int $pid
 * @property string $path
 * @property string $name
 * @property string $label
 * @property string $component
 * @property string|null $redirect
 * @property int $hide 0-显示 1-隐藏
 * @property string $meta_title
 * @property string $meta_icon
 * @property int $status 1-有效；2-无效
 * @property int $create_time 创建时间
 * @property string|null $update_time 更新时间
 * @property string|null $actions 页面功能按钮
 * @property int|null $weight 权重
 * @property string|null $jump_url 第三方跳转
 */
class AuthItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_auth_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'hide', 'status', 'create_time', 'weight'], 'integer'],
            [['create_time'], 'required'],
            [['update_time'], 'safe'],
            [['actions'], 'string'],
            [['path', 'name', 'label', 'component', 'redirect', 'meta_title', 'meta_icon'], 'string', 'max' => 64],
            [['jump_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'pid' => 'Pid',
            'path' => 'Path',
            'name' => 'Name',
            'label' => 'Label',
            'component' => 'Component',
            'redirect' => 'Redirect',
            'hide' => '0-显示 1-隐藏',
            'meta_title' => 'Meta Title',
            'meta_icon' => 'Meta Icon',
            'status' => '1-有效；2-无效',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'actions' => '页面功能按钮',
            'weight' => '权重',
            'jump_url' => '第三方跳转',
        ];
    }

    public function pidList($pid = 0)
    {
        $data = $this->find()->asArray()
            ->select(['id', 'label','actions'])
            ->where([
                'pid' => $pid,
                'status' => 1,
            ])
            ->all();
        return array_column($data, 'label', 'id');
    }
}
