<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Address;
use backend\models\Match;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Search\MatchSessionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $params array */

$this->title = '赛事场次管理';
$this->params['breadcrumbs'][] = $this->title;
$pre = $dataProvider->pagination->getPageCount();
$count = $dataProvider->getCount();
$totalCount = $dataProvider->getTotalCount();
$begin = $dataProvider->pagination->getPage() * $dataProvider->pagination->pageSize + 1;
$end = $begin + $count - 1;
?>
<p>
    <?= Html::a('创建赛事场次', ['create'], ['class' => 'btn btn-success']) ?>
</p>
<?= $this->render('_search', ['model' => $searchModel, 'params' => $params]); ?>
<section class="scrollable padder">
    <div class="row bg-light m-b">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold">赛事场次列表
                    <div class="pull-right">
                        <div class="summary">
                            第<b><?= $begin . '-' . $end ?></b>条, 共<b><?= $dataProvider->totalCount ?></b>条数据.
                        </div>
                    </div>
                </header>
                <div class="panel-body">
                    <div class="swim-match-session-index">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'options' => ['style' => 'table-layout:fixed;'],
                            'columns' => [
                                'id',
                                [
                                    'label' => '赛事名称',
                                    'contentOptions' => ['style' => 'width:250px;'],
                                    'value' => function($model) {
                                        return (new Match())->getTitleByID($model->matchid);
                                    }
                                ],
                                [
                                    'label' => '场次名',
                                    'value' => function($model) {
                                        return $model->name;
                                    }
                                ],
                                [
                                    'label' => '比赛场馆',
                                    'value' => function($model) {
                                        return (new Address())->getNameByID($model->swim_address_id);
                                    }
                                ],
                                [
                                    'label' => '可报名项目数',
                                    'format' => 'raw',
                                    'value' => function($model) {
                                        return Html::a($model->register_count,
                                            '/swim-match-session-item/index?MatchSessionItemSearch[ssid]=' . $model->id);
                                    }
                                ],
                                [
                                    'label' => '比赛开始时间',
                                    'value' => function($model) {
                                        return $model->start_time;
                                    }
                                ],
                                [
                                    'label' => '更新时间',
                                    'value' => function($model) {
                                        return $model->update_time;
                                    }
                                ],
                                [
                                    'label' => '主裁判计时',
                                    'format' => 'raw',
                                    'value' => function($model) {
                                        return Html::a('主裁判计时',
                                            'http://h5.swim.moveclub.cn/config', ['target' => '_blank']);
                                    }
                                ],
                                [
                                    'label' => '赛道裁判计时',
                                    'format' => 'raw',
                                    'value' => function($model) {
                                        return Html::a('赛道裁判计时',
                                            "http://h5.swim.moveclub.cn/configassit/{$model->matchid}/{$model->id}", ['target' => '_blank']);
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => '操作',
                                    'template' => '{grouping} <br/> {onlineRegisterImport} <br/> {printGroups} <br/>  {importView} <br/> {view} {update} &nbsp&nbsp&nbsp&nbsp&nbsp {delete}',
                                    'buttons' => [
                                        'delete' => function($url, $model){
                                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], [
                                                'data' => [
                                                    'confirm' => '确认删除?',
                                                    'method' => 'post',
                                                ],
                                            ]);
                                        },
                                        'printGroups' => function($url, $model){
                                            return Html::a('<span class="glyphicon glyphicon-print"></span>打印秩序表',
                                                ['print-groups', 'id' => $model->id], ['target' => '_blank']);
                                        },
                                        'grouping' => function($url, $model){
                                            return Html::a('<span class="glyphicon glyphicon-th-list"></span>自动分组',
                                                ['grouping', 'id' => $model->id], [
                                                    'data' => [
                                                        'confirm' => '确认分组? (如果有历史分组会删除所有记录后重新分组!!!)',
                                                        'method' => 'post',
                                                    ],
                                                ]);
                                        },
                                        'onlineRegisterImport' => function($url, $model){
                                            return Html::a('<span class="glyphicon glyphicon-import"></span>在线报名导入',
                                                ['online-register-import', 'id' => $model->id]);
                                        },
                                        'importView' => function($url, $model){
                                            return Html::a('<span class="glyphicon glyphicon-import"></span>线下报名用户导入',
                                                ['import-view', 'id' => $model->id]);
                                        },
                                    ]
                                ],
                            ],
                            'layout' => '{items}{pager}',
                            'summary' => '', //Total xxxx items.
                            'pager' => [
                                'options'=>['class'=>'pagination'],
                                'prevPageLabel' => '上一页',
                                'firstPageLabel'=> '首页',
                                'nextPageLabel' => '下一页',
                                'lastPageLabel' => '末页',
                                'maxButtonCount'=>'10',
                            ]
                        ]); ?>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>