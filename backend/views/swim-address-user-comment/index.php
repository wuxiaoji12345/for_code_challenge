<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Address;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Search\AddressUserCommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '场馆用户评价管理';
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
                <header class="panel-heading font-bold">场馆用户评价列表
                    <div class="pull-right">
                        <div class="summary">
                            第<b><?= $begin . '-' . $end ?></b>条, 共<b><?= $dataProvider->totalCount ?></b>条数据.
                        </div>
                    </div>
                </header>
                <div class="panel-body">
                    <div class="swim-address-user-comment-index">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'options' => ['style' => 'table-layout:fixed;'],
                            'columns' => [
                                'id',
                                [
                                    'label' => '场馆',
                                    'value' => function ($model) {
                                        return (new Address())->getNameByID($model->swim_address_id);
                                    }
                                ],
                                'user_id',
                                'score',
                                [
                                    'label' => '评价内容',
                                    'contentOptions' => ['style' => 'word-break:break-all;'],
                                    'value' => function ($model) {
                                        return $model->comment;
                                    }
                                ],
                                'comment_date',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => '操作',
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