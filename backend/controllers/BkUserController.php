<?php

namespace backend\controllers;

use backend\models\Address;
use backend\service\AddressUserService;
use common\models\AuthAssignment;
use common\models\AuthItem;
use common\models\AuthRole;
use common\models\BkUser;
use common\models\BkUser as McBkUser;
use common\models\CheckInfo;
use common\models\Tools;

//use Http\Client\Common\Exception\ServerErrorException;
//use mcbackendapi\models\McBkAuthAssignment;
//use mcbackendapi\models\McBkAuthItem;
use common\models\UserChannelExtra;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use yii\web\ServerErrorHttpException;

class BkUserController extends BaseController
{
    public $modelClass = 'common\models\BkUser';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'list',
        'metaEnvelope' => 'page'
    ];
    public $except = ['login'];

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
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
        $query = McBkUser::find()->asArray()->from(McBkUser::tableName() . ' as tygy_mc_bk_user')
            ->select([
                'tygy_mc_bk_user.id', 'tygy_mc_bk_user.username', 'tygy_mc_bk_user.realname',
                'tygy_mc_bk_user.created_at', 'tygy_mc_bk_user.updated_at', 'tygy_mc_bk_user.nickname', 'tygy_mc_bk_user.avatar',
                'tygy_mc_bk_user.create_time', 'tygy_mc_bk_user.update_time', 'tygy_mc_bk_user.status',
                new Expression('group_concat(tygy_mc_bk_auth_role.name) as role')
            ])
            ->leftJoin(
                AuthAssignment::tableName() . ' as tygy_mc_bk_auth_assignment',
                'tygy_mc_bk_user.id=tygy_mc_bk_auth_assignment.bkurid and tygy_mc_bk_auth_assignment.status=1 and tygy_mc_bk_auth_assignment.type=1'
            )
            ->leftJoin(
                AuthRole::tableName() . ' as tygy_mc_bk_auth_role',
                'tygy_mc_bk_auth_role.id=tygy_mc_bk_auth_assignment.auth_id and tygy_mc_bk_auth_role.status=1'
            );
