<?php

namespace backend\controllers;

use common\models\Attrs;
use common\models\Match;
use Yii;
use common\models\RegisterType;
use common\models\search\RegisterTypeSearch;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\bootstrap\Html;
use common\components\Helper;
use common\models\RegisterTypeAttrTmplMap;
use common\models\MatchGroupyAttrTmpl;
use common\models\MatchGroupAttrTmpl;
use common\models\RegisterGroupAttrTmplMap;
use yii\bootstrap\Collapse;

/**
 * RegisterTypeController implements the CRUD actions for RegisterType model.
 */
class SwimEventRegisterTypeController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all RegisterType models.
     * @return mixed
     */
    public function actionIndex()
    {

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $model = $this->findModel($post['editableKey']);
            $post = current($post['RegisterType']);
            $keys = array_keys($post);
            $post = ['RegisterType' => $post];
            if ($model->load($post) && $model->save()) {
                $fkeys = $keys[0];
                $output = $model->$fkeys;
                return json_encode(['output' => $output, 'message' => '']);
            } else {
                return json_encode(['output' => '', 'message' => Helper::getErrormsg($model)]);
            }
        }


        $searchModel = new RegisterTypeSearch();
        $aid = Yii::$app->request->get('aid');

        if (empty($aid)) return $this->redirect(Yii::$app->request->referrer);
        $queryParams = Yii::$app->request->queryParams;
        $queryParams[$searchModel->formName()]['matchid'] = $aid;
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RegisterType model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @param string $id
     * @param string $type
     * @param string $aid
     * @return string|\yii\web\Response
     */
    public function actionConfig($id = '', $type = '', $aid = '')
    {
        $model = RegisterType::findOne($id);
        if (!$model) $model = new RegisterType();
        $matchid = isset($model->matchid) ? $model->matchid : $_GET['aid'];
        if (Yii::$app->request->isPost) {
            if ($type == 2) {
                $sessionname = !$id ? "attrfamily" . $matchid : "attrfamily" . $matchid . $id;
            } else {
                $sessionname = !$id ? 'attrs' : 'attrs' . $id;
            }
            $groupsessionname = !$id ? 'groupattrs' : 'groupattrs' . $id;
            $sessionvalue = Yii::$app->session->get($sessionname);
            $groupsessionvalue = Yii::$app->session->get($groupsessionname);


            $groupsessionvalue = $groupsessionvalue ? $groupsessionvalue[0]['attrs'] : [];
            $sessionvalue = $sessionvalue ? json_encode($sessionvalue) : "";
            $groupsessionvalue = $groupsessionvalue ? json_encode($groupsessionvalue) : "";


            $post = Yii::$app->request->post();
            if ($sessionvalue) $post['RegisterType']['registerform'] = $sessionvalue;
            if ($groupsessionvalue) $post['RegisterType']['groupform'] = $groupsessionvalue;

            if ($model->isNewRecord) {
                $post['RegisterType']['matchid'] = $aid;
                $post['RegisterType']['type'] = $type ? $type : 1;


                if ($model->load($post) && $model->save()) {
                    Yii::$app->session->destroySession($sessionname);
                    Yii::$app->session->destroySession($groupsessionname);
                    return $this->redirect(['index', 'aid' => $matchid]);
                } else {
                    Helper::setFlash($model, 'error');
                }

            } else {

                $post['RegisterType']['type'] = $type ? $type : 1;
                if ($model->load($post) && $model->save()) {
                    Yii::$app->session->destroySession($sessionname);
                    Yii::$app->session->destroySession($groupsessionname);
                    return $this->redirect(['index', 'aid' => $matchid]);

                } else {
                    Helper::setFlash($model, 'error');
                }
            }
        }

        return $this->render('config', ['model' => $model, 'matchid' => $matchid]);
    }


    private function update($post, $model, $get = '')
    {
        $RegisterType = $post['RegisterType'];
        //判断类型
        switch ($RegisterType['type']) {
            case 1:
                $RegisterType['mincount'] = $RegisterType['maxcount'] = $RegisterType['fmaxcount'] = 1;
                break;
            case 2:
                $RegisterType['mincount'] = $RegisterType['maxcount'] = $RegisterType['fmaxcount'] = array_sum($RegisterType['rolecount']);
                break;
            case 3:
                $RegisterType['fmaxcount'] = $RegisterType['fmaxcount'] ? $RegisterType['fmaxcount'] : $RegisterType['maxcount'];
                break;
        }

        if ($model->isNewRecord) $RegisterType['matchid'] = $get['aid'];
        $post['RegisterType'] = $RegisterType;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load($post) && $model->save()) {
                if (!$model->isNewRecord) {
                    //跟新的时候 先删除所有信息
                    RegisterTypeAttrTmplMap::deleteAll(['typeid' => $model->id]);
                    RegisterGroupAttrTmplMap::deleteAll(['typeid' => $model->id]);
                }


                if ($RegisterType['type'] == 3) {
                    //处理团队角色信息
                    $gtmplmap = new RegisterGroupAttrTmplMap();
                    $gtmplmap->typeid = $model->id;
                    $gtmplmap->attr_tmpl_id = $RegisterType['grouptmpl'];
                    $gtmplmap->matchid = $model->matchid;
                    if (!$gtmplmap->save()) {
                        throw  new \Exception(Helper::getErrormsg($gtmplmap));
                    }
                }

                //处理个人角色信息
                $tmpls = array_values(array_filter($RegisterType['roletmpl']));
                $rolecount = array_values(array_filter($RegisterType['rolecount']));
                if (count($tmpls) > 1) {
                    foreach ($tmpls as $key => $v) {
                        if (!$v) continue;
                        $tmplmap = new RegisterTypeAttrTmplMap();
                        $tmplmap->typeid = $model->id;
                        $tmplmap->attr_tmpl_id = $v;
                        $tmplmap->matchid = $model->matchid;
                        // $tmplmap->type          =   $model->type;
                        $tmplmap->amount = $rolecount[$key];
                        if (!$tmplmap->save()) {
                            throw new  \Exception(Helper::getErrormsg($tmplmap));
                        } else {
                            $tmplmap->id = 1 && $tmplmap->isNewRecord = true;
                        }
                    }
                } else {
                    $tmplmap = new RegisterTypeAttrTmplMap();
                    $tmplmap->typeid = $model->id;
                    $tmplmap->attr_tmpl_id = $RegisterType['type'] == 2 ? $tmpls['0'] : $RegisterType['singleroletmpl'];
                    $tmplmap->matchid = $model->matchid;
                    $tmplmap->amount = 1;
                    if (!$tmplmap->save()) {
                        throw  new \Exception(Helper::getErrormsg($tmplmap));
                    }
                }
            } else {
                throw new \Exception(Helper::getErrormsg($model));
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            Helper::setFlashNoObj('error', $e->getMessage());
            $transaction->rollBack();
            return false;
        }
    }


    /**
     * Creates a new RegisterType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {


        $model = new RegisterType();
        $attrsdataProvider = MatchGroupAttrTmpl::find()->andWhere(['gid' => Yii::$app->user->identity->group->id, 'type' => 1])->all();
        $groupdataProvider = MatchGroupAttrTmpl::find()->andWhere(['gid' => Yii::$app->user->identity->group->id, 'type' => 2])->all();

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            $get = Yii::$app->request->get();
            $result = $this->update($post, $model, $get);
            if ($result) {
                if ($model->isinvited == 1) {
                    return $this->redirect(['match-invitecode/index', 'aid' => $model->matchid, 'typeid' => $model->id]);
                }
                return $this->redirect(['index', 'aid' => $_GET['aid']]);
            }
        }
        return $this->render('create', [
            'model' => $model,
            'attrsdataProvider' => $attrsdataProvider,
            'groupdataProvider' => $groupdataProvider,
        ]);

    }

    /**
     * Updates an existing RegisterType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        $attrsdataProvider = MatchGroupAttrTmpl::find()->andWhere(['gid' => Yii::$app->user->identity->group->id, 'type' => 1])->all();
        $groupdataProvider = MatchGroupAttrTmpl::find()->andWhere(['gid' => Yii::$app->user->identity->group->id, 'type' => 2])->all();

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $get = Yii::$app->request->get();
            $result = $this->update($post, $model, $get);

            if ($result) {
                return $this->redirect(['index', 'aid' => $model->matchid]);
            }

        }
        return $this->render('update', [
            'model' => $model,
            'attrsdataProvider' => $attrsdataProvider,
            'groupdataProvider' => $groupdataProvider,
        ]);


    }

    /**
     * Deletes an existing RegisterType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RegisterType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RegisterType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RegisterType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionGetItem()
    {
        $this->layout = false;
        $attrs = new \common\models\Attrs();
        $customtypelist = $attrs->getTypeList();
        $commonattrs = $attrs->getCommonAttrs();
        $baseattrs = Attrs::find()->asArray()->all();


        $label = "新建表单" . Html::tag('span', '', ['class' => 'glyphicon glyphicon-remove pull-right remove-new-attr']);

        return Collapse::widget([
            'id' => uniqid(),
            'items' => [
                // equivalent to the above
                [
                    'encode' => false,

                    'options' => [
                        'id' => uniqid()
                    ],
                    'label' => $label,
                    'content' => $this->render('form', [
                        'baseattrs' => $baseattrs,
                        'commonattrs' => $commonattrs['user'],
                        'item' => [],
                        'formid' => uniqid(),
                        'formname' => '',
                        'amount' => '',
                        'type' => 2,
                        'action' => $_GET['action']
                    ]),
                    // open its content by default
                    'contentOptions' => ['class' => 'in']
                ],
            ]
        ]);


    }


    public function handledAttr($post)
    {

        unset($post['_csrf-backend']);
        $attr = [];
        $attr['attrs'] = [];
        $attr['amount'] = isset($post['amount']) ? $post['amount'] : 1;
        $attr['title'] = isset($post['formname']) ? $post['formname'] : "选手信息";
        $attr['formid'] = isset($post['formid']) ? $post['formid'] : "";

        unset($post['amount']);
        unset($post['formname']);
        unset($post['formid']);

        foreach ($post as $key => $v) {
            $keyarr = explode("-", $key);
            $options = [];
            if (isset($v['childname'])) {
                foreach ($v['childname'] as $k => $vo) {
                    $options[$k]['key'] = $k;
                    $options[$k]['value'] = $vo;
                }
            }
            $vo = [
                'system' => isset($v['system']) ? $v['system'] : '',
                'key_name' => $keyarr[2],
                'show_name' => $v['show_name'],
                'type' => $keyarr[1],
                'rules' => isset($v['rules']) ? $v['rules'] : '',
                'options' => json_encode($options),
                'required' => isset($v['required']) ? 1 : 0,
            ];
            array_push($attr['attrs'], $vo);
        }
        return $attr;

    }

    public function handleItem($attrs)
    {
        $item = [];
        foreach ($attrs['attrs'] as $key => $v) {
            if (!isset($v['system']) || !$v['system']) {
                switch ($v['type']) {
                    case 1:
                    case 2:
                    case 5:
                        $item[]['content'] = $this->getInput($v);
                        break;
                    case 3:
                    case 4:
                    case 6:
                        if ($v['type'] == 3 || $v['type'] == 6) {
                            $presubhtml = Html::radio('', '', ['disabled' => 'disabled']);
                        } else {
                            $presubhtml = Html::checkbox('', '', ['disabled' => 'disabled']);
                        }

                        $presubhtml = Html::tag('span', $presubhtml, ['class' => 'input-group-addon']);


                        $options = json_decode($v['options'], true);
                        $midhtml = "";

                        $removechild = Html::tag('span',
                            Html::button('移除', ['type' => 'button', 'class' => 'btn btn-danger btn-flat remove-child'])
                            , ['class' => 'input-group-btn']);

                        foreach ($options as $k => $vo) {

                            if ($k > 1) {
                                $midhtml .= Html::tag('div', $presubhtml . Html::textInput('c-' . $v['type'] . "-" . $v['key_name'] . "[childname][]", $vo['value'], ['required' => 'required', 'placeholder' => '请输入子选项内容', 'class' => 'form-control']) .
                                    $removechild,
                                    ['class' => 'input-group input-group-sm input-sm-right', 'required' => 'required']);
                            } else {

                                $midhtml .= Html::tag('div', $presubhtml . Html::textInput('c-' . $v['type'] . "-" . $v['key_name'] . "[childname][]", $vo['value'], ['required' => 'required', 'placeholder' => '请输入子选项内容', 'class' => 'form-control']) .
                                    Html::tag('span', '', ['class' => 'input-group-btn']),
                                    ['class' => 'input-group input-group-sm input-sm-right', 'required' => 'required']);
                            }

                        }
                        $sufhtml = Html::tag('div',
                            Html::button('添加', ['type' => 'button', 'data-name' => "c-" . $v['type'] . "-" . $v['key_name'] . "[childname][]", 'class' => 'btn btn-primary btn-sm add-child', 'data-type' => $v['type'] == 3 ? "radio" : "checkbox"]),
                            ['class' => 'text-right margin']);

                        $item[]['content'] = strpos($v['key_name'], 'mv_') === false ? $this->getInput($v) . $midhtml . $sufhtml : $this->getInput($v) . $midhtml;
                        break;
                }
            }


        }
        return $item;
    }

    /**
     * @param $attrs
     * @param $matchid
     * @param $formid
     * @param string $sessionpre
     */
    public function handleFamilySession($attrs, $sessionname, $id)
    {
        $session = Yii::$app->session;


        if ($id) {
            $model = RegisterType::findOne($id);
            $attrfamily = $model->registerform;
            $attrfamily = json_decode($attrfamily, true);
        } else {
            //判断之前是否已经有session 对应的fromid
            $attrfamily = $session->get($sessionname);
        }
        if ($attrfamily) {
            foreach ($attrfamily as $key => $v) {
                if (isset($attrs) && isset($v['formid']) && $v['formid'] == $attrs['formid']) {
                    $attrfamily[$key] = $attrs;
                    unset($attrs);
                }
            }
            if (isset($attrs) && $attrs) {
                array_push($attrfamily, $attrs);
                $attrs = $attrfamily;
            }
        } else {
            $attrfamily = [$attrs];
        }


        //保存session
        $session->set($sessionname, $attrfamily);
        return $attrfamily;

    }

    /**
     * @param $id
     * @param $matchid
     * @param string $formname
     * @return string
     */
    public function actionAttrFamily($id, $matchid, $formname = '')
    {
        $attrs = [];
        $session = Yii::$app->session;
        $sessionname = $id ? "attrfamily" . $matchid . $id : "attrfamily" . $matchid;

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $attrs = $this->handledAttr($post, $sessionname);
            $attrs = $this->handleFamilySession($attrs, $sessionname, $id);
            if ($id) {
                $model = RegisterType::findOne($id);
                $model->registerform = json_encode($attrs);
                $model->save();
                $session->destroySession($sessionname);
            }
        } else {
            //如果没有session 并且有id 从库里面度
            if ($id) {
                $registeType = RegisterType::findOne($id);
                $attrs = $registeType->registerform;
                $attrs = json_decode($attrs, true);
            } else {
                $attrs = $session->get($sessionname);

            }
        }

        if ($attrs) {
            foreach ($attrs as $key => $v) {
                $attrs[$key]['item'] = $this->handleItem($v);
            }
        }

        $attr = new \common\models\Attrs();
        $customtypelist = $attr->getTypeList();
        $commonattrs = $attr->getCommonAttrs();
        $baseattrs = Attrs::find()->asArray()->all();
        $this->layout = 'ajax_page';
        return $this->render('attr-family', [
                'baseattrs' => $baseattrs,
                'commonattrs' => $commonattrs['user'],
                'familyattrs' => $attrs

            ]
        );
    }


    /**
     * 团队和个人的 表单信息
     * @param string $id
     * @param string $type
     * @return string
     */
    public function actionAttr($id = '', $type = 1)
    {
        if ($type == 3) {
            $presessionname = "groupattrs";
        } else {
            $presessionname = "attrs";
        }
        $sessionname = !$id ? $presessionname : $presessionname . $id;

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            $attrs = $this->handledAttr($post);
            $attrs = [$attrs];
            Yii::$app->session->set($sessionname, $attrs);
            //如果是跟新 直接保存保存表单信息
            if ($id) {
                $model = RegisterType::findOne($id);
                if ($type == 3) $model->groupform = json_encode($attrs[0]['attrs']);
                if ($type == 1) $model->registerform = json_encode($attrs);
                Yii::$app->session->destroySession($sessionname);
                $model->save();
            }
        } else {
            $attrs = Yii::$app->session->get($sessionname);
            if ($id) {
                $registeType = RegisterType::findOne($id);
                if ($type == 3) {
                    $attrs = $registeType->groupform;
                    $attrs = json_decode($attrs, true);
                    $attrs = [['attrs' => $attrs]];

                } else {
                    $attrs = $registeType->registerform;
                    $attrs = json_decode($attrs, true);
                }
            }
        }


        $item = [];
        //生成html
        if ($attrs) {
            $attrs = $attrs[0];
            $item = $this->handleItem($attrs);
        }

        $attrs = new \common\models\Attrs();
        $customtypelist = $attrs->getTypeList();
        $commonattrs = $attrs->getCommonAttrs();

        if ($type == RegisterType::TYPESINGLE || $type == 2) {
            $commonattrs = $commonattrs['user'];

        } elseif ($type == RegisterType::TYPEGROUP) {
            $commonattrs = $commonattrs['group'];
        }

        $baseattrs = Attrs::find()->asArray()->all();
        $this->layout = 'ajax_page';
        return $this->render('attr', [
                'type' => $type,
                'baseattrs' => $baseattrs,
                'commonattrs' => $commonattrs,
                'item' => $item,
                'formname' => isset($attrs['title']) ? $attrs['title'] : '',
                'amount' => isset($attrs['amount']) ? $attrs['amount'] : ''
            ]
        );
    }

    /**
     * @param $v
     * @return string
     */
    public function getInput($v)
    {
        $pre_name = "c-" . $v['type'] . "-" . $v['key_name'];
        $checked = $v['required'] ? 'checked' : '';
        $remove =   Html::tag('span', '', ['class' => 'glyphicon glyphicon-trash remove']);
        //批量编辑


        if($v['type']==3||$v['type']==4||$v['type']==6)
        {
            $remove.=Html::a('批量编辑','jacascript::void(0)',['class'=>'margin lotedit','data-name'=>"c-{$v['type']}-".$v['key_name']."[childname][]"]);
        }
        $childcontent = Html::tag("span",
                "必填 " . Html::checkbox($pre_name . "[required]", $checked),
                ['class' => "input-group-addon"]) .
            Html::textInput($pre_name . "[show_name]", $v['show_name'], ['required' => 'required', 'class' => 'form-control']) .
            Html::tag('span',$remove , ['class' => 'input-group-addon']);

        return Html::tag("div", $childcontent, ['class' => 'input-group']);

    }


    /**
     * @param $formid
     * @param $matchid
     * @param string $typeid
     * @return \yii\web\Response
     */
    public function actionRemoveItem($formid, $matchid, $typeid = '')
    {
        $session = Yii::$app->session;
        $sessionname = $typeid ? "attrfamily" . $matchid . $typeid : "attrfamily" . $matchid;
        //如果有typeid 直接跟新
        if ($typeid) {
            $model = RegisterType::findOne($typeid);
            $familyattrs = $model->registerform;
            $familyattrs = json_decode($familyattrs, true);

            foreach ($familyattrs as $key => $v) {
                if ($v['formid'] == $formid) {
                    unset($familyattrs[$key]);
                }
            }
            $model->registerform = json_encode(array_values($familyattrs));
            $model->save();
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            $familyattrs = $session->get($sessionname);
            foreach ($familyattrs as $key => $v) {
                if ($v['formid'] == $formid) {
                    unset($familyattrs[$key]);
                }
            }
            $session->set($sessionname, $familyattrs);
            return $this->redirect(Yii::$app->request->referrer);

        }

    }


    public function actionGetregistertype($matchid)
    {

        Yii::$app->response->format = 'json';

        return RegisterType::find()->select(['id', 'title'])->andWhere(['matchid' => $matchid])->asArray()->all();

    }

    public function actionImport($matchid)
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

