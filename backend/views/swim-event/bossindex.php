<?php

use yii\helpers\Html;
use yii\grid\GridView;
use mdm\admin\components\Helper;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\RegisterInfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = '报名信息管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary color-palette-box">
        <div class="box-header with-border">
            <h3 class="box-title"><?=$this->title?></h3>
             <div class="box-tools">
            </div>
        </div>    
        <!-- /.box-header -->
       <div class="box-body table-responsive no-padding"> 
    <!-- 增加权限跑断 lito  -->
<?php Pjax::begin(); ?>    
<?php if(isset($dataProvider)):?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions'=>['class'=>'table table-hover text-center table-bordered'],
        'layout' => '{items}',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>'编号'],
            'mtitle',
            'reg_start_time:datetime',
            'reg_end_time:datetime',
            'address',
            'dead_time:date',
           // 增加权限跑断 lito  
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'操作',
                'template'=>'{view}',
                'buttons'=>[
                    'view'=> function($url, $model,$key){
                        return Html::a('报名信息查看',['register-relation/index','matchid'=>$model->id],
                            [
                                'class' => 'btn btn-warning  btn-xs num',
                            ]
                        );
                    },
                ]
            ],
        ],
    ]); ?>
 <?php else:?>
    <div class="col-xs-12 text-center">暂无信息</div>   
 <?php endif;?>   
<?php Pjax::end(); ?>
</div>
</div>