//            ->where([
//                'tygy_mc_bk_user.status' => McBkUser::STATUS_ACTIVE,
//                //'tygy_mc_bk_auth_assignment.status' => 1,
//                //'tygy_mc_bk_auth_assignment.type' => 1,
//                //'tygy_mc_bk_auth_role.status' => 1,
//            ]);
        $userModel = $this->getLoginUser();
        if ($userModel) {
            if ($userModel->role) {
                $preg = '/\d+/ius';
                preg_match_all($preg, $userModel->role, $roles);
                if ($roles && isset($roles[0]) && in_array(2, $roles[0])) {

                    //超级管理员
                } else {
                    $query->andWhere(['tygy_mc_bk_user.gid' => $userModel->gid]);
                }
            }
        }
        if ($requestParams['keywords']) {
            try {
                $query->andWhere(
                    ['like', 'tygy_mc_bk_user.username', trim($requestParams['keywords'])]
                );
            } catch (\Exception $e) {
                //
            }
        }
        $query->groupBy(['tygy_mc_bk_user.id'])
            ->orderBy(['tygy_mc_bk_user.id' => SORT_DESC]);

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
        $model = new McBkUser();
        $model->load($params, '');
        if ($model->existUserName($model->username)) {
            throw new ServerErrorHttpException('登录账号已被占用');
        }
        $model->setPassword($model->password);
        $model->generateAuthKey();
        $model->generatePasswordResetToken();
        if (stristr($model->username, '@')) {
            $model->email = $model->username;
        } else {
            $model->email = $model->username . '@example.com';
        }
        $model->created_at = $model->updated_at = time();
        if ($model->save()) {
            return $model;
        } else {
            throw new ServerErrorHttpException(implode(',', $model->getErrorSummary(true)));
        }
    }

    public function actionUpdate($id)
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        // if (!isset($params['id'])) {
        //     throw new ServerErrorHttpException('参数错误');
        // }
        $model = McBkUser::findOne($id);
        if (!isset($model) || ($model->status != McBkUser::STATUS_ACTIVE)) {
            throw new ServerErrorHttpException('用户不存在');
        }
        if (isset($params['nickname'])) {
            $model->nickname = $params['nickname'];
        }
        if (!empty($params['password'])) {
            $model->setPassword($params['password']);
            $model->generateAuthKey();
            $model->generatePasswordResetToken();
        }
        $model->updated_at = time();
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
        $model = McBkUser::findOne($params['id']);
        if (!isset($model) || ($model->status != McBkUser::STATUS_ACTIVE)) {
            throw new ServerErrorHttpException('用户不存在');
        }
        $model->username = $model->username . '_' . $model->id;
        $model->email = $model->username . '@example.com';
        $model->updated_at = time();
        $model->status = McBkUser::STATUS_DELETED;
        if ($model->save()) {
            return $model;
        } else {
            throw new ServerErrorHttpException(implode(',', $model->getErrorSummary(true)));
        }
    }

    /**
     * 作废
     * @return McBkUser|null
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDestory()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        if (!isset($params['id'])) {
            throw new ServerErrorHttpException('参数错误');
        }
        $model = McBkUser::findOne($params['id']);
//        if (!isset($model) || ($model->status != McBkUser::STATUS_ACTIVE)) {
//            throw new ServerErrorHttpException('用户不存在');
//        }
        $model->updated_at = time();
        $state = Tools::getJsonParam('state', 1);
        $model->status = $state;
        if ($model->save()) {
            return $model;
        } else {
            throw new ServerErrorHttpException(implode(',', $model->getErrorSummary(true)));
        }
    }

    public function actionUserRoleList()
    {
        $params = Yii::$app->getRequest()->getQueryParams();
        if (!isset($params['bkurid'])) {
            throw new ServerErrorHttpException('参数错误');
        }
        if (!$this->isAdmin()) {
            $userModel = $this->getLoginUser();
            $roleList = (new AuthRole())->roleUserList($userModel->gid);
        } else {
            $roleList = (new AuthRole())->roleList();
        }
        $userRoleIDs = (new AuthAssignment())->userRoleIDs($params['bkurid']);
        foreach ($roleList as $roleID => $roleVal) {
            $roleList[$roleID]['has'] = (in_array($roleID, $userRoleIDs) ? 1 : 0);
        }
        return array_values($roleList);
    }

    public function actionAssignRole()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        if (!isset($params['bkurid']) || !isset($params['role_id']) || !isset($params['grant'])) {
            throw new ServerErrorHttpException('参数错误');
        }
        $model = BkUser::findOne(['id' => $params['bkurid'], 'status' => Address::NORMAL_STATUS]);
        if (!isset($model) || ($model->status != McBkUser::STATUS_ACTIVE)) {
            throw new ServerErrorHttpException('用户不存在');
        }
        //是否添加角色
        $isGrant = ($params['grant'] == 1);
        $role_model = AuthRole::findOne(['id' => $params['role_id'], 'status' => Address::NORMAL_STATUS]);
        if ($isGrant) {
            $extra_model = UserChannelExtra::findOne(['user_channel_id' => $model->channel_id, 'status' => UserChannelExtra::NORMAL_STATUS]);
            //存入渠道额外信息表，打通以前的流程
            //首先查一下是否检查员
            if (isset($params['checker_id']) && $params['checker_id']) {
                $extra['user_channel_id'] = $model->channel_id;
                $extra['realname'] = $model->nickname;
//                $extra['is_owner'] = $params['address_id'];
                $extra_model = $extra_model ?? new UserChannelExtra();
                $extra_model->load($extra, '');
                $extra_model->is_checker = 1;
                if (!$extra_model->save()) {
                    throw new ServerErrorHttpException(json_encode($extra_model->getErrors()));
                }
                //将channel_id绑定至检查员
                $check_model = CheckInfo::findOne(['id' => $params['checker_id']]);
                $check_model->user_channel_id = $extra['user_channel_id'];
                if (!$check_model->save()) {
                    throw new ServerErrorHttpException(json_encode($check_model->getErrors()));
                }
            }
            if (isset($params['address_id']) && $params['address_id']) {
                $extra['user_channel_id'] = $model->channel_id;
                $extra['realname'] = $model->nickname;
                $extra['is_checker'] = 2;
                $extra['is_owner'] = $params['address_id'];
                $extra_model = $extra_model ?? new UserChannelExtra();
                $extra_model->load($extra, '');
                if (!$extra_model->save()) {
                    throw new ServerErrorHttpException(json_encode($extra_model->getErrors()));
                }
            }
            //还有是否是超级检查员
            if ($role_model->name == UserChannelExtra::SUPER_CHECKER) {
                if (!$extra_model) {
                    throw new ServerErrorHttpException('超级检查员首先要是检查员');
                }
                if ($extra_model->is_owner != 0) {
                    throw new ServerErrorHttpException('超级检查员不能同时是场所负责人');
                }
                $extra_model->is_super_checker = 2;
                if (!$extra_model->save()) {
                    throw new ServerErrorHttpException(json_encode($extra_model->getErrors()));
                }
            }
        } else {
//            $user_model = BkUser::findOne(['id' => $params['bkurid']]);
            if ($role_model->name == UserChannelExtra::CHECKER) {
                UserChannelExtra::updateAll(['is_checker' => 2, 'status' => UserChannelExtra::ABNORMAL_STATUS], ['user_channel_id' => $model->channel_id]);
                //检查员的channel_id在移除时要置为空
                $check_model = CheckInfo::findOne(['user_channel_id' => $model->channel_id]);
                $check_model->user_channel_id = 0;
                if (!$check_model->save()) {
                    throw new ServerErrorHttpException(json_encode($check_model->getErrors()));
                }
            }

            if ($role_model->name == UserChannelExtra::SUPER_CHECKER) {
                UserChannelExtra::updateAll(['is_super_checker' => 1], ['user_channel_id' => $model->channel_id]);
            }

            if ($role_model->name == UserChannelExtra::VENUE_LEADER) {
                UserChannelExtra::updateAll(['is_owner' => 0], ['user_channel_id' => $model->channel_id]);
            }
        }
        (new AuthAssignment())->refreshPrivilege($params['bkurid'], $params['role_id'], $isGrant);
        return $model;
    }

    public function actionLogin()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        try {
            $username = $params['username'];
            $password = $params['password'];
        } catch (\Throwable $th) {
            throw new ServerErrorHttpException("用户名和密码必须填写");
        }
        $model = McBkUser::findByUsername($username);
        if (!isset($model) || !$model->validatePassword($password)) {
            throw new ServerErrorHttpException('账号或密码错误');
        }
        return [
            // 'id' => $model->id,
            'token' => $model->auth_key,
            'realname' => $model->realname,
        ];
    }

    public function actionMenu3()
    {
        $id = Yii::$app->user->getId();
        $roleIDs = (new AuthAssignment())->userRoleIDs($id);
        if (empty($roleIDs)) {
            return [
                'code' => 401,
                'message' => '该账号未分配角色',
            ];
        }
        $roleAuthIDs = (new AuthAssignment())->userRoleAuthIDs($id);
        $data = [];
        $authData = AuthItem::find()->asArray()->where(['id' => $roleAuthIDs, 'status' => 1])->indexBy('id')->all();

        $parentSonIDs = [];
        foreach ($authData as $authID => $arrAuth) {
            //过滤pid非0
            if (!empty($arrAuth['pid'])) {
                if (!isset($authData[$arrAuth['pid']])) {
                    if (!isset($parentSonIDs[$arrAuth['pid']])) {
                        $parentSonIDs[$arrAuth['pid']] = [];
                    }
                    $parentSonIDs[$arrAuth['pid']][] = $authID;
                }
                continue;
            }
            $temp = [
                'id' => $arrAuth['id'],
                'path' => $arrAuth['path'],
                'component' => $arrAuth['component'],
                'redirect' => $arrAuth['redirect'],
                'name' => $arrAuth['name'],
                'label' => $arrAuth['label'],
                'hide' => $arrAuth['hide'],
                'meta' => [
                    'title' => $arrAuth['meta_title'],
                    'icon' => $arrAuth['meta_icon'],
                ],
            ];
            $childrenList = AuthItem::find()->asArray()->where(['pid' => $arrAuth['id'], 'status' => 1])->all();
            if (count($childrenList) > 0) {
                $temp['children'] = [];
                foreach ($childrenList as $child) {
                    $temp['children'][] = [
                        'id' => $child['id'],
                        'path' => $child['path'],
                        'component' => $child['component'],
                        'redirect' => $child['redirect'],
                        'name' => $child['name'],
                        'label' => $child['label'],
                        'hide' => $child['hide'],
                        'meta' => [
                            'title' => $child['meta_title'],
                            'icon' => $child['meta_icon'],
                        ],
                    ];
                }
            }
            $data[] = $temp;
        }
        if (!empty($parentSonIDs)) {
            foreach ($parentSonIDs as $authID => $sonIDs) {
                $modelAuth = AuthItem::findOne(['id' => $authID, 'status' => 1]);
                if (!isset($modelAuth)) {
                    continue;
                }
                $arrAuth = $modelAuth->toArray();
                $temp = [
                    'id' => $arrAuth['id'],
                    'path' => $arrAuth['path'],
                    'component' => $arrAuth['component'],
                    'redirect' => $arrAuth['redirect'],
                    'name' => $arrAuth['name'],
                    'label' => $arrAuth['label'],
                    'hide' => $arrAuth['hide'],
                    'meta' => [
                        'title' => $arrAuth['meta_title'],
                        'icon' => $arrAuth['meta_icon'],
                    ],
                ];
                $childrenList = AuthItem::find()->asArray()->where(['id' => $sonIDs, 'status' => 1])->all();
                if (count($childrenList) > 0) {
                    $temp['children'] = [];
                    foreach ($childrenList as $child) {
                        $temp['children'][] = [
                            'id' => $child['id'],
                            'path' => $child['path'],
                            'component' => $child['component'],
                            'redirect' => $child['redirect'],
                            'name' => $child['name'],
                            'label' => $child['label'],
                            'hide' => $child['hide'],
                            'meta' => [
                                'title' => $child['meta_title'],
                                'icon' => $child['meta_icon'],
                            ],
                        ];
                    }
                }
                $data[] = $temp;
            }
        }
        return $data;
    }

    public function arraySort($array, $keys, $sort = SORT_DESC)
    {
        $keysValue = [];
        foreach ($array as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }

    public function actionTest()
    {
        $searchModel = new AddressSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $dataProvider;

    }

    public function actionMenu()
    {
        $id = Yii::$app->user->getId();
        $params = Yii::$app->getRequest()->getQueryParams();
        if (!isset($params['roleid']) || $this->isAdmin()) {
            $roleAuthIDs = (new AuthAssignment())->userRoleAuthIDs($id);
        } else {
            $roleAuthIDs = (new AuthAssignment())->userRoleAuthID($id, $params['roleid']);
        }
        $menus = [];
        $authData = AuthItem::find()->asArray()->where(['id' => $roleAuthIDs, 'status' => 1])->orderBy('pid asc,weight desc')->all();
        foreach ($authData as $k => $arrAuth) {
            $pid = $arrAuth['pid'];
            if ($pid == 0 && !isset($menus[$pid])) {
                $menus[$arrAuth['id']] = [
                    'id' => $arrAuth['id'],
                    'path' => $arrAuth['path'],
                    'component' => $arrAuth['component'],
                    'redirect' => $arrAuth['redirect'] ? $arrAuth['redirect'] : "",
                    'name' => $arrAuth['name'],
                    'label' => $arrAuth['label'],
                    'hide' => $arrAuth['hide'],
                    'weight' => $arrAuth['weight'],
                    'meta' => [
                        'title' => $arrAuth['meta_title'],
                        'icon' => $arrAuth['meta_icon'],
                    ],
                    'jump_url' => $arrAuth['jump_url'],
                    'children' => []
                ];
            }
            if (isset($menus[$pid])) {
                $a = [
                    'id' => $arrAuth['id'],
                    'path' => $arrAuth['path'],
                    'component' => $arrAuth['component'],
                    'redirect' => $arrAuth['redirect'],
                    'name' => $arrAuth['name'],
                    'label' => $arrAuth['label'],
                    'hide' => $arrAuth['hide'],
                    'jump_url' => $arrAuth['jump_url'],
                    'meta' => [
                        'title' => $arrAuth['meta_title'],
                        'icon' => $arrAuth['meta_icon'],
                    ],
                ];
                array_push($menus[$pid]['children'], $a);
            } else {
                $tt = AuthItem::find()->asArray()->where(['id' => $pid, 'status' => 1])->orderBy('weight desc,id desc')->one();
                if ($tt) {
                    $menus[$pid] = [
                        'id' => $tt['id'],
                        'path' => $tt['path'],
                        'component' => $tt['component'],
                        'redirect' => $tt['redirect'] ? $tt['redirect'] : "",
                        'name' => $tt['name'],
                        'label' => $tt['label'],
                        'hide' => $tt['hide'],
                        'weight' => $tt['weight'],
                        'meta' => [
                            'title' => $tt['meta_title'],
                            'icon' => $tt['meta_icon'],
                        ],
                        'jump_url' => $tt['jump_url'],
                        'children' => [[
                            'id' => $arrAuth['id'],
                            'path' => $arrAuth['path'],
                            'component' => $arrAuth['component'],
                            'redirect' => $arrAuth['redirect'],
                            'name' => $arrAuth['name'],
                            'label' => $arrAuth['label'],
                            'hide' => $arrAuth['hide'],
                            'meta' => [
                                'title' => $arrAuth['meta_title'],
                                'icon' => $arrAuth['meta_icon'],
                            ],
                        ]]
                    ];
                }

            }
        }
        $menus = array_values($menus);
        $menus = $this->arraySort($menus, 'weight', SORT_DESC);
        Tools::dataJsonOut($menus);
    }

    public function actionModifyInfo()
    {
        $params = Yii::$app->getRequest()->getBodyParams();
        $id = Yii::$app->user->getId();
        $model = McBkUser::findOne($id);
        if (!isset($model) || ($model->status != McBkUser::STATUS_ACTIVE)) {
            throw new ServerErrorHttpException('账号不存在');
        }
        if (isset($params['realname'])) {
            $model->realname = $params['realname'];
        }
        if (isset($params['old_password']) && isset($params['new_password']) && !empty($params['old_password'])
            && $model->validatePassword($params['old_password'])) {
            $model->setPassword($params['new_password']);
            $model->generateAuthKey();
            $model->generatePasswordResetToken();
        }
        $model->updated_at = time();
        if ($model->save()) {
            return $model;
        } else {
            throw new ServerErrorHttpException(implode(',', $model->getErrorSummary(true)));
        }
    }

    public function actionUserInfo()
    {
        $id = Yii::$app->user->getId();
        $roleIDs = (new AuthAssignment())->userRoleIDs($id);
        $roleNames = (new AuthRole())->roleNames($roleIDs);
        $model = McBkUser::findOne($id);
        if (!isset($model) || ($model->status != McBkUser::STATUS_ACTIVE)) {
            throw new ServerErrorHttpException('账号不存在');
        }
        $data = [
            'avatar' => '',
            'realname' => $model->realname,
            'role' => $roleNames,
        ];
        return $data;
    }

    /**
     * 分配检查员角色时可绑定的检查员列表
     * @return array|string|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]|null
     */
    public function actionCheckerList()
    {
        $params = \Yii::$app->request->bodyParams;
        return AddressUserService::checkerList($params, false);
    }
}
