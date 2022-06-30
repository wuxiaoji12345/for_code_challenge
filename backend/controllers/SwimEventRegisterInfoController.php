<?php
namespace backend\controllers;

use common\models\Match;
use common\models\RegisterRelation;
use common\models\RegisterType;
use Yii;
use common\models\RegisterInfo;
use common\models\search\RegisterInfoSearch;
use yii\data\Sort;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\bootstrap\Html;
use common\models\Attrs;
use common\models\RegisterGroupAttrValue;
use kartik\editable\Editable;
use kartik\grid\GridView;
use common\components\Helper;
use moonland\phpexcel\Excel;

/**
 * RegisterInfoController implements the CRUD actions for RegisterInfo model.
 */
class SwimEventRegisterInfoController extends Controller
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
     * Lists all RegisterInfo models.
     * @return mixed
     */
    public function actionIndex($aid,$rrid='',$simple=false)
    {

        if(!$aid) $this->goBack();


        if($aid)   $where['matchid']  =   $aid;
        if(!$rrid){
            $rrinfos    =   RegisterRelation::find()->asArray()->andWhere($where)->select(['id'])->all();
            $rrids  =   [];
            if($rrinfos)
            {
                foreach ($rrinfos as $key=>$v)
                {
                    array_push($rrids,$v['id']);
                }
            }
        }else{
            $rrids  =   [$rrid];
        }

        $searchModel    = new RegisterInfoSearch();
        $queryParams    =   Yii::$app->request->queryParams;
        $queryParams[$searchModel->formName()]['matchid']    =   $aid;
//        $queryParams[$searchModel->formName()]['state']    =   1;
        $dataProvider = $searchModel->search($queryParams,['in','rrid',$rrids]);

        if(Yii::$app->request->isPost)
        {
            $post   =   Yii::$app->request->post();
            $model  =   $this->findModel($post['editableKey']);
            $post   =   current($post['RegisterInfo']);
            $keys   =   array_keys($post);
            $post   =   ['RegisterInfo'=>$post];


            if($model->load($post)&&$model->save())
            {
                $fkeys   =  $keys[0];
                $output =   $model->$fkeys;

                if($fkeys=='state')
                {
                    $output = $model->stateName;
                }
                return json_encode(['output'=>$output, 'message'=>'']);
            }else{
                return json_encode(['output'=>'', 'message'=>Helper::getErrormsg($model)]);
            }
        }

        if($simple) {
            $this->layout   =   false;
            $dataProvider->pagination->pageSize =   99999999;

        }


        return   $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'simple'=>$simple,
            'matchid'=>$aid
        ]);
    }
    /**
     * Lists all RegisterInfo models.
     * @return mixed
     */
    public function actionIndexs($aid,$rrid='',$simple=false)
    {

        if(!$aid) $this->goBack();


        if($aid)   $where['matchid']  =   $aid;
        if(!$rrid){
            $rrinfos    =   RegisterRelation::find()->asArray()->andWhere($where)->select(['id'])->all();
            $rrids  =   [];
            if($rrinfos)
            {
                foreach ($rrinfos as $key=>$v)
                {
                    array_push($rrids,$v['id']);
                }
            }
        }else{
            $rrids  =   [$rrid];
        }

        $searchModel    = new RegisterInfoSearch();
        $queryParams    =   Yii::$app->request->queryParams;
        $queryParams[$searchModel->formName()]['matchid']    =   $aid;
//        $queryParams[$searchModel->formName()]['state']    =   1;
        $dataProvider = $searchModel->search($queryParams,['in','rrid',$rrids]);

        if(Yii::$app->request->isPost)
        {
            $post   =   Yii::$app->request->post();
            $model  =   $this->findModel($post['editableKey']);
            $post   =   current($post['RegisterInfo']);
            $keys   =   array_keys($post);
            $post   =   ['RegisterInfo'=>$post];


            if($model->load($post)&&$model->save())
            {
                $fkeys   =  $keys[0];
                $output =   $model->$fkeys;

                if($fkeys=='state')
                {
                    $output = $model->stateName;
                }
                return json_encode(['output'=>$output, 'message'=>'']);
            }else{
                return json_encode(['output'=>'', 'message'=>Helper::getErrormsg($model)]);
            }
        }

        if($simple) {
            $this->layout   =   false;
            $dataProvider->pagination->pageSize =   99999999;

        }


        return   $this->render('index_baiyulan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'simple'=>$simple,
            'matchid'=>$aid
        ]);
    }

    /**
     * 报名信息导出
     * @param $mid
     * @return \yii\web\Response
     */
    public function actionExport($mid)
    {

        ini_set("memory_limit",-1);
        $match = Match::findOne($mid);
        if (!$mid) $this->goBack();
        //跟新 UPDATE
        $sql    =   new Expression("update mpms_register_info a  set gnum =  (select gnum from mpms_register_relation where matchid = a.matchid and rgid=a.rgid) where a.matchid = {$mid}");
        Yii::$app->db->createCommand($sql)->execute();
        $models = RegisterInfo::find();
        $models->select([
            new Expression("(year(now())-year(birth)-1)  as age"),
            'mpms_register_info.id',
            'mpms_register_info.rgid',
            'mpms_register_info.matchid',
            'mpms_register_info.typeid',
            'mpms_register_info.memberid',
            'mpms_register_info.rrid',
            'mpms_register_info.ischeckin',
            'mpms_register_info.state',
            'mpms_register_info.name',
            'mpms_register_info.mobile',
            'mpms_register_info.sex',
            'mpms_register_info.idtype',
            'mpms_register_info.idnumber',
            'mpms_register_info.birth',
            'mpms_register_info.avatar',
            'mpms_register_info.registerinfos',
            'mpms_register_info.size',
            'mpms_register_info.gnum',
            'mpms_register_info.subgnum',
            'mpms_register_info.orderindex',
            'mpms_register_relation.order_no',
            'mpms_register_relation.trade_no',
            'mpms_register_relation.fees',
        ]);
        $models->andWhere([RegisterInfo::tableName() . '.matchid' => $mid, RegisterInfo::tableName() . '.state' => 1]);
        //加条件过滤
        $models->andWhere([
            RegisterRelation::tableName() . '.state' => 1,
            RegisterInfo::tableName() . '.matchid' => $mid,
        ]);
        $models->joinWith('registerType');
        $models->joinWith('relations');
        $models->joinWith('registerGroup');
        $models->groupBy(['mpms_register_info.rgid', 'rrid', 'id']);
        $models->orderBy([
            'typeid' => SORT_DESC,
            'convert(trim((select `name` from mpms_register_info a  where id= ( select id from mpms_register_info b where  rgid = mpms_register_info.rgid order by (year(now())-year(birth)-1) asc limit 1))) using gbk)' => SORT_ASC,
            'mpms_register_info.rgid' => SORT_ASC,
            'age' => SORT_ASC,
        ]);




        $allmodel       =    clone $models;
        $allmodel       =   $allmodel->all();
        $allcolumn      =   [];
        $allmodels      =   ['报名信息'=>$allmodel];
        $this->formatcolumns($allmodel,$allcolumn,3);
        $allcolumns     =   ['报名信息'=>$allcolumn];
        //按照分组
        $type = RegisterType::find()->andWhere(['matchid' => $mid])->select(['title','type','id'])->asArray()->all();
        foreach ($type as $v)
        {
            $columns        =   [];
            $typemodel      =   clone $models;
            if(empty($allmodels[$v['title']]))  $allmodels[$v['title']]     =   [];
            if(empty($allcolumns[$v['title']])) $allcolumns[$v['title']]    =   [];
            $allmodels[$v['title']]     =   $typemodel->andWhere([RegisterRelation::tableName().'.typeid'=>$v['id']])->all();



            $this->formatcolumns($allmodels[$v['title']],$columns,$v['type']);
            $allcolumns[$v['title']]    = $columns;
        }



        Excel::export([
         'fileName'=>$match->title."报名信息(".date('ymd').")".'.xlsx',
         'isMultipleSheet' => true,
  		 'models' => $allmodels,
  		 'columns' => $allcolumns
        ]);

    }

    /**
     * 格式化 columns
     * @param $obj
     * @param $columns
     * @param $type
     */
    public function formatcolumns(&$obj,&$columns,$type)
    {
        $colforeach =   function($obj,$keys,&$columns,$extrkey=''){
            $registerinfo   =   new RegisterInfo();
            foreach($obj as $objk=> $objs)
            {
                $cachename  =  'attrcache'.$objs->typeid;
                //读取 缓存
                $attrcache      =   Yii::$app->cache->get($cachename);
                $registerinfos  =   $objs->$keys;
                if($extrkey)  $registerinfos    =   $objs->$keys->$extrkey;
                if(!$registerinfos) continue;
                //得到json存储的所有数据 & 遍历
                $registerinfos          =   json_decode($registerinfos,true);
                $valuearr               =   [];
                foreach ($registerinfos as $key=>$v)
                {
                    if(!isset($v['value']))
                        continue;
                    if(is_array($v['value']))
                    {
                        $v['value']   =   json_encode($v['value']);
                    }
                    $v['value'] =   isset($v['value'])?$v['value']:'';
                    //如果是系统字段 忽略 因为 对象中已经包含了
                    if(!(strpos($v['key_name'],'mv_')===false)&&$registerinfo->hasAttribute(explode("_",$v['key_name'])[1])) continue;
                    //判断是否已经缓存
                    if($attrcache && isset($attrcache[$v['key_name']]))
                    {
                        $attr   =     'attr'.$attrcache[$v['key_name']];
                    }elseif ($attrcache){
                        $attr   =   'attr'.count($attrcache);
                        $attrcache[$v['key_name']]  =   count($attrcache);
                        Yii::$app->cache->set($cachename,$attrcache);
                    }else{
                        $attr   =   'attr0';
                        $attrcache[$v['key_name']]  =   0;
                        Yii::$app->cache->set($cachename,$attrcache);
                    }
                    $objs->$attr        =   $v['value'];
                    if(!in_array(['attribute'=>$attr,'header'=>$v['show_name']],$columns) && !empty($v['show_name']))
                    {
                        $columns[]  =   [
                            'attribute'=>$attr,
                            'header'=>$v['show_name'],
                        ];
                    }
                }
            }


        };

        $infocolumns    = $gcolumns  =   [];



        $colforeach($obj,'registerinfos',$infocolumns);
        $colforeach($obj,'registerGroup',$infocolumns,'groupinfos');





        $attributes =   [
            [
                'attribute'=>'registerType.title',
                'header'=>'组别',
            ],
            [
                'attribute'=>'gnum',
                'header'=>'团队编号',
            ],
            [
                'attribute'=>'rgid',
                'header'=>'团队ID',
            ],
            [
                'attribute'=>'id',
                'header'=>'组内编号',
            ],
            [
                'attribute'=>'name',
                'header'=>'姓名',
            ],
            [
                'attribute'=>'idtype',
                'header'=>'证件类型',
            ],
            [
                'attribute'=>'idnumberexport',
                'header'=>'证件号码',
            ],
            [
                'attribute'=>'mobile',
                'header'=>'手机号码',
            ],
            [
                'attribute'=>'sex',
                'header'=>'性别',
            ],
            [
                'attribute'=>'size',
                'header'=>'衣服尺码',
            ],
            [
                'attribute'=>'ischeckin',
                'header'=>'是否检录',
            ],
            [
                'attribute'=>'relations.order_no',
                'header'=>'商户订单号',
            ],
            [
                'attribute'=>'relations.trade_no',
                'header'=>'微信交易号',
            ],
            [
                'attribute'=>'relations.fees',
                'header'=>'支付金额',
            ],
        ];

        $columns    = array_merge($attributes,$infocolumns,$gcolumns);

        if($type==RegisterType::TYPEGROUP)
        {

            array_push($columns,

                [
                    'attribute'=>'registerGroup.regname',
                    'header'=>'团队名称',
                ],
                [
                    'attribute'=>'registerGroup.unit',
                    'header'=>'单位名称',
                ],
                [
                    'attribute'=>'registerGroup.leader',
                    'header'=>'领队名称',
                ],
                [
                    'attribute'=>'registerGroup.mobile',
                    'header'=>'领队电话',
                ],
                [
                    'attribute'=>'relations.paytypename',
                    'header'=>'支付方式',
                ],[
                    'attribute'=>'minagename',
                    'header'=>'赛包领取首字母',
                ],
                [
                    'attribute'=>'minageUser',
                    'header'=>'赛包领取人',
                ]
            );
        }


    }


    /**
     * @param array $data
     * @param $key
     * @param array $sortarr
     */
    static public function array_sort(array $data,$key,$sortarr,$deletekey=false)
    {
        $mainarr    =   [];
        $otherarr   =   [];

        foreach ($data as $datum) {

            foreach ($sortarr as $item) {

                if($datum[$key]==$item)
                {

                    $mainarr[$item]  =     $datum;
                }
            }
            if(!in_array($datum[$key],$sortarr))
            {
                array_push($otherarr,$datum);
            }

        }
        return array_merge($mainarr,$otherarr);




    }

    
    
    /**
     * Displays a single RegisterInfo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        
        RegisterInfo::find()
        ->andWhere(['mpms_register_info.id'=>$id])
        ->joinWith('values',false)
        ->all();
        
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RegisterInfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RegisterInfo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RegisterInfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    /**
     * Updates an existing RegisterInfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateinfos($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isPost)
        {
            $post       =   Yii::$app->request->post();
            $reginfos   =   $model->registerinfos;

            if(!$reginfos) $this->redirect(Yii::$app->request->referrer);
            $reginfos   =   json_decode($reginfos,true);

            foreach ($reginfos as $key=>$ov)
            {
                foreach ($post as $nkey=>$nv)
                {
                    if($nkey==$ov['key_name'])
                    {
                        $reginfos[$key]['value']    =   $nv;
                    }
                }
            }

            $model->registerinfos   =   json_encode($reginfos);
            if(!$model->save())
            {
                Helper::setFlash($model,'error');
            }

        }
        $this->redirect(Yii::$app->request->referrer);


//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        } else {
//            return $this->render('update', [
//                'model' => $model,
//            ]);
//        }
    }
    
    public function getGroupValues()
    {
        return $this->hasMany(RegisterGroupAttrValue::className(),['rgid'=>'rgid']);
    }
    

    /**
     * Deletes an existing RegisterInfo model.
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
     * Finds the RegisterInfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RegisterInfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RegisterInfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSubmitinfos()
    {
        if(Yii::$app->request->isAjax)
        {
            $post   =   Yii::$app->request->post();
            $result =   RegisterInfo::updateAll(['state'=>1],['in','id',$post['keys']]);
        }
    }


    public function actionExportAvatar($matchid){

        $rrm =  RegisterInfo::find();
        $res = $rrm->andWhere([RegisterInfo::tableName().'.matchid'=>$matchid])
            ->select([
                RegisterInfo::tableName().'.name',
                'avatar',
                'rrid',
                RegisterInfo::tableName().'.rgid',
            ])
            ->joinWith(['relations'=>function($query){
                $query->select(['state','id','gnum']);
            }])
            ->joinWith(['registerGroup'=>function($query){
                $query->select(['regname','unit','id']);
            }])
            ->andWhere([RegisterRelation::tableName().'.state'=>1])
            ->asArray()
            ->all();

            $dir = './avatar/'.$matchid."/";


            if(!is_dir($dir)) {
                if(!mkdir($dir,'0777',true)) echo "目录生成出错";
            }

            foreach ($res as $key=>$v){
                $type   =   explode(".",$v['avatar'])[count(explode(".",$v['avatar']))-1];
                $name = $v['relations']['gnum']."-".$v['name']."-".$v['registerGroup']['unit']."-".$v['registerGroup']['regname'].".".$type;
                file_put_contents($dir.$name,file_get_contents($v['avatar']));
            }

            echo "OK";



    }


}
