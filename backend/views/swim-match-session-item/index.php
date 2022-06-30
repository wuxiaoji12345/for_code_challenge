<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Match;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Search\MatchSessionItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $params array */

$this->title = '赛事场次项目管理';
$this->params['breadcrumbs'][] = $this->title;
$pre = $dataProvider->pagination->getPageCount();
$count = $dataProvider->getCount();
$totalCount = $dataProvider->getTotalCount();
$begin = $dataProvider->pagination->getPage() * $dataProvider->pagination->pageSize + 1;
$end = $begin + $count - 1;
?>
<p>
    <?= Html::a('创建赛事场次项目', ['create'], ['class' => 'btn btn-success']) ?>
</p>
<?= $this->render('_search', ['model' => $searchModel, 'params' => $params]); ?>
<section class="scrollable padder">
    <div class="row bg-light m-b">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold">赛事场次项目列表
                    <div class="pull-right">
                        <div class="summary">
                            第<b><?= $begin . '-' . $end ?></b>条, 共<b><?= $dataProvider->totalCount ?></b>条数据.
                        </div>
                    </div>
                </header>
                <div class="panel-body">
                    <div class="swim-match-session-item-index">
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
                                    'label' => '场次ID',
                                    'value' => function($model) {
                                        return $model->ssid;
                                    }
                                ],
                                [
                                    'label' => '项目名称',
                                    'format' => 'raw',
                                    'value' => function($model) {
                                        return Html::a($model->name,
                                            '/swim-score-enroll/index?ScoreEnrollSearch[itemid]=' . $model->id);
                                    }
                                ],
                                [
                                    'label' => '参赛年龄',
                                    'value' => function($model) {
                                        return $model->agemin . '-' . $model->agemax . '岁';
                                    }
                                ],
                                [
                                    'label' => '权重',
                                    'value' => function($model) {
                                        return $model->weight;
                                    }
                                ],
                                [
                                    'label' => '更新时间',
                                    'value' => function($model) {
                                        return $model->update_time;
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => '操作',
                                    'template' => '{printGroup} <br/> {rank} <br/> {view} {update}',
                                    'buttons' => [
//                                        'delete' => function($url, $model){
//                                            return Html::a('<span class="glyphicon glyphicon-trash"></span>',
//                                                ['delete', 'id' => $model->id], [
//                                                'data' => [
//                                                    'confirm' => '确认删除?',
//                                                    'method' => 'post',
//                                                ],
//                                            ]);
//                                        },
                                        'printGroup' => function($url, $model){
                                            return Html::a('<span class="glyphicon glyphicon-print"></span>打印分组',
                                                ['print-group', 'id' => $model->id], ['target' => '_blank']);
                                        },
                                        'rank' => function($url, $model){
                                            return Html::a('<span class="glyphicon glyphicon-arrow-down"></span>成绩排名',
                                                ['rank', 'id' => $model->id, 'ssid' => $model->ssid], ['target' => '_blank']);
                                        },
                                    ]
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => '删除',
                                    'template' => '{delete}',
                                    'buttons' => [
                                        'delete' => function($url, $model){
                                            return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                                                ['delete', 'id' => $model->id], [
                                                'data' => [
                                                    'confirm' => '确认删除?',
                                                    'method' => 'post',
                                                ],
                                            ]);
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