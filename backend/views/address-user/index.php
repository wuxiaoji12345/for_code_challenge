<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\BackendUser;
use backend\models\Address;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Search\BackendUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '场馆用户管理';
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
                <header class="panel-heading font-bold">列表
                    <div class="pull-right">
                        <div class="summary">
                            第<b><?= $begin . '-' . $end ?></b>条, 共<b><?= $dataProvider->totalCount ?></b>条数据.
                        </div>
                    </div>
                </header>
                <div class="panel-body">
                    <div class="user-index">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                'id',
                                'username',
                                [
                                    'label' => '所属场馆',
                                    'value' => function ($model) {
                                        if ($model->swim_address_id == 0) {
                                            return '';
                                        }
                                        return (new Address())->getNameByID($model->swim_address_id);
                                    }
                                ],
                                [
                                    'label' => '状态',
                                    'value' => function($model) {
                                        return isset(BackendUser::$STATUS[$model->status])
                                            ? BackendUser::$STATUS[$model->status] : '';
                                    }
                                ],
                                [
                                    'label' => '创建时间',
                                    'value' => function($model) {
                                        return date('Y-m-d H:i:s', $model->created_at);
                                    }
                                ],
                                [
                                    'label' => '更新时间',
                                    'value' => function($model) {
                                        return date('Y-m-d H:i:s', $model->updated_at);
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => '操作',
                                    'template' => '{update} &nbsp&nbsp&nbsp&nbsp&nbsp {delete}',
                                    'buttons' => [
                                        'update' => function($url, $model){
                                            if ($model->status != 10) {
                                                return '';
                                            }
                                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id]);
                                        },
                                        'delete' => function($url, $model){
                                            if ($model->status != 10) {
                                                return '';
                                            }
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