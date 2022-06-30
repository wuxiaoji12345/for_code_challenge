<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\switchinput\SwitchInput;
use backend\models\Match;
use backend\models\RegisterType;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Search\MatchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '浦东新区游泳场所开放信息化应用系统';
$this->params['breadcrumbs'][] = $this->title;
$pre = $dataProvider->pagination->getPageCount();
$count = $dataProvider->getCount();
$totalCount = $dataProvider->getTotalCount();
$begin = $dataProvider->pagination->getPage() * $dataProvider->pagination->pageSize + 1;
$end = $begin + $count - 1;
?>
<p>
    <?= Html::a('创建赛事', ['create'], ['class' => 'btn btn-success']) ?>
</p>
<?= $this->render('_search', ['model' => $searchModel]); ?>
<section class="scrollable padder">
    <div class="row bg-light m-b">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold">赛事列表
                    <div class="pull-right">
                        <div class="summary">
                            第<b><?= $begin . '-' . $end ?></b>条, 共<b><?= $dataProvider->totalCount ?></b>条数据.
                        </div>
                    </div>
                </header>
                <div class="panel-body">
                    <div class="swim-match-index">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'options' => ['style' => 'table-layout:fixed;'],
                            'columns' => [
                                'id',
                                [
                                    'label' => '标题',
                                    'format' => 'raw',
                                    'contentOptions' => ['style' => 'width:400px;'],
                                    'value' => function($model) {
                                        return Html::a($model->title,
                                            '/swim-match-session/index?matchid=' . $model->id);
                                    }
                                ],
                                [
                                    'label' => '赛事图片',
                                    'format' => 'raw',
                                    'value'=> function($model) {
                                        return Html::img($model->imgurl, ['width' => '100px']);
                                    },
                                ],
                                [
                                    'label' => '线上报名设置',
                                    'format' => 'html',
                                    'value'=> function($model) {
                                        $exist = (new RegisterType())->existMatchRegister($model->id);
                                        $title = ($exist ? '查看' : '<i class="fa fa-hand-o-right"></i><span>去设置</span>');
                                        $url = ($exist ? '/swim-register-type/index?RegisterTypeSearch[matchid]=' . $model->id
                                            : '/swim-register-type/create?matchid=' . $model->id);
                                        return Html::a($title, $url);
                                    },
                                ],
                                [
                                    'label' => '报名时间',
                                    'format' => 'raw',
                                    'value'=> function($model) {
                                        return date('Y-m-d H:i:s', $model->reg_start_time)
                                            . '~<br/>' . date('Y-m-d H:i:s', $model->reg_end_time);
                                    },
                                ],
                                [
                                    'label' => '赛事时间',
                                    'format' => 'raw',
                                    'value'=> function($model) {
                                        return date('Y-m-d H:i:s', $model->start_time)
                                            . '~<br/>' . date('Y-m-d H:i:s', $model->start_time);
                                    },
                                ],
                                [
                                    'label' => '发布状态',
                                    'format' => 'raw',
                                    'value' => function($model) {
                                        return SwitchInput::widget([
                                            'id' => 'publish_' . $model->id,
                                            'name' => 'publish_' . $model->id,
                                            'value' => $model->publish == Match::PUBLISH_YES ? true : false,
                                            'pluginOptions'=>[
                                                'size' => 'mini',
                                                'handleWidth' => 10,
                                                'onText'=>'是',
                                                'offText'=>'否',
                                                'onColor' => 'success',
                                                'offColor' => 'danger',
                                            ],
                                            'pluginEvents' => [
                                                "switchChange.bootstrapSwitch" => "function(event, state) {
                                                    //var match_id = $(this).parent().parent().parent().parent().siblings(':first').text();
                                                    var match_id = this.name.replace('publish_', '');
                                                    var ret = $.ajax({
                                                        type:'POST',
                                                        url: '/swim-match/update-publish',
                                                        data: {
                                                            'id': match_id,
                                                            'publish': state ? 1 : 0
                                                        },
                                                        datatype: 'json',
                                                        success:function(data){
                                                            if (data.code != 0) {
                                                            //$('#publish_'+id).bootstrapSwitch('setState', state);
                                                                alert('修改发布状态失败');
                                                            }
                                                        },
                                                        error: function(){
                                                            alert('修改发布状态失败');
                                                        }   
                                                    });
                                                }"
                                            ],
                                        ]);
                                    }
                                ],
                                [
                                    'label' => '更新时间',
                                    'value'=> function($model) {
                                        return $model->update_time;
                                    },
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => '操作',
                                    'template' => '{view} {update}',
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => '删除',
                                    'template' => '{delete}',
                                    'buttons' => [
                                        'delete' => function($url, $model){
                                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], [
                                                'data' => [
                                                    'confirm' => '确认删除?',
                                                    'method' => 'post',
                                                ],
                                            ]);
                                        }
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