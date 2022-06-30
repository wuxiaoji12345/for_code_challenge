<?php

namespace backend\controllers;

use common\models\AuthAssignment;
use common\models\AuthItem;
use common\models\AuthRole;
use common\models\AuthRoleItem;
use common\models\Tools;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use yii\web\ServerErrorHttpException;

class AuthRoleItemController extends BaseController
{
    public $modelClass = 'common\models\AuthRoleItem';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'list',
        'metaEnvelope' => 'page'
    ];

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'authenticatior' => [
                    'tokenParam' => 'token',
                    'class' => QueryParamAuth::className(),
                ]
            ],
            [
                'verbFilter' => ['actions' => ['update' => ['POST']]],
            ],
            [
                'verbFilter' => ['actions' => ['delete' => ['POST']]],
            ]
        );
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        return $actions;
    }

    public function actionIndex()
    {
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }
        $query = AuthRoleItem::find()->asArray()
            ->select(['tygy_mc_bk_auth_role_item.id', 'tygy_mc_bk_auth_role.name', 'tygy_mc_bk_auth_item.label',
                'tygy_mc_bk_auth_role_item.create_time', 'tygy_mc_bk_auth_role_item.update_time'])
            ->leftJoin(AuthRole::tableName(), 'tygy_mc_bk_auth_role_item.role_id=tygy_mc_bk_auth_role.id')
            ->leftJoin(AuthItem::tableName(), 'tygy_mc_bk_auth_role_item.auth_item_id=tygy_mc_bk_auth_item.id')
            ->where([
                'tygy_mc_bk_auth_role_item.status' => 1
            ]);

        $query->orderBy(['tygy_mc_bk_auth_role_item.id' => SORT_DESC]);

        if (isset($requestParams['name']) && !empty($requestParams['name'])) {
            $query->andWhere([
                'like', 'tygy_mc_bk_auth_role.name', $requestParams['name'],
            ]);
        }

        return Yii::createObject([
            'class' => ActiveDataProvider::className(),
            'query' => $query,
            'pagination' => [
                'params' => $requestParams,
            ],
            'sort' => [
                'params' => $requestParams,
            ],
        ]);
    }

    public function actionCreate()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        $model = new AuthRoleItem();
        $model->load($params, '');
        $model->create_time = time();
        if ($model->save()) {
            return $model;
        } else {
            throw new ServerErrorHttpException(implode(',', $model->getErrorSummary(true)));

        }
    }

    public function actionUpdate()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        if (!isset($params['id'])) {
            throw new ServerErrorHttpException('参数错误');
        }
        $model = AuthRoleItem::findOne($params['id']);
        if (!isset($model) || ($model->status == 2)) {
            throw new ServerErrorHttpException('角色权限不存在');
        }
        $model->load($params, '');
        if ($model->save()) {
            return $model;
        } else {
            throw new ServerErrorHttpException(implode(',', $model->getErrorSummary(true)));
        }
    }

    public function actionDelete()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        if (!isset($params['id'])) {
            throw new ServerErrorHttpException('参数错误');
        }
        $model = AuthRoleItem::findOne($params['id']);
        if (!isset($model) || ($model->status == 2)) {
            throw new ServerErrorHttpException('角色权限不存在');
        }
        $model->status = 2;
        if ($model->save()) {
            return $model;
        } else {
            throw new ServerErrorHttpException(implode(',', $model->getErrorSummary(true)));
        }
    }

    public function actionRoleAuthItemList()
    {
        $params = Yii::$app->getRequest()->getQueryParams();
        if (!isset($params['role_id'])) {
            throw new ServerErrorHttpException('参数错误');
        }

        $data = [];
        $roleAuthItemIDs = (new AuthRoleItem())->getRoleAuthItemIDs($params['role_id']);
        if($this->isAdmin()){
            $authPidData = (new AuthItem())->pidList(0);
            foreach ($authPidData as $pid => $pidVal) {
                $existPid = (in_array($pid, $roleAuthItemIDs) ? 1 : 0);
                $temp = [
                    'id' => $pid,
                    'label' => $pidVal,
                    'has' => $existPid,
                    'children' => [],
                ];
                $children = (new AuthItem())->pidList($pid);
                foreach ($children as $childID => $childName) {
                    $temp['children'][] = [
                        'id' => $childID,
                        'label' => $childName,
                        'has' => (($existPid || in_array($childID, $roleAuthItemIDs)) ? 1 : 0),
                    ];
                }
                $data[] = $temp;
            }
        }else{
            $id = Yii::$app->user->getId();
            if(!isset($params['menu_id'])){
                $roleAuthIDs = (new AuthAssignment())->userRoleAuthIDs($id);
            }else{
                $roleAuthIDs = (new AuthAssignment())->userRoleAuthID($id,$params['menu_id']);
            }

            $authData = AuthItem::find()->asArray()->where(['id' => $roleAuthIDs, 'status' => 1])->all();
            foreach($authData as $k=>$arrAuth)
            {
                $pid = $arrAuth['pid'];
                $existPid = (in_array($pid, $roleAuthItemIDs) ? 1 : 0);
                if($pid == 0 && !isset($data[$pid]))
                {
                    $data[$arrAuth['id']]  = [
                        'id' => $arrAuth['id'],
                        'label' => $arrAuth['label'],
                        'has' => $existPid,
                        'children' => []
                    ];
                }
                if(isset($data[$pid]))
                {
                    $a = [
                        'id' => $arrAuth['id'],
                        'label' => $arrAuth['label'],
                        'has' => (($existPid || in_array($arrAuth['id'], $roleAuthItemIDs)) ? 1 : 0),
                    ];
                    array_push($data[$pid]['children'],$a);
                }else{
                    $tt = AuthItem::find()->asArray()->where(['id' =>$pid, 'status' => 1])->one();
                    if($tt)
                    {
                        $data[$pid]  = [
                            'id' => $tt['id'],
                            'label' => $tt['label'],
                            'has' => $existPid,
                            'children' => [[
                                'id' => $arrAuth['id'],
                                'label' => $arrAuth['label'],
                                'has' => (($existPid || in_array($arrAuth['id'], $roleAuthItemIDs)) ? 1 : 0),
                            ]]
                        ];
                    }

                }
            }
            $data = array_values($data);
        }
        return $data;
    }


    public function actionRoleAuthItemAction()
    {
        $role_id = Tools::getJsonParamErr('role_id');
        $result = [];
//定义索引数组，用于记录节点在目标数组的位置
        $I = [];

        $authPidData = AuthItem::find()->from(AuthRoleItem::tableName().' as ari')->leftJoin(AuthItem::tableName().' as ai','ari.auth_item_id=ai.id')
            ->select('ai.id,ai.label,ai.pid,ai.actions,ari.actions as hasactions,ari.id as page_id')->where(['ari.status'=>1,'ai.status'=>1,'role_id'=>$role_id])
            ->andWhere(['!=','ai.actions',""])
            ->orderBy('pid asc')->asArray()->all();
        foreach($authPidData as $val)
        {
            if($val['actions'])
            {
                $val['actions'] = $this->formatActions($val['actions']);
            }
            if($val['hasactions'])
            {
                $val['hasactions'] = json_decode($val['hasactions'],true);
            }else{
                $val['hasactions'] = [];
            }
            $result [] = $val;
//            if($val['pid'] == 0) {
//                $i = count($result);
//                $result[$i] = $val;
//                $I[$val['id']] = & $result[$i];
//            } else {
//                if(!isset($I[$val['pid']]['children']))
//                    $I[$val['pid']]['children'] = [];
//                $i = count($I[$val['pid']]['children']);
//                $I[$val['pid']]['children'][$i] = $val;
//                $I[$val['id']] = & $I[$val['pid']]['children'][$i];
//            }
        }
        return $result;
    }

    /**
     * 更新权限
     * @return array
     */
    public function actionRoleAuthItemUpdate()
    {
        $role_id = Tools::getJsonParamErr('role_id');
        $data = Tools::getJsonParamErr('data');
        $data = json_decode($data,true);
        foreach($data as $k=>$v)
        {
            $rid = $v['page_id'];
            $model = AuthRoleItem::findOne($rid);
            if($model && $model->role_id == $role_id)
            {
                if(is_array($v['hasactions']) && $v['hasactions'])
                {
                    $model->updateAttributes(['actions'=>json_encode($v['hasactions'])]);
                }else{
                    $model->updateAttributes(['actions'=>null]);
                }

            }
        }
        return ['msg'=>'OK'];
    }
    public function formatActions($val)
    {
        $actions = [];
        if($val)
        {
            $out = explode(";",$val);
            foreach($out as $k=>$v)
            {
                if($v)
                {
                    $vv = explode(":",$v);
                    $actions[] = ['name'=>$vv[0],'role'=>$vv[1]];
                }
            }
        }
        return $actions;
    }

    public function actionBatchUpdate()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        if (!isset($params['role_id']) || !isset($params['item_id']) || !is_array($params['item_id'])) {
            throw new ServerErrorHttpException('参数错误');
        }

        try {
            (new AuthRoleItem())->refreshRoleItems($params['role_id'], $params['item_id']);
            return [];
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('更新失败');
        }
    }
}
