<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Address;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Search\AddressCheckSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '场馆每日检查管理';
$this->params['breadcrumbs'][] = $this->title;
$pre = $dataProvider->pagination->getPageCount();
$count = $dataProvider->getCount();
$totalCount = $dataProvider->getTotalCount();
$begin = $dataProvider->pagination->getPage() * $dataProvider->pagination->pageSize + 1;
$end = $begin + $count - 1;
?>
<?= $this->render('_search', ['model' => $searchModel]); ?>
<section class="scrollable padder">
    <div class="row bg-light m-b">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold">场馆每日检查列表
                    <div class="pull-right">
                        <div class="summary">
                            第<b><?= $begin . '-' . $end ?></b>条, 共<b><?= $dataProvider->totalCount ?></b>条数据.
                        </div>
                    </div>
                </header>
                <div class="panel-body">
                    <div class="swim-address-check-index">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                'id',
                                [
                                    'label' => '场馆',
                                    'value' => function ($model) {
                                        return (new Address())->getNameByID($model->swim_address_id);
                                    }
                                ],
                                //'user_channel_id',
                                [
                                    'label' => '检查员昵称',
                                    'value' => function($model) {
                                        if (isset($model->userChannel->userInfo)) {
                                            return $model->userChannel->userInfo->nickname;
                                        }
                                        return '';
                                    }
                                ],
                                [
                                    'label' => '检查员位置信息',
                                    'format' => 'raw',
                                    'value' => function($model) {
                                        $modelAddress = $model->address;
                                        if (isset($modelAddress) && !empty($modelAddress->longitude) && !empty($model->longitude)) {
                                            return Html::a('点击查看', ['position-info', 'id' => $model->id], ['target' => '_blank']);
                                        }
                                        return '暂无位置信息';
                                    }
                                ],
                                'check_date',
                                [
                                    'label' => '评论数',
                                    'attribute' => 'comment_num'
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => '操作',
                                    'template' => '{view} {export} &nbsp&nbsp&nbsp&nbsp&nbsp {delete} | {comment}',
                                    'buttons' => [
                                        'export' => function($url, $model){
                                            return Html::a('<span class="glyphicon glyphicon-download"></span>',
                                                ['export', 'id' => $model->id], [
                                                'data' => [
                                                    'method' => 'post',
                                                ],
                                            ]);
                                        },
                                        'delete' => function($url, $model){
                                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], [
                                                'data' => [
                                                    'confirm' => '确认删除?',
                                                    'method' => 'post',
                                                ],
                                            ]);
                                        },
                                        'comment' => function($url, $model){
                                            return Html::a('查看评论及回复',
                                                ['swim-address-check-comment/index', 'id' => $model->id]
                                            );
                                        },
                                    ]
                                ],
                            ],
                            'layout' => '{items}{pager}',
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