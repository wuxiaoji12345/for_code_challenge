<?php

namespace common\models;

use common\models\AuthRoleItem;
use Yii;

/**
 * This is the model class for table "swim_auth_assignment".
 *
 * @property int $id id
 * @property int $bkurid
 * @property int $auth_id
 * @property int $type 1-role；2-auth item
 * @property int $status 1-有效；2-无效
 * @property int $create_time 创建时间
 * @property string|null $update_time 更新时间
 */
class AuthAssignment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'swim_auth_assignment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bkurid', 'auth_id', 'type', 'status', 'create_time'], 'integer'],
            [['create_time'], 'required'],
            [['update_time'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'bkurid' => 'Bkurid',
            'auth_id' => 'Auth ID',
            'type' => '1-role；2-auth item',
            'status' => '1-有效；2-无效',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];
    }

    public function userRoleIDs($bkUrid)
    {
        $data = $this->find()->asArray()
            ->select(['auth_id'])
            ->where([
                'bkurid' => $bkUrid,
                'type' => 1,
                'status' => 1,
            ])
            ->all();
        return array_column($data, 'auth_id');
    }

    public function refreshPrivilege($bkUrid, $roleID, $grant = true)
    {
        $model = $this->findOne(['bkurid' => $bkUrid, 'auth_id' => $roleID, 'type' => 1, 'status' => 1]);
        if ($grant) {
            if (!isset($model)) {
                $model = new self();
                $model->bkurid = $bkUrid;
                $model->auth_id = $roleID;
                $model->type = 1;
                $model->status = 1;
                $model->create_time = time();
                if (!$model->save()) {
                    throw new \Exception('保存失败');
                }
            }
        } else {
            if (isset($model)) {
                $model->status = 2;
                if (!$model->save()) {
                    throw new \Exception('保存失败');
                }
            }
        }
    }

    public function userRoleAuthIDs($bkUrid)
    {
        $data = $this->find()->asArray()->from(AuthAssignment::tableName().' as aa')
            ->leftJoin(AuthRoleItem::tableName().' as ar',
                'aa.auth_id=ar.role_id')
            ->select(['auth_item_id'])
            ->where([
                'bkurid' => $bkUrid,
                'type' => 1,
                'aa.status' => 1,
                'ar.status' => 1,
            ])
            ->all();
        return array_column($data, 'auth_item_id');
    }

    public function userRoleAuthID($bkUrid,$roleid)
    {
        $data = $this->find()->asArray()->from(AuthAssignment::tableName().' as aa')
            ->leftJoin(AuthRoleItem::tableName().' as ar',
                'aa.auth_id=ar.role_id')
            ->select(['role_id','auth_item_id'])
            ->where([
                'bkurid' => $bkUrid,
                'type' => 1,
                'aa.status' => 1,
                'ar.status' => 1,
            ])
            ->all();
        $menu = [];
        foreach($data as $k=>$v){
            if($v['role_id'] == $roleid){
                $menu[] = $v;
            }
        }

        return array_column($menu, 'auth_item_id');
    }
}
