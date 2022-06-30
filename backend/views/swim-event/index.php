<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use backend\assets\AppAsset;
use yii\helpers\ArrayHelper;
use common\models\MatchCategory;
use common\models\Event;
use yii\bootstrap\Dropdown;
use yii\base\Widget;
use mdm\admin\components\Helper;
use kartik\popover\PopoverX;
use kartik\editable\Editable;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MatchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '赛事列表';
$this->params['breadcrumbs'][] = $this->title;

// $tag    =   isset($tag)?$tag:"li";
$tag = "li";
$operates = function ($template) {
    return PopoverX::widget([
        'header' => '请选择操作',
        'placement' => PopoverX::ALIGN_RIGHT,
        'content' => $template,
        'footer' => '&nbsp;',
        'toggleButton' => ['label' => '操作', 'class' => 'btn btn-default'],
    ]);
};
$match = new Event();
$statusList = $match->statusList;


$type = isset($_GET['type']) ? $_GET['type'] : 0;
AppAsset::addSchedule($this);
?>

<?php if ($type != 2): ?>

    <div class="match-index">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'responsive' => true,
            'responsiveWrap' => false,
            'panel' => [
                'heading' => '<h3 class="panel-title">' . $this->title . '</h3>',
                'type' => AppAsset::BOX_CLASS,
                'after' => false
            ],
            'toolbar' => [
                [
                    'content' => $this->render("_search", ['model' => $searchModel])
                ]
            ],
            'columns' => [
                [
                    'class' => 'kartik\grid\SerialColumn',
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'header' => '操作',
                    'dropdown' => true,
                    'dropdownButton' => ['label' => '操作', 'class' => "btn btn-" . AppAsset::BOX_CLASS],


                    'template' => $template ? $template : '{registerinfo} {type}  {image-config} {image}  {event-externallink}  {timing} {material} {task} {finalaccounts}  {metting}  {delete} {sms} {checkin}  {volunteer} {personnel} {account} {orient} {pack} {gift} {temp} {design} {files} {forms}',


                    'buttons' => [
                        'design' => function ($url, $model, $key) use ($tag) {
                            return Html::a('设计', ['/event-design/index', 'mid' => $model->id, 'RegisterRelationSearch' => ['state' => 1]], ['class' => 'list-group-item','target'=>"_blank"]);
                        },

                        'delete' => function ($url, $model, $key) use ($tag) {
                            return Html::a('删除', ['/event/delete', 'id' => $model->id], ['class' => 'list-group-item text-red', 'data-confirm' => "确定删除?", 'data-method' => 'post']);
                        },
                        'update' => function ($url, $model, $key) use ($tag) {
                            return Html::a('信息修改', ['/event/update', 'id' => $model->id], ['class' => 'list-group-item']);
                        },
                        'editdetail' => function ($url, $model, $key) use ($tag) {
                            return Html::a('编辑详情', ['/event/updatedetil', 'id' => $model->id], ['class' => 'list-group-item']);
                        },

                        'image-config' => function ($url, $model, $key) use ($tag) {
                            return Html::a('照片配置', ['/event-image-config/config', 'mid' => $model->id], ['class' => 'list-group-item text-red']);
                        },
                        'match-externallink' => function ($url, $model, $key) use ($tag) {
                            return Html::a('链接汇总', ['/event-externallink/index', 'matchid' => $model->id], ['class' => 'list-group-item text-red']);
                        },
                        'timing' => function ($url, $model, $key) use ($tag) {
                            return Html::a('计时管理', ['/times-track/index', 'matchid' => $model->id], ['class' => 'list-group-item']);
                        },
                        'material' => function ($url, $model, $key) use ($tag) {
                            return Html::a('赛事物资', ['/event-material/index', 'aid' => $model->id], ['class' => 'list-group-item']);
                        },
                        'material2' => function ($url, $model, $key) use ($tag) {
                            return Html::a('赛事物资2', ['/event-track-tree/do2', 'mid' => $model->id], ['class' => 'list-group-item']);
                        },
                        'task' => function ($url, $model, $key) use ($tag) {
                            return Html::a('赛事任务', ['/event-flow/match', 'mid' => $model->id], ['class' => 'list-group-item']);
                        },
                        'task2' => function ($url, $model, $key) use ($tag) {
                            return Html::a('赛事任务2', ['/event-track-tree/index', 'mid' => $model->id], ['class' => 'list-group-item']);
                        },
                        'finalaccounts' => function ($url, $model, $key) use ($tag) {
                            return Html::a('物资决算', ['/event-material/final-accounts', 'aid' => $model->id], ['class' => 'list-group-item']);
                        },

                        'metting' => function ($url, $model, $key) use ($tag) {
                            return Html::a('协调会信息', ['/event-meeting-attr-value/index', 'mid' => $model->id], ['class' => 'list-group-item']);
                        },
                        'sms' => function ($url, $model, $key) use ($tag) {
                            return Html::a('短信提醒', ['/event/send-sms', 'mid' => $model->id], ['class' => 'list-group-item']);
                        },
                        'image' => function ($url, $model, $key) use ($tag) {
                            return Html::a('赛事照片', ['/event-image/index', 'mid' => $model->id], ['class' => 'list-group-item']);
                        },

                        'checkin' => function ($url, $model, $key) use ($tag) {
                            return Html::a('赛事检录', ['/checkin/index', 'aid' => $model->id], ['class' => 'list-group-item']);
                        },
                        'volunteer' => function ($url, $model, $key) use ($tag) {
                            return Html::a('志愿者管理', ['/volunteer-cert/index', 'matchid' => $model->id], ['class' => 'list-group-item']);
                        },

                        'personnel' => function ($url, $model, $key) {
                            return Html::a('人员分工', ['/event-staff/index', 'mid' => $model->id], ['class' => 'list-group-item']);
                        },
                        'account' => function ($url, $model, $key) {
                            return Html::a('记账', ['/event-account/index', 'mid' => $model->id], ['class' => 'list-group-item']);
                        },
                        'orient' => function ($url, $model, $key) {
                            return Html::a('定向赛', ['/orient-content/index', 'mid' => $model->id], ['class' => 'list-group-item']);
                        },
                        'pack' => function ($url, $model, $key) {
                            return Html::a('赛包领取', ['/game-pack/index', 'mid' => $model->id], ['class' => 'list-group-item']);
                        },
                        'gift' => function ($url, $model, $key) {
                            return Html::a('赛事抽奖', ['/event-gift/index', 'mid' => $model->id], ['class' => 'list-group-item']);
                        },
                        'files' => function ($url, $model, $key) {
                            return Html::a('文档', ['/project-files/index', 'projectid' => $model->id], ['class' => 'list-group-item']);
                        },
                        'forms' => function ($url, $model, $key) {
                            return Html::a('表单', ['/event-evaluation-form/index', 'matchid' => $model->id], ['class' => 'list-group-item']);
                        },
                    ],
                    'visibleButtons' => [
                        'registerinfo' => Helper::checkRoute('/register-relation/index'),
                        'update' => Helper::checkRoute('/match/update'),
                        'editdetail' => Helper::checkRoute('/match/updatedetil'),
                        'type' => Helper::checkRoute('/register-type/index'),
                        'material' => Helper::checkRoute('/match-material/index'),
                        'delete' => Helper::checkRoute('/match/delete'),
                        'finalaccounts' => Helper::checkRoute('/match-material/final-accounts'),
                    ]
                ],
                [
                    'label' => "报名信息",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => 'middle',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a('报名信息', ['/register-relation/index', 'aid' => $model->id, 'RegisterRelationSearch' => ['state' => 1]], ['target'=>"_blank"]);

                    }
                ],
                [
                    'label' => "组别",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => 'middle',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a('赛事组别', ['/register-type/index', 'aid' => $model->id], ['target'=>"_blank"]);
                    }
                ],
                [
                    'label' => "报名状态",
                    'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'status',
                    'value' => 'statusName',
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => 'middle',
                    'editableOptions' => [
                        'inputType' => Editable::INPUT_DROPDOWN_LIST,
                        'data' => $statusList,
                        'header' => '报名状态',
                        'resetButton' => ['style' => "display:none;"],
                        'pluginEvents' => [
                            "editableSuccess" => "function(event, val, form, data) { location.reload() }",
                        ],
                    ],
                ],
                [
                    'label' => "报名渠道",
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => 'middle',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a('报名渠道', ['/seo', 'matchid' => $model->id]);
                        //return Html::a(Yii::$app->params['ydb_host'] . $model->id,Yii::$app->params['ydb_host'] . $model->id,['target'=>'_blank']);
                    }
                ],
                [
                    'attribute' => 'title',
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => 'middle',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a(\common\components\Helper::cutstr($model->title, 30), array_merge(['/match/update', 'id' => $model->id], $_GET), ['title' => $model->title]);
                    }
                ],

                [
                    'attribute' => 'address',
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => GridView::ALIGN_MIDDLE
                ],
                [
                    'attribute' => 'matchtime',
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => 'middle',
                ],
                [
                    'attribute' => 'matchregtime',
                    'hAlign' => GridView::ALIGN_CENTER,
                    'vAlign' => 'middle',
                ],


            ],
        ]); ?>
    </div>

