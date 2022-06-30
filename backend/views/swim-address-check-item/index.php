<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Search\AddressCheckItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '场馆检查项目管理';
$this->params['breadcrumbs'][] = $this->title;
$pre = $dataProvider->pagination->getPageCount();
$count = $dataProvider->getCount();
$totalCount = $dataProvider->getTotalCount();
$begin = $dataProvider->pagination->getPage() * $dataProvider->pagination->pageSize + 1;
$end = $begin + $count - 1;
?>
<p>
    <?= Html::a('创建', ['create'], ['class' => 'btn btn-success']) ?>
</p>
<?= $this->render('_search', ['model' => $searchModel]); ?>
<section class="scrollable padder">
    <div class="row bg-light m-b">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold">场馆检查项目列表
                    <div class="pull-right">
                        <div class="summary">
                            第<b><?= $begin . '-' . $end ?></b>条, 共<b><?= $dataProvider->totalCount ?></b>条数据.
                        </div>
                    </div>
                </header>
                <div class="panel-body">
                    <div class="swim-address-check-item-index">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                'id',
                                'name',
                                [
                                    'label' => '父项目',
                                    'value' => function($model) {
                                        return (new \backend\models\AddressCheckItem())->getNameByID($model->pid);
                                    }
                                ],
                                [
                                    'label' => '检查内容',
                                    'format' => 'html',
                                    'value' => function($model) {
                                        return $model->getCheckInfoForIndexPage();
                                    }
                                ],
                                'weight',
                                [
                                    'attribute' => 'update_time',
                                    'label' => '更新时间',
                                    'enableSorting'=>false,
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