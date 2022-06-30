<?php

namespace backend\controllers;
use backend\models\Search\EventSearch;
use common\models\Event;
use yii\web\Controller;
use Yii;
use yii\filters\VerbFilter;

class SwimEventController extends Controller
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

    public function actionUrl()
    {
        $this->layout = "ajax_page";
        return $this->render('url');
    }

    public function actionVote()
    {
        $this->layout = "ajax_page";
        return $this->render('vote');
    }

    


    /**
     * Lists all Match models.
     * @return mixed
     */
    public function actionIndex($tag = "li", $dropdown = '', $template = '')
    {
        
        $searchModel = new EventSearch();
        $queryParams = Yii::$app->request->queryParams;

        if (Yii::$app->user->id != 1)
            $queryParams[$searchModel->formName()]['gid'] = Yii::$app->user->identity->group->id;

        $dataProvider = $searchModel->search($queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dropdown' => $dropdown,
            'template' => $template,
            'tag' => $tag
        ]);
    }


    public function actionMatchMaterial()
    {
        return Yii::$app->runAction('/match/index', ['tag' => 'span', 'dropdown' => 2, 'template' => "{material} {finalaccounts}"]);
    }


    /**
     * Displays a single Match model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $partial = false)
    {
        if ($partial) {
            $this->layout = 'ajax_page';
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Match model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Event();

        $model->setScenario('create');
        if (Yii::$app->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $post = Yii::$app->request->post();
                if (!$model->load($post) || !$model->save()) {
                    throw new \Exception(Helper::getErrormsg($model));
                } else {


                    MatchSchedule::updateAll(['status' => MatchSchedule::LIST_B], ['id' => $model->msid]);
                    McloudMatch::rsyData($model);
                    $transaction->commit();
                    return $this->redirect(['index']);
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Helper::setFlashNoObj('error', $e->getMessage());
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }


        return $this->render('create', [
            'model' => $model,
        ]);

    }

    /**
     * Updates an existing Match model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('update');
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if (!$model->load($post) || !$model->save()) {
                Helper::setFlash($model, 'error');
            } else {
                McloudMatch::rsyData($model);
                unset($_GET['id']);
                return $this->redirect(['index'] + $_GET);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Match model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        McloudMatch::rsyData($this->findModel($id), 9);
        return $this->redirect(['index']);
    }

    public function actionUpdatedetil($id)
    {

        $model = $this->findModel($id);
        $this->layout = false;
        $model->setScenario('update');
        if (Yii::$app->request->isPost) {
            if (Yii::$app->request->post('content')) {
                $model->intro = Yii::$app->request->post('content');
                if ($model->save()) {

                } else {
                    Helper::setFlash($model, 'error');
                }
                McloudMatch::rsyData($this->findModel($id));
                return json_encode(['result' => $model->save(), 'href' => 'match/update?id=' . $id]);
            }
        }
        return $this->render('updatedetail', [
            'model' => $model,
        ]);

    }


    /**
     * Finds the Match model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Match the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Event::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSendSms($mid)
    {

        $match = Event::findOne($mid);
        // $sql    =   "select mobile from mpms_register_user where id in (select userid from mpms_register_relation where matchid  = {$mid} and state=1 ) UNION ALL
        $sql = "select a.mobile from mpms_register_info a left join mpms_register_relation b on  a.rrid=b.id  where  b.state=1  and a.matchid= {$mid} and b.matchid= {$mid} and a.state=1  ";
        $result = Yii::$app->db->createCommand($sql)->queryAll();
        $mobiles = '';
        $content = "【%s组委会】尊敬的参赛选手，感谢您报名%s，请携带本人身份证件领取赛事装备。领取时间：%s；领取地点：%s，您可乘坐%s。感谢您的支持与配合!";
        $time = "";
        $address = "";
        $traffic = "";
        $smscontent = sprintf($content, $match->category->title, $match->title, $time, $address, $traffic);
        if ($result) {
            $newresult = [];
            foreach ($result as $key => $v) {
                //手机正则

                //^1[0-9]{10}$
                if (preg_match("/^1[0-9]{10}$/", $v['mobile'])) {
                    array_push($newresult, $v['mobile']);
                }
            }
            $newresult = array_unique($newresult);
            $newresult = array_filter($newresult);
            $mobiles = implode(',', $newresult);
        }
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $sms = new SMSUtils();

            //检查签名长度
            preg_match('/(?:\【)(.*)(?:\】)/i', $post['content'], $s);

//
//            if(empty($s))
//            {
//                Helper::setFlashNoObj('error', '发送失败,签名不能为空');
//                return $this->redirect(['/match/send-sms', 'mid' => $mid]);
//            }
//
//            if (isset($s)&&mb_strlen($s[1], 'utf-8') > 8) {
//                Helper::setFlashNoObj('error', '发送失败,签名【' . $s[1] . "】有误,请确保签名长度为2-8个字符<br>" . $post['content']);
//                return $this->redirect(['/match/send-sms', 'mid' => $mid]);
//            }


//            print_r($post['mobiles']);exit;


            //如果长度大于500 分批发送
            $mobilearr = explode(',', $post['mobiles']);

            if (count($mobilearr) > 500) {
                $newmobilearr = array_chunk($mobilearr, 500);


                foreach ($newmobilearr as $v) {
                    $sms->SendNew(implode(',', $v), $post['content']);
                }
                Helper::setFlashNoObj('success', '手机号超过500个,短信已分批发送');

            } else {
                $res = $sms->SendNew($post['mobiles'], $post['content']);
                $res = json_decode($res);

                if ($res->code == 'SUCCESS') {
                    Helper::setFlashNoObj('success', '短信已发送');
                } else {
                    Helper::setFlashNoObj('error', '短信发送失败');

                }
            }
            return $this->redirect(['/match/send-sms', 'mid' => $mid]);
        }
        return $this->render('send-sms', [
            'mobiles' => $mobiles,
            'content' => $smscontent
        ]);
    }


    public function actionReport()
    {
        $sms = new SMSUtils();


        $data = [
            'number' => 100,
            'timestamp' => time()
        ];

        $result = $sms->getReport($data);


        var_dump($result);
        exit;

    }

    public function actionMoveMember()
    {

        //第一步 取出来来数据
        $oldmembers = RegisterOldMembers::find()
            ->joinWith('registerGroup')
            ->all();


        //第二步 判断当前队长是否已经注册新系统
        if ($oldmembers) {


            $db = Yii::$app->db;
            $transaction = Yii::$app->db->beginTransaction();

            try {

                foreach ($oldmembers as $obj) {

                    if (!$obj->registerGroup) continue;
                    $where['mobile'] = $obj->registerGroup->mobile;
                    $user = RegisterUser::findOne($where);
                    if (!$user) {
                        //注册用户
                        $user = new RegisterUser();
                        $user->name = $obj->registerGroup->name;
                        $user->mobile = $obj->registerGroup->mobile;
                        $user->email = $obj->registerGroup->email;
                        $user->password = $obj->registerGroup->password;
                        $user->passwordsalt = $obj->registerGroup->passwordsalt;
                        $user->save();
                    }
                    //注册Member 以及 member 信息
                    $newmember = new RegisterMembers();
                    $newmember->userid = $user->id;
                    $newmember->rgid = $user->id;;
                    $newmember->save();
                    $params = [
                        'key_name',
                        'show_name',
                        'value',
                        'memberid',
                        'rules'
                    ];
                    $value = [
                        [Attrs::MVNAME, '姓名', $obj->name, $newmember->id, '/*/'],
                        [Attrs::IDTYPE, '证件类型', $obj->idtype == 1 ? "身份证" : "护照", $newmember->id, '/*/'],
                        [Attrs::IDNUMBER, '证件号码', $obj->idnumber, $newmember->id, '/*/'],
                        [Attrs::MOBILE, '手机号码', $obj->mobile, $newmember->id, '/*/'],
                        [Attrs::MVSEX, '性别', $obj->sex == 1 ? "男" : "女", $newmember->id, '/*/'],
                        [Attrs::MVAVATAR, '近期免冠照', $obj->avatar, $newmember->id, '/*/'],
                    ];


                    $db->createCommand()->batchInsert(RegisterMemberAttrValue::tableName(), $params, $value)->execute();//执行批量添加

                }
                $transaction->commit();
                echo "ol";

            } catch (\yii\db\Exception $e) {

                $transaction->rollBack();

                print_r($e->getMessage());
                exit;

            }


        }
    }

    public $regname = ['mv_groupname'];
    public $leader = ['custom_59e6bb0826880', 'custom_59e6be3182e81', 'custom_5a090f6614511', 'custom_5a5d7413764b7', 'custom_5a784181a1bc7'];
    public $gmobile = ['custom_59e6bb2be4c5e', 'custom_59e6be445016c', 'custom_5a090f6e1ceaa', 'custom_5a5d741ca8f8b', 'custom_5a78418ed46a2'];
    public $unit = ['custom_59afa3862096b', 'custom_59b755d15f065', 'custom_59e6bf07f004c', 'custom_5a094f966d27d', 'custom_5a1cf410b2253', '企业名称'];

    public $size = ['custom_59b758f32a696', 'custom_59afa2607cfb2', 'custom_59b759c172c4c', 'custom_5a6055d2eb68c', 'custom_59b758f32a696',
        'custom_59f7f03694932', 'custom_59f7f147053a5', 'custom_5a2782e80af29', 'custom_5a2787694aaf5', 'custom_5a2787f3810e6', 'custom_5a2a0941cd77a', 'custom_5a2a09d8d1bd0'];
    public $birth = ['custom_5a4f4fb1e619a'];


    public function keyname($ccobj)
    {
        if (in_array($ccobj->key_name, $this->regname)) $ccobj->key_name = 'mv_regname';
        if (in_array($ccobj->key_name, $this->leader)) $ccobj->key_name = 'mv_leader';
        if (in_array($ccobj->key_name, $this->gmobile)) $ccobj->key_name = 'mv_leader_mobile';
        if (in_array($ccobj->key_name, $this->unit)) $ccobj->key_name = 'mv_unit';
        if (in_array($ccobj->key_name, $this->size)) $ccobj->key_name = 'mv_size';
        if (in_array($ccobj->key_name, $this->birth)) $ccobj->key_name = 'mv_birth';

    }


    //35,36,37,38,39,40
    public $matchids = [6, 7, 8, 16, 26, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40];