<?php else: ?>
    <?php
    $tmp = [];
    $data = Match::find()->andWhere(['status'=>1])->select(['title', 'end_time', 'address', 'category_id'])->asArray()->all();


    foreach ($data as $key => $v) {
        $val = [];
        if ($v['end_time'] > 0) {
            $val['start'] = date("Y-m-d", $v['end_time']);
            $val['title'] = $v['title'];
            $val['address'] = $v['address'];
            $val['end_time'] = date("Y-m-d", $v['end_time']);
            array_unshift($tmp, $val);
        }
    }
    ?>


    <div class="panel panel-<?= AppAsset::BOX_CLASS ?>">
        <div class="panel-heading <?= AppAsset::BOX_BORDER ?>">
            <h3 class="panel-title"><?= $this->title ?></h3>
        </div>
        <div id='calendar' class="panel-body" style="padding:0px;">

        </div>
    </div>


    <?php $this->beginBlock('js') ?>
    var data = <?= json_encode($tmp) ?>;

    $('#calendar').fullCalendar({
        customButtons: {
            viewlist: {
                text: '列表模式查看',
                click: function () {
                    window.location.href = window.location.origin + "/match/index";
                }
            }
        },
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,basicWeek,basicDay,listMonth,viewlist'
        },
        lang: 'zh-CN',
        navLinks: true, // can click day/week names to navigate views
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        events: data,
        eventRender: function (event, element) {
            element.qtip({
                content: "名称:" + event.title + "<br>举办地点:" + event.address + "<br>举办时间:" + event.end_time,
                position: {
                    my: 'top left',  // Position my top left...
                    at: 'bottom left', // at the bottom right of...
                    target: element // my target
                }
            });
        }

    })

    <?php $this->endBlock() ?>
    <?php $this->registerJs($this->blocks['js']) ?>

<?php endif; ?>

<?= $this->registerCss('.dropdown-menu{padding:0px 0;} .table{margin-bottom:0px;}') ?>