//            $typeid = array_filter($post['typeid'], function ($v, $k) {
//                return $v == 'on';
//            }, ARRAY_FILTER_USE_BOTH);
//            $typeid = array_keys($typeid);
            $typeid =   [];
            if(!empty($post['typeid']))
            {
                foreach ($post['typeid'] as $key=>$v)
                {
                    if($v=='on')
                    {
                        array_push($typeid,$key);
                    }
                }
            }
            if(!$typeid)
            {
                return $this->redirect(Yii::$app->request->referrer);
            }
            $reall = RegisterType::find()->asArray()->andWhere(['in', 'id', $typeid])->all();


            try {
                if ($reall) {
                    foreach ($reall as $obj) {

                        $type = new RegisterType();

                        $type->setAttributes($obj);

//                    $type->title = $obj['title'].uniqid();
                        $type->matchid = $matchid;
                        $type->create_time = time();
//                        $type->update_time = time();
                        $type->save() && $type->isNewRecord = true && $type->id = 1;

                        if($type->getErrors())
                        {
                            throw  new \Exception(Helper::getErrormsg($type));
                        }

                    }

                }
            }catch (\Exception $e)
            {
                Helper::setFlashNoObj('error',$e->getMessage());
            }
            return $this->redirect(Yii::$app->request->referrer);


        }
    }


}