//    public $matchids   =   [1,2,31,38,39,40,41,42,56,57,58,59,60,61,62,64,65];
//    public $matchids   =   [38];

    /**
     * 处理 register type
     */
    public function actionHandleData()
    {

        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {


            $attrs = new \common\models\Attrs();
            $commonattrs = $attrs->getCommonAttrs();

            $sysuserattr = [];
            $sysgroupattr = [];


            foreach ($commonattrs['user'] as $key => $v) {
                array_push($sysuserattr, $v['key_name']);
            }
            foreach ($commonattrs['group'] as $key => $v) {
                array_push($sysgroupattr, $v['key_name']);
            }


            $registerform = [];
            $groupform = [];
            $attr = [];
            $gattr = [];

            foreach ($this->matchids as $aid) {
                $registerType = RegisterType::findAll(['matchid' => $aid]);
                if (!$registerType) continue;
                foreach ($registerType as $obj) {
                    //个人表单
                    //团队表单
                    $registerform[$obj->id] = [];
                    $groupform[$obj->id] = [];

                    if ($obj->type == 3) {
                        foreach ($obj->groupTmpl as $cobj) {
//                            $gattr['amount']  =   1;
//                            $gattr['title']   =   '';
//                            $gattr['attrs']   =   [];
                            $attrs = [];


                            foreach ($cobj->attrTmpl->attrs as $key => $ccobj) {
                                $this->keyname($ccobj);

                                $attrs['system'] = in_array($ccobj->key_name, $sysgroupattr) ? 1 : 0;
                                $attrs['key_name'] = $ccobj->key_name;
                                $attrs['show_name'] = $ccobj->show_name;
                                $attrs['type'] = $ccobj->type;
                                $attrs['options'] = $ccobj->options;
                                $attrs['required'] = $ccobj->required;
                                $attrs['rules'] = $ccobj->rules;

//                                array_push($gattr['attrs'],$attrs);
                                array_push($groupform[$obj->id], $attrs);

                            }
//                            array_push($groupform[$obj->id],$attrs);
                        }

                    }


                    foreach ($obj->tmpl as $cobj) {
                        $attr['amount'] = $cobj->amount;
                        $attr['title'] = $obj->title;
                        $attr['attrs'] = [];
                        $attr['formid'] = uniqid();
                        $attrs = [];
                        if (!is_object($cobj->attrTmpl)) continue;
                        foreach ($cobj->attrTmpl->attrs as $key => $ccobj) {
                            $this->keyname($ccobj);

                            $attrs['system'] = in_array($ccobj->key_name, $sysuserattr) ? 1 : 0;
                            $attrs['key_name'] = $ccobj->key_name;
                            $attrs['show_name'] = $ccobj->show_name;
                            $attrs['type'] = $ccobj->type;
                            $attrs['options'] = $ccobj->options;
                            $attrs['required'] = $ccobj->required;
                            $attrs['rules'] = $ccobj->rules;
                            array_push($attr['attrs'], $attrs);
                        }
                        array_push($registerform[$obj->id], $attr);
                    }
                }
            }


//            print_r($groupform);exit;

            //update
            foreach ($registerform as $key => $v) {
                if (!$v) continue;
                $model = RegisterType::findOne($key);


                $model->registerform = json_encode($v);
                $model->groupform = json_encode([]);

                $model->save() && $model->isNewRecord = true && $model->id = 1;
            }

            foreach ($groupform as $key => $v) {
                if (!$v) continue;
                $model = RegisterType::findOne($key);
                $model->groupform = json_encode($v);
                $model->save() && $model->isNewRecord = true && $model->id = 1;;
            }
            $transaction->commit();
            echo "分组OL";
        } catch (Exception $e) {
            $transaction->rollBack();
            print_r($e->getMessage());
            exit;

        }
    }

    /**
     * @param $ids
     * register info
     * register group
     */
    public function actionHandleRegister($ids)
    {


        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {

            $select = ['mpms_register_info.id', 'mpms_register_info.matchid'];
            $registerInfos = RegisterInfo::find()->select($select)->joinWith('values')->andWhere(['mpms_register_info.matchid' => $ids])->all();

            foreach ($registerInfos as $obj) {

                $infoss = [];
                $infos = [];
                foreach ($obj->values as $cobj) {
                    $this->keyname($cobj);
                    $name = explode("_", $cobj->key_name)[1];
                    if ($obj->hasAttribute($name)) {
                        $obj->setAttribute($name, trim($cobj->value));
                    }

                    $infos['key_name'] = $cobj->key_name;
                    $infos['show_name'] = $cobj->show_name;
                    $infos['type'] = $cobj->type;
                    $infos['options'] = $cobj->options;
                    $infos['required'] = $cobj->required;
                    $infos['rules'] = $cobj->rules;
                    $infos['value'] = $cobj->value;
                    array_push($infoss, $infos);
                }
                $obj->registerinfos = json_encode($infoss);


                $obj->save() && $obj->isNewRecord = true;
            }

            $select = ['mpms_register_group.id', 'mpms_register_group.matchid'];
            //register Group
            $registerGroup = RegisterGroup::find()->select($select)->joinWith('values')->andWhere(['mpms_register_group.matchid' => $ids])->all();

            foreach ($registerGroup as $obj) {
                $infoss = [];
                $infos = [];

                foreach ($obj->values as $cobj) {
                    $this->keyname($cobj);
                    $name = explode("_", $cobj->key_name)[1];
                    if ($obj->hasAttribute($name)) {
                        $obj->setAttribute($name, $cobj->value);
                    }
                    $infos['key_name'] = $cobj->key_name;
                    $infos['show_name'] = $cobj->show_name;
                    $infos['type'] = $cobj->type;
                    $infos['options'] = $cobj->options;
                    $infos['required'] = $cobj->required;
                    $infos['rules'] = $cobj->rules;
                    $infos['value'] = $cobj->value;
                    array_push($infoss, $infos);
                }
                $obj->groupinfos = json_encode($infoss);
                $obj->save() && $obj->isNewRecord = true;
            }


            $transaction->commit();
            echo "Info OL";
        } catch (Exception $e) {
            $transaction->rollBack();
            print_r($e->getMessage());
            exit;

        }

    }


    public function actionHandleInfo($ids)
    {


        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {

            $registerInfos = RegisterInfo::find()->joinWith('values')->andWhere(['mpms_register_info.matchid' => $ids])->all();

            foreach ($registerInfos as $obj) {

                $infoss = [];
                $infos = [];
                foreach ($obj->values as $cobj) {
                    $this->keyname($cobj);
                    $name = explode("_", $cobj->key_name)[1];
                    if ($obj->hasAttribute($name)) {
                        $obj->setAttribute($name, $cobj->value);
                    }

                    $infos['key_name'] = $cobj->key_name;
                    $infos['show_name'] = $cobj->show_name;
                    $infos['type'] = $cobj->type;
                    $infos['options'] = $cobj->options;
                    $infos['required'] = $cobj->required;
                    $infos['rules'] = $cobj->rules;
                    $infos['value'] = $cobj->value;
                    array_push($infoss, $infos);
                }
                $obj->registerinfos = json_encode($infoss);


                $obj->save() && $obj->isNewRecord = true;
            }


            $transaction->commit();
            echo "Info OL";
        } catch (Exception $e) {
            $transaction->rollBack();
            print_r($e->getMessage());
            exit;

        }

    }


    public function actionHandleGroup($ids)
    {


        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {

            //register Group
            $registerGroup = RegisterGroup::find()->joinWith('values')->andWhere(['mpms_register_group.matchid' => $ids])->all();

            foreach ($registerGroup as $obj) {
                $infoss = [];
                $infos = [];

                foreach ($obj->values as $cobj) {
                    $this->keyname($cobj);

                    if ($cobj->key_name == 'mv_leader_mobile') $cobj->key_name = "mv_mobile";

                    $name = explode("_", $cobj->key_name)[1];
                    if ($obj->hasAttribute($name)) {
                        $obj->setAttribute($name, $cobj->value);
                    }
                    $infos['key_name'] = $cobj->key_name;
                    $infos['show_name'] = $cobj->show_name;
                    $infos['type'] = $cobj->type;
                    $infos['options'] = $cobj->options;
                    $infos['required'] = $cobj->required;
                    $infos['rules'] = $cobj->rules;
                    $infos['value'] = $cobj->value;
                    array_push($infoss, $infos);
                }
                $obj->groupinfos = json_encode($infoss);
                $obj->save() && $obj->isNewRecord = true;
            }


            $transaction->commit();
            echo "Group OL";
        } catch (Exception $e) {
            $transaction->rollBack();
            print_r($e->getMessage());
            exit;

        }

    }


    /**
     * @return string
     * 人员分工页面
     * @author xueyi
     * @time 2018/4/25
     */
    public function actionPersonnel($mid)
    {
        $model = self::findModel($mid);
        return $this->render('personnel', [
            'mid' => $mid,
            'model' => $model,
            'line' => self::createLine(),
            'menu' => self::createMenu(),
        ]);
    }

    /**
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * 人员分工提交
     * @author xueyi
     * @time 2018/4/25
     */
    public function actionPersonnelDo()
    {
        $data = Yii::$app->request->get('data');
        $mid = Yii::$app->request->get('mid');
        if (!$mid) {
            return $this->asJson(['status' => 202, 'msg' => '缺少参数mid']);
        }
        $model = self::findModel($mid);
        $model->infos = json_encode($data);
        $model->setScenario('update');
        if ($model->save()) {
            return $this->asJson(['status' => 200, 'msg' => '人员分工保存成功']);
        } else {
            return $this->asJson(['status' => 202, 'msg' => '人员分工保存失败']);
        }
    }

    /**
     * @return string
     * 新增人员的html元素
     * @author xueyi
     * @time 2018/5/7
     */
    protected function createLine()
    {
        $model = new Match();
        $line = '<div class="person">';
        $line .= '<div class="col-md-6 no-right">';
        $line .= '<input type="text" name="job-name" class="form-control" placeholder="岗位...">';
        $line .= '</div>';
        $line .= '<div class="col-md-6 no-left">';
        $line .= '<div class="input-group">';
        $line .= '<select name="job-value" class="form-control">';
        $line .= '<option value="0">请选择人员</option>';
        foreach ($model->getUserList() as $k => $v) {
            $line .= '<option value="' . $k . '">' . $v . '</option>';
        }
        $line .= '</select>';
        $line .= '<span class="input-group-btn">';
        $line .= '<button class="btn btn-danger remove" type="button">移除</button>';
        $line .= '</span></div></div></div>';

        return $line;
    }

    

}
